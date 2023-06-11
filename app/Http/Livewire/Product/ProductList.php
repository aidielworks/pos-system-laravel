<?php

namespace App\Http\Livewire\Product;

use App\Models\Category;
use App\Models\Product;
use Livewire\Component;
use Livewire\WithPagination;

class ProductList extends Component
{
    use WithPagination;

    public $categories = null;
    public $selected_category = 0;
    public $search_items = '';

    public function select_category($category_id)
    {
        $this->selected_category = $category_id;
        $this->resetPage();
    }

    public function test()
    {
        $this->dispatchBrowserEvent('confirmDelete');
    }
    public function mount()
    {
        $this->categories = Category::all();
    }

    public function render()
    {
        $products = Product::when($this->search_items != '', fn($query) => $query->where('product_name', 'LIKE', '%'.$this->search_items.'%'))
            ->when($this->selected_category != 0, fn($query)=> $query->where('category_id', $this->selected_category))
            ->orderBy('product_name', 'asc')
            ->paginate(10);

        return view('livewire.product.product-list', [
            'products' => $products
        ]);
    }
}
