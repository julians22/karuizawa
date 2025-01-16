<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

use function React\Promise\all;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::validProduct()
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where('product_name', 'like', '%' . $request->search . '%');
            })
            ->paginate(8);
        return response()->json($products);
    }

    public function findBySlug($slug)
    {
        // slug to array
        $sku = explode(',', $slug);
        $product = Product::whereIn('sku', $sku)->get();
        return response()->json($product);
    }
}
