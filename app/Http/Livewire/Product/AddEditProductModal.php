<?php

namespace App\Http\Livewire\Product;

use App\Enum\MealType;
use App\Models\Category;
use Livewire\WithFileUploads;
use LivewireUI\Modal\ModalComponent;

class AddEditProductModal extends ModalComponent
{
    use WithFileUploads;

    public $categories;
    public $product;
    public $meal_types;
    public $is_edit = false;

    public function remove_uploaded_file()
    {
        if(isset($this->product['upload_picture'])){
            unset($this->product['upload_picture']);
        }
    }

    public function mount()
    {
        $this->categories = Category::all();
        $this->meal_types = collect(MealType::cases())->mapWithKeys(fn ($type) => [$type->value => $type->getLabel()])->toArray();
    }

    public function render()
    {
        return view('livewire.product.add-edit-product-modal');
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
