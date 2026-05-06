<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q');

        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::where('status', 'active')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('sku', 'like', "%{$query}%");
            })
            ->with(['brand', 'category'])
            ->limit(8)
            ->get()
            ->map(function ($product) {
                $images = is_array($product->images)
                    ? $product->images
                    : json_decode($product->images, true);
                $image = isset($images[0])
                    ? route('product.image', basename($images[0]))
                    : asset('images/no-image.png');

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => number_format($product->sale_price ?? $product->price),
                    'image' => $image,
                    'brand' => $product->brand->name ?? '',
                    'category' => $product->category->name ?? '',
                    'url' => '/products/' . $product->slug,
                ];
            });

        return response()->json($products);
    }
    public function index(Request $request)
{
    $query = $request->get('q');
    $products = collect();
    $brands = collect();

    if ($query) {
        $productsQuery = Product::where('status', 'active')
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('sku', 'like', "%{$query}%");
            })
            ->with(['brand', 'category']);

        // Filter by brand
        if (request('brands')) {
            $productsQuery->whereIn('brand_id', request('brands'));
        }

        // Filter by price
        if (request('min_price')) {
            $productsQuery->where('sale_price', '>=', request('min_price'));
        }
        if (request('max_price')) {
            $productsQuery->where('sale_price', '<=', request('max_price'));
        }

        // Sort
        match(request('sort')) {
            'price_low'  => $productsQuery->orderBy('sale_price', 'asc'),
            'price_high' => $productsQuery->orderBy('sale_price', 'desc'),
            default      => $productsQuery->latest(),
        };

        $products = $productsQuery->paginate(20)->withQueryString();

        // Get brands for filter
        $brands = \App\Models\Brand::whereHas('products', function ($q) use ($query) {
            $q->where('status', 'active')
              ->where('name', 'like', "%{$query}%");
        })->get();
    }

    return view('search', compact('products', 'query', 'brands'));
}
}
