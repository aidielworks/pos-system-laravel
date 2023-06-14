<?php

namespace App\Http\Livewire\Pos;

use App\Enum\PaymentType;
use LivewireUI\Modal\ModalComponent;

class PayModal extends ModalComponent
{
    public $carts = [];
    public $subtotal = 0;
    public $discount = 0;
    public $total = 0;
    public $pay_amount = 0;
    public $change = 0;
    public $payment_types;
    public $order_no;

    public function mount()
    {
        $this->calculateCart();
        $this->payment_types = collect(PaymentType::cases())->mapWithKeys(fn ($type) => [$type->value => $type->getLabel()])->toArray();
    }

    public function updatedPayAmount()
    {
        $this->change = $this->pay_amount == '' || $this->pay_amount == 0 ? 0 : floatval($this->pay_amount) - floatval($this->total);
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

    public function render()
    {
        return view('livewire.pos.pay-modal');
    }

    /**
     * Supported: 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'
     */
    public static function modalMaxWidth(): string
    {
        return '3xl';
    }

    public static function closeModalOnClickAway(): bool
    {
        return false;
    }
}
