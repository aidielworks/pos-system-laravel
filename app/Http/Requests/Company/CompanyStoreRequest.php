<?php

namespace App\Http\Requests\Company;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Monarobase\CountryList\CountryListFacade;

class CompanyStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            "brand_name" => ['required', 'string'],
            "owner" => ['required', 'string'],
            "phone_number" => ['required', 'string'],
            "email" => ['required', 'email'],
            "address" => ['required', 'string'],
            "postcode" => ['required', 'string'],
            "city" => ['required', 'string'],
            "state" => ['required', 'string'],
            "country" => ['required', 'string', Rule::in(array_keys(CountryListFacade::getList()))],
            "logo" => ['sometimes', 'nullable', 'image']
        ];
    }
}
