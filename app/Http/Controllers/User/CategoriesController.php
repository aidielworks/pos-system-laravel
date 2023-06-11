<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryStore;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class CategoriesController extends Controller
{
    public function store(CategoryStore $request)
    {
        $validated = $request->validated();
        $result = DB::transaction(fn() => Category::create($validated));

        if($result){
             Alert::success('Category created!');
             session()->flash('setting_tab', 1);
        } else {
             Alert::error('Failed creating category!');
        }

        return redirect()->route('company.index');
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
            Alert::success('Category updated!');
            session()->flash('setting_tab', 1);
        } else {
            Alert::error('Failed updating category!');
        }

        return redirect()->route('company.index');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        session()->flash('setting_tab', 1);
        return redirect()->route('company.index')->withSuccess('Category deleted!');
    }
}
