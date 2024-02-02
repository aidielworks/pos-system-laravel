<?php

namespace App\Models;

use App\Enum\TableStatus;
use App\Http\Traits\CompanyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Table extends Model
{
    use HasFactory, CompanyTrait;

    protected $fillable = [
        'table_no', 'status', 'qr_url'
    ];

    protected $casts = [
        'status' => TableStatus::class,
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function generateQR()
    {
        $path = '/qr_code/table_qr_' . time() . '.png';
        $encrypt = encrypt(['table_id' => $this->id]);
        $qr = QrCode::format('png')
                ->size(300)
                ->generate(route('order.selfOrder', ['order' => $encrypt]));
        Storage::disk('public')->put($path, $qr);

        $this->update(['qr_url' => $path]);
    }
}
