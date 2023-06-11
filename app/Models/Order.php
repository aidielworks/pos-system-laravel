<?php

namespace App\Models;

use App\Enum\OrderStatus;
use App\Http\Traits\CompanyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory, CompanyTrait;

    protected $fillable = [
        'company_id', 'order_no', 'discount_id', 'subtotal_amount',
        'discount_amount', 'total_amount', 'paid_amount', 'status',
        'created_by', 'parent_order_id'
    ];

    protected $casts = [
      'status' => OrderStatus::class,
    ];

    public function order_items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function order_children()
    {
        return $this->hasMany($this, 'parent_order_id');
    }
}
