<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'brand_name',
        'owner',
        'phone_number',
        'email',
        'address',
        'postcode',
        'city',
        'state',
        'country',
        'registration_number',
        'status',
        'logo_url'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user');
    }
}
