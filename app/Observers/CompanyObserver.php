<?php

namespace App\Observers;

use Illuminate\Support\Facades\Auth;

class CompanyObserver
{
    public function getCompanyId(): int
    {
        return Auth::user()->company->first()->id ?? 0;
    }

    public function updating($model): void
    {
        $model->company_id = $this->getCompanyId();
    }

    public function creating($model): void
    {
        $model->company_id = $this->getCompanyId();
    }
}
