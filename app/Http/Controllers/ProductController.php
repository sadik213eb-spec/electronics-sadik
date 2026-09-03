<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductBanner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display the single product page
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->where('status', 'active')
            ->with(['category.parent', 'brand'])
            ->firstOrFail();

        $related = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('status', 'active')
            ->limit(6)
            ->get();

        $reviews = $product->reviews()->where('status', 'approved')->latest()->get();
        $averageRating = $reviews->avg('rating');
        $reviewCount = $reviews->count();

        $productBanner = ProductBanner::where('is_active', true)->latest()->first();

        return view('product', compact(
            'product',
            'related',
            'reviews',
            'averageRating',
            'reviewCount',
            'productBanner'
        ));
    }

    /**
     * Display the category listing page (Parent Category)
     */
    public function category(Request $request, $slug)
    {
        // 1. Find the category by slug
        $category = Category::where('slug', $slug)->firstOrFail();

        // 2. Get all child category IDs to include their products too
        $childCategoryIds = Category::where('parent_id', $category->id)->pluck('id');
        $allCategoryIds = $childCategoryIds->push($category->id);

        // 3. Calculate Min and Max price for the description text
        $minPrice = Product::whereIn('category_id', $allCategoryIds)
            ->where('status', 'active')
            ->min(DB::raw('(CASE WHEN sale_price IS NOT NULL THEN sale_price ELSE price END)'));

        $maxPrice = Product::whereIn('category_id', $allCategoryIds)
            ->where('status', 'active')
            ->max(DB::raw('(CASE WHEN sale_price IS NOT NULL THEN sale_price ELSE price END)'));

        // 4. Filter and Sort Products
        $query = Product::whereIn('category_id', $allCategoryIds)->where('status', 'active');

        $this->applyFilters($query, $request);

        $products = $query->paginate(12)->withQueryString();

        // Get unique brands available in this group of categories
        $brands = Product::whereIn('category_id', $allCategoryIds)
            ->distinct()
            ->pluck('brand_id');

        return view('category', compact('category', 'products', 'brands', 'minPrice', 'maxPrice'));
    }

    /**
     * Display the category listing page (Child Category)
     * URL: /category/parent-slug/child-slug
     */
    public function childCategory(Request $request, $parentSlug, $childSlug)
    {
        // 1. Find the child category by its slug
        $category = Category::where('slug', $childSlug)->firstOrFail();

        // 2. Safety Check: Ensure the child actually belongs to the parent slug provided in URL
        if (!$category->parent || $category->parent->slug !== $parentSlug) {
            abort(404);
        }

        // 3. Calculate Min and Max price for the description text (Only for this specific child)
        $minPrice = Product::where('category_id', $category->id)
            ->where('status', 'active')
            ->min(DB::raw('(CASE WHEN sale_price IS NOT NULL THEN sale_price ELSE price END)'));

        $maxPrice = Product::where('category_id', $category->id)
            ->where('status', 'active')
            ->max(DB::raw('(CASE WHEN sale_price IS NOT NULL THEN sale_price ELSE price END)'));

        // 4. Filter and Sort Products
        $query = Product::where('category_id', $category->id)->where('status', 'active');

        $this->applyFilters($query, $request);

        $products = $query->paginate(12)->withQueryString();

        // Get unique brands available in this child category
        $brands = Product::where('category_id', $category->id)
            ->distinct()
            ->pluck('brand_id');

        return view('category', compact('category', 'products', 'brands', 'minPrice', 'maxPrice'));
    }

    /**
     * Display all products for a given brand, across every category.
     * URL: /brand/{brand}  (route model bound by Brand's route key, e.g. id or slug)
     */
    public function brand(Request $request, Brand $brand)
    {
        $minPrice = Product::where('brand_id', $brand->id)
            ->where('status', 'active')
            ->min(DB::raw('(CASE WHEN sale_price IS NOT NULL THEN sale_price ELSE price END)'));

        $maxPrice = Product::where('brand_id', $brand->id)
            ->where('status', 'active')
            ->max(DB::raw('(CASE WHEN sale_price IS NOT NULL THEN sale_price ELSE price END)'));

        $query = Product::where('brand_id', $brand->id)->where('status', 'active');

        $this->applyFilters($query, $request);

        $products = $query->paginate(12)->withQueryString();

        $brands = Product::where('brand_id', $brand->id)
            ->distinct()
            ->pluck('brand_id');

        // Reuse the existing category view — pass a lightweight stand-in
        // object so the view's $category->name / breadcrumb code still works.
        $category = (object) [
            'name' => $brand->name,
            'banner' => null,
        ];
        $breadcrumbMiddle = 'brand';

        return view('category', compact('category', 'products', 'brands', 'minPrice', 'maxPrice', 'breadcrumbMiddle'));
    }

    /**
     * Helper method to avoid repeating filter logic across listing methods
     */
    private function applyFilters($query, Request $request)
    {
        // Price Filter
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Brand Filter
        if ($request->has('brands') && is_array($request->brands)) {
            $query->whereIn('brand_id', $request->brands);
        }

        // Sorting
        if ($request->sort == 'price_low') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort == 'price_high') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }
    }
}
