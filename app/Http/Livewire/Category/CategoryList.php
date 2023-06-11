<?php

namespace App\Http\Livewire\Category;

use App\Models\Category;
use Livewire\Component;

class CategoryList extends Component
{
    protected $listeners = ['renderComponent' => '$refresh'];

    public function render()
    {
        return view('livewire.category.category-list', [
            'categories' => Category::paginate(10)
        ]);
    }
}
