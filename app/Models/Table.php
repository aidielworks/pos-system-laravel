<?php

namespace App\Models;

use App\Enum\TableStatus;
use App\Http\Traits\CompanyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    use HasFactory, CompanyTrait;

    protected $fillable = ['table_no', 'status'];

    protected $casts = [
        'status' => TableStatus::class,
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
