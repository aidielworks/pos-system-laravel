<?php

namespace App\Http\Livewire\Order;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;

class OrderList extends Component
{
    use WithPagination;
    public $list_type = 'tile';

    public $search_items = '';

    public $order = 'asc';

    public $paginate = 12;

    public function selectListType($list_type)
    {
        $this->list_type = $list_type;
        $this->paginate = 12;
        if($list_type == 'list'){
            $this->paginate = 10;
        }
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.order.order-list', [
            'orders' => Order::when($this->search_items != '', fn($query) => $query->where('order_no', 'LIKE','%'. $this->search_items .'%'))
                ->orderBy('status', $this->order)
                ->orderBy('created_at', 'desc')
                ->paginate($this->paginate),
        ]);
    }
}
