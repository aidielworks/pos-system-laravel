<?php

namespace App\Http\Traits;

use App\Models\Scopes\CompanyScope;
use App\Observers\CompanyObserver;

trait CompanyTrait
{
    public static function bootCompanyTrait()
    {
        static::observe(new CompanyObserver);
        static::addGlobalScope(new CompanyScope);
    }
}