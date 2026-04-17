<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Libraries\Api\Accurate\Oauth;
use App\Libraries\Api\Accurate\ProductApi;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductActualStock;
use App\Models\Store;
use App\Services\Products\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    public function index()
    {
        return view('backend.product.index');
    }

    public function fetchStock()
    {
        $productApi = new ProductApi();
        $products = $productApi->stockJobs(500, 1);

        if ($products) {
            return redirect()->route('admin.product.index')->withFlashSuccess(__('The product was successfully fetched.'));
        }
        return redirect()->route('admin.product.index')->withFlashDanger(__('An error occurred while fetching the product.'));
    }

    public function fetchPrice()
    {
        // $itemCategory = [350, 500];
        // 350 for Apparel, 500 for Parfume, we can add more category if needed, chack the category id in accurate to get the correct category id
        $productApi = new ProductApi();
        $products = $productApi->productJobs(500, 1, [350, 500], 'id,name,no,unitPrice');

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

    public function editStock(Product $product)
    {
        $product->load('productActualStocks');
        $stores = Store::all();

        $actualStocks = [];

        foreach ($stores as $store) {
            $actualStocks[$store->id] = $product->productActualStocks->where('store_id', $store->id)->first()->stock_quantity ?? 0;
        }

        return view('backend.product.edit-stock', compact('product', 'stores', 'actualStocks'));
    }

    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'stocks' => 'required',
        ]);

        $stocks = $request->stocks;

        DB::beginTransaction();

        try {
            foreach ($stocks as $key => $stock) {
                $actualStock = ProductActualStock::where('product_id', $product->id)->where('store_id', $key)->firstOrCreate([
                    'product_id' => $product->id,
                    'store_id' => $key,
                ], [
                    'stock_quantity' => 0
                ]);

                $actualStock->stock_quantity = $stock;
                $actualStock->save();
            }

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withFlashDanger(__('An error occurred while updating the product stock.'));
        }

        DB::commit();
        return redirect()->route('admin.product.index')->withFlashSuccess(__('The product stock was successfully updated.'));
    }


    public function update(Request $request, Product $product)
    {
        $imageFile = $request->filepond ?? null;

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
                'commerce_title' => $request->commerce_title,
                'commerce_description' => $request->commerce_description,
                'commerce_price' => $request->commerce_price,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withFlashDanger(__('An error occurred while updating the product.'));
        }

        DB::commit();


        if ($imageFile) {
            // prepare the deleted old image if exists
            if ($product->getFirstMedia('featured_image')) {
                $product->getFirstMedia('featured_image')->delete();
            }

            $fileTemp = Storage::disk('karuizawa-temp-file')->path($imageFile);

            $product
                ->addMedia($fileTemp)
                ->toMediaCollection('featured_image');

            // delete the temporary file after adding to media library
            Storage::disk('karuizawa-temp-file')->delete($imageFile);
        }

        return redirect()->route('admin.product.index')->withFlashSuccess(__('The product was successfully updated.'));

    }

}
