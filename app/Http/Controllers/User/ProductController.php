<?php

namespace App\Http\Controllers\User;

use App\Enum\MealType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Traits\CompanyTrait;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class ProductController extends Controller
{
    use CompanyTrait;

    public function store(ProductStoreRequest $request)
    {
        $validated = $request->validated();

        $result = DB::transaction(function () use($validated, $request) {
            $product = Product::create($validated);

            if (isset($validated['upload_picture'])) {
                $dir = 'products/'.hash('sha256', $product->id);
                $image = $validated['upload_picture'];
                $imageName = hash('sha256', time().$image->getClientOriginalName()).'.'.$image->extension();

                $request->upload_picture->storeAs('public/'.$dir, $imageName);
                $product->picture_url = $dir.'/'.$imageName;
                $product->save();
            }

            return $product;
        });

        if($result){
            Alert::success('Product created!');
            session()->flash('setting_tab', 2);
        } else {
            Alert::error('Failed creating product!');
        }

        return redirect()->route('company.index');
    }

    public function update(ProductStoreRequest $request, Product $product)
    {
        $validated = $request->validated();
        $result = DB::transaction(function () use ($product, $validated, $request){
            if (isset($validated['upload_picture'])) {
                if(Storage::exists('public/'.$product->picture_url)){
                    Storage::delete('public/'.$product->picture_url);
                }

                $dir = 'products/'.hash('sha256', $product->id);
                $image = $validated['upload_picture'];
                $imageName = hash('sha256', time().$image->getClientOriginalName()).'.'.$image->extension();
                $file_path = $dir.'/'.$imageName;
                $request->upload_picture->storeAs('public/'.$dir, $imageName);
                $validated['picture_url'] = $file_path;
            }

            return $product->update($validated);
        });

        if($result){
            Alert::success('Product updated!');
            session()->flash('setting_tab', 2);
        } else {
            Alert::error('Failed updating product!');
        }

        return  redirect()->route('company.index');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        session()->flash('setting_tab', 2);
        return redirect()->route('company.index')->withSuccess('Product deleted');
    }
}
