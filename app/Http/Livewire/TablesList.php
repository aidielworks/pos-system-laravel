<?php

namespace App\Http\Livewire;

use App\Models\Table;
use Livewire\Component;
use Livewire\WithPagination;

class TablesList extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.tables-list', [
            'tables' => Table::paginate(20)
        ]);
    }
}
