<?php

namespace App\Http\Requests\Category;

use App\Enum\ProductStatus;
use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CategoryStore extends FormRequest
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
        return [
            'categories_name' => ['required','string'],
            'picture_url' => ['nullable', 'string'],
            'status' => ['sometimes', 'integer', new Enum(ProductStatus::class)],
        ];
    }
}
