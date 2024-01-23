<?php

namespace App\Http\Livewire;

use App\Models\Table;
use LivewireUI\Modal\ModalComponent;

class CreateEditTableModal extends ModalComponent
{
    public Table $table;

    protected $rules = [
        'table.table_no' => ['required'],
        'table.status' => ['required'],
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function render()
    {
        return view('livewire.create-edit-table-modal');
    }

    public static function modalMaxWidth(): string
    {
        return 'xl';
    }

}
