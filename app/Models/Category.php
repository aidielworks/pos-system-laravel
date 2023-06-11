<?php

namespace App\Models;

use App\Http\Traits\CompanyTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, CompanyTrait, SoftDeletes;

    protected $fillable = [
        'company_id',
        'categories_name',
        'picture_url',
        'status'
    ];

    protected $cast = [
        'status' => 'boolean'
    ];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}
