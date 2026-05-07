<?php

namespace App\Http\Controllers;

use App\Models\Offer;
use App\Models\Product;
use Illuminate\Http\Request;

class OfferController extends Controller
{
    public function show(Request $request, $slug)
    {
        $offer = Offer::where('slug', $slug)
            ->where('status', 'active')
            ->firstOrFail();

        // Get products linked to this offer
        $productIds = $offer->product_ids ?? [];

        $query = Product::whereIn('id', $productIds)
            ->where('status', 'active');

        // Sorting
        if ($request->sort == 'price_low') {
            $query->orderBy('price', 'asc');
        } elseif ($request->sort == 'price_high') {
            $query->orderBy('price', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->withQueryString();

        return view('offer', compact('offer', 'products'));
    }
}
