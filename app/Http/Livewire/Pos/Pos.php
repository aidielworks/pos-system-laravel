<?php

namespace App\Http\Livewire\Pos;

use App\Enum\TableStatus;
use App\Models\Category;
use App\Models\Product;
use App\Models\Table;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use Livewire\Component;

class Pos extends Component
{
    use LivewireAlert;

    const TYPE_DINE_IN = 1;
    const TYPE_TAKEAWAY = 2;

    public bool $show_pos = false;
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

    public function chooseType($type)
    {
        if (in_array($type, [self::TYPE_DINE_IN, self::TYPE_TAKEAWAY])) {
            $this->show_pos = true;
            $this->selected_type = $type;

            if ($type == self::TYPE_TAKEAWAY) {
                if(session()->has($this->_cart_session_key)){
                    $this->carts = session()->get($this->_cart_session_key);
                    $this->calculateCart();
                }
            }
        }
    }

    public function chooseTable($table_id)
    {
        $this->current_table_cart_session_key = $this->_cart_session_key . $this->selected_table_id;

        if(session()->has($this->current_table_cart_session_key)){
            $this->alert('success', 'Basic Alert');
        }

        $this->carts = [];
        $this->calculateCart();

        $this->selected_table_id = $table_id;
        $table = $this->available_tables->where('id', $this->selected_table_id)->first();
        $this->selected_table_no = $table->table_no;

        if(session()->has($this->current_table_cart_session_key)){
            $this->carts = session()->get($this->current_table_cart_session_key);
            $this->calculateCart();
        }

        $this->show_pos = true;
    }

    public function clearSteps()
    {
        $this->show_pos = false;
        $this->selected_type = self::TYPE_DINE_IN;
        $this->selected_table_id = null;
        $this->selected_table_no = null;
    }

    public function selectCategories($category_id)
    {
        $this->selected_categories = $category_id;
        if ($category_id != 0) {
            $products = Category::with([
                'products' => fn($query) => $query->orderBy('product_name', 'asc')->when($this->search_items != '', fn($query) => $query->where('product_name', 'LIKE', '%'.$this->search_items.'%'))
            ])->find($category_id)->products;
        } else {
            $products = Product::when($this->search_items != '', fn($query) => $query->where('product_name', 'LIKE', '%'.$this->search_items.'%'))->orderBy('product_name', 'asc')->get();
        }
        $this->products = $products;
    }

    public function updatedSearchItems($value)
    {
        $this->selectCategories($this->selected_categories);
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
                session([$this->current_table_cart_session_key => $this->carts]);
            } else {
                session([$this->_cart_session_key => $this->carts]);
            }
        }
    }

    public function mount($selected_table_id = null)    {
        $this->order_no = 'OD-'.date('his');
        $this->categories = Category::all();
        $this->products = Product::orderBy('product_name', 'asc')->get();
        $this->available_tables = Table::where('status', TableStatus::AVAILABLE)->with('orders')->get();

        if ($selected_table_id) {
            $this->selected_table_id = $selected_table_id;
            $table = $this->available_tables->where('id', $this->selected_table_id)->first();
            $this->selected_table_no = $table->table_no;
            $this->self_order = true;
            $this->current_table_cart_session_key = $this->_cart_session_key . $this->selected_table_id;

            if(session()->has($this->current_table_cart_session_key)){
                $this->carts = session()->get($this->current_table_cart_session_key);
                $this->calculateCart();
            }

            $this->show_pos = true;
        }
    }

    public function render()
    {
        return view('livewire.pos.pos');
    }
}
