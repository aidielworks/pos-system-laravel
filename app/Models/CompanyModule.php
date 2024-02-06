<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyModule extends BaseModel
{
    use HasFactory;

    const MODULE = [
        'module_qr'
    ];

    protected $fillable = self::MODULE;
}
