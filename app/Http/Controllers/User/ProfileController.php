<?php

namespace App\Http\Controllers\User;

use App\Enum\MealType;
use App\Enum\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Category;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current-password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function dashboard()
    {
        $categories = Category::pluck('categories_name')->toArray();

        $yesterdayRange = [
            Carbon::today()->subDay()->startOfDay(),
            Carbon::today()->subDay()->endOfDay()
        ];

        $todayRange = [
            Carbon::today()->startOfDay(),
            Carbon::today()->endOfDay()
        ];

        $monthRange = [
            Carbon::today()->startOfMonth(),
            Carbon::today()->endOfMonth(),
        ];

        $food = OrderItem::whereHas('order', fn($q) => $q->where('status', OrderStatus::PAID))
            ->whereHas('product', fn($q) => $q->withTrashed()->where('meal_type', MealType::FOOD));
        $drinks = OrderItem::whereHas('order', fn($q) => $q->where('status', OrderStatus::PAID))
            ->whereHas('product', fn($q) => $q->withTrashed()->where('meal_type', MealType::DRINKS));

        // Today's Sales
        $yesterday_foods = (clone $food)->whereBetween('created_at', $yesterdayRange)->sum('quantity');
        $todays_foods = (clone $food)->whereBetween('created_at', $todayRange)->sum('quantity');

        $yesterday_drinks = (clone $drinks)->whereBetween('created_at', $yesterdayRange)->sum('quantity');
        $todays_drinks = (clone $drinks)->whereBetween('created_at', $todayRange)->sum('quantity');

        $todays_sales_foods = [
            'value' => $todays_foods,
            'percent' => $yesterday_foods == 0 ? 100 : ($todays_foods - $yesterday_foods) / $yesterday_foods * 100
        ];

        $todays_sales_drinks = [
            'value' => $todays_drinks,
            'percent' => $yesterday_drinks == 0 ? 100 : ($todays_drinks - $yesterday_drinks) / $yesterday_drinks * 100
        ];

        //TOP 10 ITEM
        $top_10_query = function ($query) use ($monthRange) {
            return $query->selectRaw('sum(quantity) as quantity, product_id')
                ->whereBetween('created_at', $monthRange)
                ->groupBy('product_id')
                ->with(['product' => function ($q) {
                    $q->select(['id', 'product_name']);
                }])
                ->orderBy('quantity', 'desc')
                ->limit(10)
                ->get();
        };

        $top_10_foods = $top_10_query((clone $food));
        $top_10_drinks = $top_10_query((clone $drinks));


        // TOTAL SALES BY MONTH
        $order_items_by_month = OrderItem::selectRaw('SUM(subtotal) as total, MONTH(created_at) as month')
            ->whereYear('created_at', Carbon::now()->format('Y'))
            ->groupBy('month')
            ->get()
            ->mapWithKeys(function ($item, $key) {
                return [$item['month'] => floatval($item['total'])];
            })->all();

        $month_sales = [];

        for($x = 1; $x <= 12; $x++){
            if(array_key_exists($x, $order_items_by_month)){
                $month_sales[$x] = $order_items_by_month[$x];
            } else {
                $month_sales[$x] = 0;
            }
        }

        $month_sales = array_values($month_sales);

        //Sales By Categories
        $orderItems = OrderItem::whereHas('order', fn($q) => $q->where('status', OrderStatus::PAID))
            ->whereBetween('created_at', $monthRange)
            ->with(['product' => fn($q) => $q->withTrashed()])
            ->select('product_id', DB::raw('SUM(quantity) as count'))
            ->groupBy('product_id')
            ->get();

        $category_counts = $orderItems->groupBy('product.category.id')
            ->map(function ($items) {
                return $items->sum('count');
            })->all();
        ksort($category_counts);
        $category_counts = array_values($category_counts);

        return view('dashboard' , compact(
            'todays_sales_foods', 'todays_sales_drinks', 'top_10_foods', 'top_10_drinks',
            'month_sales', 'categories', 'category_counts'
        ));
    }
}
