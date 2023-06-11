<?php

namespace App\Http\Requests\Product;

use App\Enum\MealType;
use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class ProductStoreRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $prepare_for_validations = [];

        $this->merge($prepare_for_validations);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $ids = Category::all()->pluck('id');
        return [
            'category_id' => ['required', Rule::in($ids)],
            'product_name' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'description' => ['sometimes', 'nullable', 'string'],
            'upload_picture' => ['sometimes', 'nullable', 'image'],
            'meal_type' => ['required', new Enum(MealType::class)]
        ];
    }
}
