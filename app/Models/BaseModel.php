<?php

namespace App\Models;

use App\Http\Traits\CompanyTrait;
use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use CompanyTrait;
    public static function applyCompanyScopeWithId($company_id)
    {
        return static::withoutGlobalScope(CompanyScope::class)
            ->withGlobalScope('company', new CompanyScope($company_id));
    }
}
