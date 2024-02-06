<?php

namespace App\Http\Livewire\Pos;

use App\Enum\TableStatus;
use App\Models\Category;
use App\Models\Product;
use App\Models\Scopes\CompanyScope;
use App\Models\Table;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;
use RealRashid\SweetAlert\Facades\Alert;

class Pos extends Component
{
    use LivewireAlert;

    const TYPE_DINE_IN = 1;
    const TYPE_TAKEAWAY = 2;

    public $show_pos = false;
    public $selected_type = self::TYPE_DINE_IN;
    public $selected_table_id = null;
    public $selected_table_no = null;

    public $selected_categories = 0;
    public $categories;
    public $products = [];
    public $carts = [];
    public $total = 0.00;
    public $subtotal = 0.00;
    public $discount = 0.00;
    public $search_items = '';
    public $order_no = '';
    public $available_tables = [];

    public $_cart_session_key = 'cart_session_';
    public $current_table_cart_session_key = '';

    public $self_order = false;
    public $company_id = null;


    public function chooseType($type)
    {
        if (in_array($type, [self::TYPE_DINE_IN, self::TYPE_TAKEAWAY])) {
            $this->selected_type = $type;

            if ($type == self::TYPE_TAKEAWAY) {
                $this->setCart();
                $this->calculateCart();
            }

            $this->show_pos = true;
        }
    }

    public function chooseTable($table_id)
    {
        $this->initTable($table_id);
        $this->show_pos = true;
    }

    public function clearSteps()
    {
        $this->show_pos = false;
        $this->selected_type = self::TYPE_DINE_IN;
        $this->selected_table_id = null;
        $this->selected_table_no = null;
        $this->current_table_cart_session_key = '';
    }

    public function addToCart($product_id)
    {
        $product = $this->products
            ->where('id', $product_id)
            ->first()
            ->only(['id','product_name', 'price', 'picture_url']);

        $index = array_search($product_id, array_column($this->carts, 'id'));
        if ($index !== false) {
            $this->carts[$index]['quantity']++;
        } else {
            $this->carts[] = [...$product, 'quantity' => 1];
        }

        $this->updateSession();
        $this->calculateCart();
    }

    public function calculateCart()
    {
        // Initialize the total variable
        $total = 0;

        // Iterate over each product and calculate the sum
        foreach ($this->carts as $cart) {
            $price = floatval($cart["price"]); // Convert price to float
            $quantity = intval($cart["quantity"]); // Convert quantity to integer

            $subtotal = $price * $quantity; // Calculate the subtotal for the product
            $total += $subtotal; // Accumulate the subtotal to the total
        }

        $this->subtotal = $total;
        $this->total = roundToNearestHalf($this->subtotal - $this->discount);
    }

    public function incrDecrQuantity($index, $decrease = false)
    {
        if($decrease){
            $this->carts[$index]['quantity']--;
            if($this->carts[$index]['quantity'] == 0){
                $this->removeItem($index);
            }
        } else {
            $this->carts[$index]['quantity']++;
        }

        $this->calculateCart();
    }

    public function removeItem($index)
    {
        unset($this->carts[$index]);
        $this->carts = array_values($this->carts);
        $this->updateSession();
        $this->calculateCart();
    }

    public function removeAll()
    {
        $this->carts = [];
        $this->updateSession(true);
        $this->calculateCart();
    }

    public function initTable($table_id = null, $self_order = false, $self_session_key = null)
    {
        if ($table_id) {
            $this->selected_table_id = $table_id;
            $table = $this->available_tables->where('id', $this->selected_table_id)->first();
            $this->selected_table_no = $table->table_no;
            $this->self_order = $self_order;
            $this->current_table_cart_session_key = $self_session_key ?? $this->_cart_session_key . $this->selected_table_id;

            $this->setCart();
        }
    }

    public function setCart()
    {
        $key = $this->selected_type == self::TYPE_DINE_IN ? $this->current_table_cart_session_key : $this->_cart_session_key;

        if(session()->has($key)){
            $session = session()->get($key);
            if (isset($session['cart'])) {
                $this->carts = $session['cart'];

            }
        } else {
            $this->carts = [];
        }

        $this->calculateCart();
    }

    public function updateSession($forget = false)
    {
        if ($forget) {
            if ($this->selected_type == self::TYPE_DINE_IN && !empty($this->current_table_cart_session_key)) {
                session()->forget($this->current_table_cart_session_key);
            } else {
                $this->carts = [];
                session()->forget($this->_cart_session_key);
                $this->calculateCart();
            }
        } else {
            if ($this->selected_type == self::TYPE_DINE_IN && !empty($this->current_table_cart_session_key)) {
                // Dine In
                session([
                    $this->current_table_cart_session_key => [
                        'cart' => $this->carts,
                        'self_order' => $this->self_order
                    ]
                ]);
            } else {
                // For Take Away
                session([
                    $this->_cart_session_key => [
                        'cart' => $this->carts,
                        'self_order' => $this->self_order
                    ]
                ]);
            }
        }
    }

    public function mount($selected_table_id = null, $self_session_key = null, $company_id = null)
    {
        if (isset($company_id)) {
            $this->company_id = $company_id;
            $this->categories = Category::applyCompanyScopeWithId($company_id)->get();
            $this->products = Product::applyCompanyScopeWithId($company_id)->with('category')->orderBy('product_name', 'asc')->get();
            $this->available_tables = Table::applyCompanyScopeWithId($company_id)->where('status', TableStatus::AVAILABLE)->with('orders')->get();
        } else {
            $this->categories = Category::all();
            $this->products = Product::orderBy('product_name', 'asc')->get();
            $this->available_tables = Table::where('status', TableStatus::AVAILABLE)->with('orders')->get();
        }

        if ($selected_table_id) {
            $this->initTable($selected_table_id, true, $self_session_key);
            $this->show_pos = true;
        }
    }

    public function render()
    {
        return view('livewire.pos.pos');
    }

    // Pos UI function
    public function selectCategories($category_id)
    {
        $this->selected_categories = $category_id;
        if ($category_id != 0) {
            if ($this->company_id) {
                $products = Category::with([
                    'products' => fn($query) => $query->orderBy('product_name', 'asc')->when($this->search_items != '', fn($query) => $query->where('product_name', 'LIKE', '%'.$this->search_items.'%'))
                ])->find($category_id)->products;
            }

        } else {
            $products = Product::when($this->search_items != '', fn($query) => $query->where('product_name', 'LIKE', '%'.$this->search_items.'%'))->orderBy('product_name', 'asc')->get();
        }
        $this->products = $products;
    }

    public function updatedSearchItems($value)
    {
        $this->selectCategories($this->selected_categories);
    }
}
