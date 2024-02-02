<?php

namespace App\Http\Controllers\User;

use App\Enum\MealType;
use App\Enum\OrderStatus;
use App\Enum\PaymentType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderStoreRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Enum;
use RealRashid\SweetAlert\Facades\Alert;

class OrderController extends Controller
{
    public function index()
    {
        return view('order.index');
    }

    public function store(OrderStoreRequest $request)
    {
        $validated = $request->validated();

        if (Arr::exists($validated, 'place_order')) {
            $validated['paid_amount'] = 0;
            $validated['status'] = OrderStatus::UNPAID;
        } elseif (Arr::exists($validated, 'pay')) {
            if( $validated['paid_amount'] < $validated['total_amount'] ){
                Alert::error('Error creating order');
                return redirect()->route('pos.index');
            }
            $validated['status'] = OrderStatus::PAID;
        } else {
            Alert::error('Error creating order');
            return redirect()->route('pos.index');
        }

        $result = DB::transaction(function () use ($validated){
            $order_input = Arr::except($validated, ['order_items']);
            $order_items_input = Arr::only($validated, 'order_items');

            $order = Order::create([...$order_input, 'created_by' => Auth::id()]);

            foreach ($order_items_input['order_items'] as $input){
                $order->order_items()->create($input);
            }

            return $order;
        });

        if($result){
            session()->flash('print-order-receipt', $result->id);
            Alert::success('Order created!');
            if (isset($validated['table_id'])) {
                session()->forget('cart_session_' . $validated['table_id']);
            } else {
                session()->forget('cart_session_');
            }
        } else {
            Alert::error('Failed creating order!');
        }

        return redirect()->route('pos.index');
    }

    public function show(Order $order)
    {
        return view('order.show', compact('order'));
    }

    public function update(OrderStoreRequest $request, Order $order)
    {

    }

    public function addOrder(OrderStoreRequest $request, Order $order)
    {
        $validated = $request->validated();

        [$order, $order_items] = DB::transaction(function () use ($validated, $order){
            $order_items_input = Arr::only($validated, 'order_items');

            $order->subtotal_amount += $validated['subtotal_amount'];
            $order->discount_amount += $validated['discount_amount'];
            $order->total_amount += $validated['total_amount'];

            $order->save();

            foreach ($order_items_input['order_items'] as $input){
                $order_items = $order->order_items();

                if ($order_items = $order_items->where('product_id', $input['product_id'])->first()) {
                    $order_items->quantity += $input['quantity'];
                    $order_items->subtotal += $input['subtotal'];
                    $order_items->save();
                } else {
                    $order->order_items()->create($input);
                }
            }

            return [$order, $order_items_input['order_items']];
        });

        if($order){
            session()->flash('print-order', $order_items);
            Alert::success('Order added!');
            session()->forget('cart-session-'.$order->id);
        } else {
            Alert::error('Failed adding order!');
        }

        return redirect()->route('order.show', $order->id);

    }

    public function payOrder(Request $request, Order $order)
    {
        $validated = $request->validate([
            'paid_amount' => ['required', 'numeric'],
            'payment_method' => ['required_with:pay', new Enum(PaymentType::class)]
        ]);

        $order->status = OrderStatus::PAID;
        $order->paid_amount = $validated['paid_amount'];
        $order->payment_method = $validated['payment_method'];
        $order->save();

        session()->flash('print-receipt', $order->id);
        Alert::success('Order paid!');
        return redirect()->route('order.show', $order->id);
    }

    public function printReceipt(Request $request, Order $order)
    {
        $company = getCompany();
        return view('order.print-receipt', compact('order', 'company'));
    }

    public function printOrder(Request $request)
    {
        $foods = $drinks = [];
        $order = null;

        $input = $request->only('items', 'id');

        if(isset($input['id'])){
            $order = Order::find($input['id']);
        }

        if(isset($input['items'])) {
            $items = collect(json_decode($input['items'], 1));
            $ids = $items->pluck('product_id');
            $products = Product::select('id', 'product_name', 'meal_type')->whereIn('id', $ids)->get();
            $products = $products->map(function ($value) use ($items) {
                $item = $items->where('product_id', $value->id)->first();
                $value->quantity = $item['quantity'];
                $value->price = $item['price'];
                $value->subtotal = $item['subtotal'];
                return $value;
            });

            $meal_type = $products->groupBy('meal_type');

            if(isset($meal_type[MealType::FOOD->value])){
                $foods = $meal_type[MealType::FOOD->value];
            }

            if(isset($meal_type[MealType::DRINKS->value])){
                $drinks = $meal_type[MealType::DRINKS->value];
            }
        }

        if($order && empty($foods) && empty($drinks)){
            $meal_type = $order->order_items()->with(['product' => fn($query) => $query->withTrashed()])->get()->groupBy('product.meal_type');
            if(isset($meal_type[MealType::FOOD->value])){
                $foods = $meal_type[MealType::FOOD->value];
            }

            if(isset($meal_type[MealType::DRINKS->value])){
                $drinks = $meal_type[MealType::DRINKS->value];
            }
        }


        return view('order.print-order', compact('order', 'foods', 'drinks'));
    }

    public function printOrderReceipt(Request $request, Order $order)
    {
        $foods = $drinks = [];
        $company = getCompany();
        $meal_type = $order->order_items()->with('product')->get()->groupBy('product.meal_type');
        if(isset($meal_type[MealType::FOOD->value])){
            $foods = $meal_type[MealType::FOOD->value];
        }

        if(isset($meal_type[MealType::DRINKS->value])){
            $drinks = $meal_type[MealType::DRINKS->value];
        }

        return view('order.print-order-receipt', compact('order', 'company', 'foods', 'drinks'));
    }

    public function selfOrder(Request $request)
    {
        $validated = $request->only('order');

        if(!isset($validated['order'])) {
            return 'error';
        }

        $order = decrypt($validated['order']);


        if (isset($order['table_id'])) {
            $session_key = 'self-order-' . $order['table_id'];
            session()->put($session_key, []);
            
            $table = Table::find($order['table_id']);

            return view('public.order.self-order', compact('order', 'session_key'));
        } else {
            return abort(404);
        }
    }
}
