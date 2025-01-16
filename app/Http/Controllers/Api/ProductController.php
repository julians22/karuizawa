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
        // size:
        // color:
        // sort: 'Newest', 'Oldest', 'Price: Low to High', 'Price: High to Low'
        $products = Product::validProduct()
            ->when($request->has('search'), function ($query) use ($request) {
                $query->where('product_name', 'like', '%' . $request->search . '%');
            })
            ->when($request->has('size'), function ($query) use ($request) {
                $query->where('product_name', 'like', 'ukuran' . $request->size . '%')
                    ->orWhere('product_name', 'like', 'size ' . $request->size . '%')
                    ->orWhere('product_name', 'like', 'Size' . $request->size . ' %')
                    ->orWhere('product_name', 'like', 'Ukuran' . $request->size . ' %');

            })
            ->when($request->has('color'), function ($query) use ($request) {
                $query->where('product_name', 'like', '% ' . $request->color . '%');
            })
            ->when($request->has('sort'), function ($query) use ($request) {
                if ($request->sort == 'Newest') {
                    $query->orderBy('updated_at', 'desc');
                } elseif ($request->sort == 'Oldest') {
                    $query->orderBy('updated_at', 'asc');
                } elseif ($request->sort == 'Price: Low to High') {
                    $query->orderBy('price', 'asc');
                } elseif ($request->sort == 'Price: High to Low') {
                    $query->orderBy('price', 'desc');
                }
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
