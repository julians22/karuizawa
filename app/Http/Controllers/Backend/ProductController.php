<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Libraries\Api\Accurate\Oauth;
use App\Libraries\Api\Accurate\ProductApi;
use App\Models\Category;
use App\Models\Product;
use App\Services\Products\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    public function index()
    {
        return view('backend.product.index');
    }

    public function fetchStock(ProductService $productService)
    {
        $auth = new Oauth();
        $auth->makeSignature();

        $productApi = new ProductApi();
        $products = $productApi->stockJobs(100, 2, 'Ashta');

        if ($products) {
            return redirect()->route('admin.product.index')->withFlashSuccess(__('The product was successfully fetched.'));
        }
        return redirect()->route('admin.product.index')->withFlashDanger(__('An error occurred while fetching the product.'));
    }

    public function fetchPrice(ProductService $productService)
    {
        $auth = new Oauth();
        $auth->authInfo();

        $productApi = new ProductApi();
        $products = $productApi->productJobs(40);

        if ($products) {
            return redirect()->route('admin.product.index')->withFlashSuccess(__('The price was successfully fetched.'));
        }

        return redirect()->route('admin.product.index')->withFlashSuccess(__('The sync price was successfully fetched.'));
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
