<?php

namespace App\Models;

use App\Http\Traits\CompanyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'category_id',
        'product_name',
        'price',
        'meal_type',
        'description',
        'picture_url',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class)->withDefault();
    }
}
