<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Company\CompanyStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Monarobase\CountryList\CountryListFacade;
use RealRashid\SweetAlert\Facades\Alert;

class CompanySettingController extends Controller
{
    public function index()
    {
        $company = getCompany();
        $countries = CountryListFacade::getList();

        return view('company.index', compact('countries', 'company'));
    }

    public function store(CompanyStoreRequest $request)
    {
        $validated = $request->validated();
        $company = getCompany();

        $result = DB::transaction(function () use ($validated, $company, $request) {
            if (isset($validated['logo'])) {
                $dir = 'company/'.hash('sha256', $company->id);
                $image = $validated['logo'];
                $imageName = hash('sha256', time().$image->getClientOriginalName()).'.'.$image->extension();
                $request->logo->storeAs('public/'.$dir, $imageName);
                $validated['logo_url'] = $dir.'/'.$imageName;
            }

            return $company->update($validated);
        });

        if ($result){
            Alert::success('Company details updated!');
        } else {
            Alert::error('Error when updating company details.');
        }

        return redirect()->route('company.index');
    }

    public function removeLogo(Request $request)
    {
        $company = getCompany();

        $result = DB::transaction(function () use ($company) {
            $company->logo_url = null;
            $company->save();

            if(Storage::exists('public/'.$company->logo_url)){
                Storage::delete('public/'.$company->logo_url);
            }
            return $company;
        });

        if($result){
            return response()->json(['message' => 'Logo remove!']);
        } else {
            return response()->json(['message' => 'Error removing logo!'], 422);
        }

    }
}
