<?php

namespace App\Models;

use App\Enum\TableStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class Table extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'table_no', 'status', 'qr_url', 'url'
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
        $encrypt = encrypt(['table_id' => $this->id, 'company_id' => $this->company_id]);
        $url = route('order.selfOrder', ['order' => $encrypt]);
        $qr = QrCode::format('png')
                ->size(300)
                ->generate($url);
        Storage::disk('public')->put($path, $qr);

        return $this->update(['qr_url' => $path, 'url' => $url]);
    }
}
