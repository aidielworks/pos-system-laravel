<?php

namespace App\Models;

use App\Http\Traits\CompanyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory, CompanyTrait;

    protected $fillable = [
        'company_id', 'order_id', 'product_id', 'price',
        'quantity', 'subtotal',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
