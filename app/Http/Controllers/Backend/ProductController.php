<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    public function index()
    {
        return view('backend.product.index');
    }

    public function create()
    {
        $categories = Category::all();

        return view('backend.product.create', compact('categories'));
    }

    function show(Product $product) {

        return view('backend.product.show', compact('product'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required',
            'description' => 'sometimes',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $product = Product::create([
                'sku' => $request->sku,
                'product_name' => $request->product_name,
                'description' => $request->description,
                'price' => $request->price,
                'category_id' => $request->category_id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withFlashDanger(__('An error occurred while creating the product.'));
        }

        DB::commit();
        return redirect()->route('admin.product.index')->withFlashSuccess(__('The product was successfully created.'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();

        return view('backend.product.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'product_name' => 'required',
            'description' => 'sometimes',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'sku' => 'required',
        ]);

        DB::beginTransaction();

        try {
            $product->update([
                'sku' => $request->sku,
                'product_name' => $request->product_name,
                'description' => $request->description,
                'price' => $request->price,
                'category_id' => $request->category_id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withFlashDanger(__('An error occurred while updating the product.'));
        }

        DB::commit();
        return redirect()->route('admin.product.index')->withFlashSuccess(__('The product was successfully updated.'));

    }

}
