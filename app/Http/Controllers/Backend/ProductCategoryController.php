<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function index()
    {
        return view('backend.product-category.index');
    }

    public function create()
    {
        return view('backend.product-category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_name' => 'required|string|max:255',
        ]);

        $name = $request->category_name;

        Category::create([
            'name' => $name,
        ]);

        return redirect()->route('admin.product-category.index')->withFlashSuccess(__('Category successfully created.'));
    }

    public function edit(Request $request, Category $category)
    {
        // if category id is 1, then return back to the previous page with a message
        if ($category->id == 1) {
            return redirect()->back()->withFlashDanger('You cannot edit this category.');
        }

        return view('backend.product-category.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        // if category id is 1, then return back to the previous page with a message
        if ($category->id == 1) {
            return redirect()->back()->withFlashDanger('You cannot edit this category.');
        }

        $request->validate([
            'category_name' => 'required|string|max:255',
        ]);

        $name = $request->category_name;

        $category->update([
            'name' => $name,
        ]);

        return redirect()->route('admin.product-category.index')->withFlashSuccess(__('Category successfully updated.'));
    }

    public function destroy(Category $category)
    {
        // if category id is 1, then return back to the previous page with a message
        if ($category->id == 1) {
            return redirect()->back()->withFlashDanger('You cannot delete this category.');
        }

        // if category has products, then return back to the previous page with a message

        if ($category->products !== null && $category->products->count() > 0) {
            return redirect()->back()->withFlashDanger('You cannot delete this category because it has products.');
        }


        $category->delete();

        return redirect()->route('admin.product-category.index')->withFlashSuccess(__('Category successfully deleted.'));
    }
}
