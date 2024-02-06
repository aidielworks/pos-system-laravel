<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class CompanyScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */

    protected mixed $tenantId = null;

    public function __construct($tenantId = null)
    {
        $this->tenantId = $tenantId;
    }

    public function getCompanyId()
    {
        return $this->tenantId ?: (auth()->check() ? Auth::user()->company->first()->id : null);
    }

    public function apply(Builder $builder, Model $model)
    {
        $builder->where('company_id', $this->getCompanyId());
    }
}
