<?php

namespace App\Http\Livewire;

use App\Models\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Jantinnerezo\LivewireAlert\LivewireAlert;
use LivewireUI\Modal\ModalComponent;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
        $table = $this->table;
        $result = DB::transaction(function () use ($table) {
            $path = '/qr_code/table_qr_' . time() . '.png';

            if (Storage::disk('public')->exists($path)) {
                Storage::delete($path);
            }

            $qr = QrCode::format('png')->size(300)->generate(route('order.orderByQr', $table->id));
            Storage::disk('public')->put($path, $qr);

            return $table->update(['qr_url' => $path]);
        });

        if ($result) {
            $this->alert('success', 'QR Regenerated!');
        }
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
