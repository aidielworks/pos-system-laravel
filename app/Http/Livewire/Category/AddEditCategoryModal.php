<?php

namespace App\Http\Livewire\Category;

use App\Models\Category;
use LivewireUI\Modal\ModalComponent;
use RealRashid\SweetAlert\Facades\Alert;

class AddEditCategoryModal extends ModalComponent
{
    public $category;
    public $is_edit = false;

    public function toggleStatus($id)
    {
        $category = Category::find($id);
        $category->status = !$category->status;
        $category->save();

        $this->emit('renderComponent');
        $this->dispatchBrowserEvent('success', ['message' => 'Status changed!' ]);

    }

    public function render()
    {
        return view('livewire.category.add-edit-category-modal');
    }

    public static function modalMaxWidth(): string
    {
        return 'xl';
    }

    public static function closeModalOnClickAway(): bool
    {
        return false;
    }
}
