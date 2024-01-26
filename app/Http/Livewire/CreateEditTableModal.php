<?php

namespace App\Http\Livewire;

use App\Models\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use LivewireUI\Modal\ModalComponent;

class CreateEditTableModal extends ModalComponent
{
    use LivewireAlert;

    public Table $table;

    protected $listeners = ['refreshComponent' => '$refresh'];

    protected $rules = [
        'table.table_no' => ['required'],
        'table.status' => ['required'],
    ];

    public function regenerateQR()
    {
        $result = DB::transaction(function () {
            if (!is_null($this->table->qr_url) && Storage::disk('public')->exists($this->table->qr_url)) {
                Storage::delete($this->table->qr_url);
            }

            return $this->table->generateQR();
        });

        if ($result) {
            $this->alert('success', 'QR Regenerated!');
        }
    }

    public function download()
    {
        if(!Storage::disk('public')->exists($this->table->qr_url)) {
            $this->regenerateQR();
        }

        return Storage::disk('public')->download($this->table->qr_url);
    }

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
