<?php

namespace App\Http\Livewire\Order;

use App\Enum\PaymentType;
use App\Models\Order;
use App\Models\OrderItem;
use LivewireUI\Modal\ModalComponent;

class PayOrderModal extends ModalComponent
{
    public $pay_amount = 0;
    public $change = 0;
    public $payment_types;

    public $order_id;
    public $order;
    public $order_items;

    public function mount()
    {
        $this->order = Order::find($this->order_id);
        $this->order_items = $this->order->order_items;
        $this->total = $this->order['total_amount'];
        $this->payment_types = collect(PaymentType::cases())->mapWithKeys(fn ($type) => [$type->value => $type->getLabel()])->toArray();
    }

    public function updatedPayAmount()
    {
        $this->change = $this->pay_amount == '' || $this->pay_amount == 0 ? 0 : floatval($this->pay_amount) - floatval($this->total);
    }

    public function render()
    {
        return view('livewire.order.pay-order-modal');
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
