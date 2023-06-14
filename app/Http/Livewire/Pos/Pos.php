<?php

namespace App\Http\Livewire\Pos;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;

class Pos extends Component
{
    public $selected_categories = 0;
    public $categories;
    public $products = [];
    public $carts = [];
    public $total = 0.00;
    public $subtotal = 0.00;
    public $discount = 0.00;
    public $search_items = '';
    public $order_no = '';

    protected $_cart_session_key = 'cart_session';

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

        session([$this->_cart_session_key => $this->carts]);

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
        $this->calculateCart();
    }

    public function removeAll()
    {
        $this->carts = [];
        session()->forget($this->_cart_session_key);
        $this->calculateCart();
    }

    public function mount()
    {
        if(session()->has($this->_cart_session_key)){
            $this->carts = session()->get($this->_cart_session_key);
            $this->calculateCart();
        }

        $this->order_no = 'OD-'.date('his');
        $this->categories = Category::all();
        $this->products = Product::orderBy('product_name', 'asc')->get();
    }

    public function render()
    {
        return view('livewire.pos.pos');
    }
}
