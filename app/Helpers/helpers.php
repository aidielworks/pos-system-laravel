<?php

use Illuminate\Support\Facades\Auth;

if (! function_exists('roundToNearestHalf')) {
    function roundToNearestHalf(float $num): float|int
    {
        $num = (string) $num;

        $whole_number =  strtok($num, '.');
        $first_decimal = substr(strrchr($num, "."), 1,1);
        $decimal = substr(strrchr($num, "."), 2);

        if ($decimal <= 2) {
            return floatval($whole_number.'.'.$first_decimal.'0');
        } elseif ($decimal <= 7) {
            return floatval($whole_number.'.'.$first_decimal.'5');
        } else {
            return floatval($whole_number.'.'.$first_decimal) + 0.1;
        }
    }
}

if (! function_exists('getCompany')) {
    function getCompany($company_id = null)
    {
        $company = $company_id ? \App\Models\Company::find($company_id) : (auth()->check() ? Auth::user()->company->first() : null);

        if($company){
            return $company;
        }

        return null;
    }
}
