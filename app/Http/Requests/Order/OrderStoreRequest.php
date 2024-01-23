<?php

namespace App\Http\Requests\Order;

use App\Enum\PaymentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class OrderStoreRequest extends FormRequest
{
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
            'order_no' => ['required'],
            'discount_id' => ['sometimes', 'nullable', 'integer'],
            'subtotal_amount' => ['required', 'numeric'],
            'discount_amount' => ['required', 'numeric'],
            'total_amount' => ['required', 'numeric'],
            'paid_amount' => ['required_with:pay', 'numeric'],
            'order_items' => ['array'],
            'order_items.*.product_id' => ['required'],
            'order_items.*.price' => ['required', 'numeric'],
            'order_items.*.quantity' => ['required', 'numeric'],
            'order_items.*.subtotal' => ['required', 'numeric'],
            'place_order' => ['sometimes', 'nullable'],
            'pay' => ['sometimes', 'nullable'],
            'payment_method' => ['required_with:pay', new Enum(PaymentType::class)],
            'table_id' => ['nullable']
        ];
    }
}
