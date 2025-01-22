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
        $products = Product::when($request->has('store_id'), function ($query) use ($request) {
                $query->with(['productActualStocks' => function ($query) use ($request) {
                    return $query->select('product_id', 'store_id', 'stock_quantity')
                        ->where('store_id', $request->store_id)
                        ->where('stock_quantity', '>', 0);
                }])
                    ->whereHas('productActualStocks', function ($query) use ($request) {
                        return $query->where('store_id', $request->store_id)
                            ->where('stock_quantity', '>', 0);
                    });
            })
            ->when($request->has('search'), function ($query) use ($request) {
                if (empty($request->search)) {
                    return $query;
                }

                $query->where('product_name', 'like', '%' . $request->search . '%')
                    ->orWhere('sku', 'like', '%' . $request->search . '%');
            })
            ->when($request->has('size'), function ($query) use ($request) {
                if (empty($request->size)) {
                    return $query;
                }
                $query->whereRaw("UPPER(product_name) like '%" . strtoupper($request->size) . "%'");

            })
            ->when($request->has('color'), function ($query) use ($request) {
                if (empty($request->color)) {
                    return $query;
                }
                $query->whereRaw("UPPER(product_name) like '%" . strtoupper($request->color) . "%'");
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
