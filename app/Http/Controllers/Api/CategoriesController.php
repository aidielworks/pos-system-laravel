<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryStore;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $categories = Category::where('status', 1)->get();
        return $this->successResponse('Get Categories', $categories->toArray());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CategoryStore $request)
    {
        $validated = $request->validated();
        $result = DB::transaction(fn() => Category::create($validated));

        if($result){
            return $this->successResponse('Category created!', $result);
        } else {
            return $this->errorResponse('Failed creating category!');
        }
    }


    public function show(Category $category)
    {
        return $this->successResponse('Get category!', $category->load('products')->toArray());
    }

    public function update(CategoryStore $request, Category $category)
    {
        $validated = $request->validated();

        $result = DB::transaction(fn() => $category->update($validated));

        if($result){
            return $this->successResponse('Category updated!', $category->toArray());
        } else {
            return $this->errorResponse('Failed updating category!');
        }
    }

    public function destroy(Category $categories)
    {
        //
    }
}
