<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Traits\CompanyTrait;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    use CompanyTrait;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $product = Product::query();
        if(isset($request->category_id)){
            $product->where('category_id', $request->category_id);
        }
        return $this->successResponse('', $product->get()->toArray());
    }


    public function store(ProductStoreRequest $request)
    {
        $validated = $request->validated();
        $result = DB::transaction(fn () => Product::create($validated));

        if($result){
            return $this->successResponse('product created!', $result);
        } else {
            return $this->errorResponse('Failed creating product!');
        }
    }

    public function show(Product $product)
    {
        return $this->successResponse('Get single product!', $product);
    }

    public function update(ProductStoreRequest $request, Product $product)
    {
        $validated = $request->validated();
        $result = DB::transaction(fn () => $product->update($validated));

        if($result = false){
            return $this->successResponse('product updated!', $product);
        } else {
            return $this->errorResponse('Failed updating product!');
        }
    }

    public function destroy(Product $product)
    {
        //
    }
}
