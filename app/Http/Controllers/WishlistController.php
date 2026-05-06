<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlists = Wishlist::where('customer_id', auth('customer')->id())
            ->with('product.brand')
            ->latest()
            ->get();

        $customer = auth('customer')->user();

        return view('account.wishlist', compact('wishlists', 'customer'));
    }

    public function toggle(Request $request)
    {
        if (! auth('customer')->check()) {
            return response()->json(['error' => 'login_required'], 401);
        }

        $customerId = auth('customer')->id();
        $productId = $request->product_id;

        $existing = Wishlist::where('customer_id', $customerId)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            $existing->delete();

            return response()->json(['status' => 'removed']);
        } else {
            Wishlist::create([
                'customer_id' => $customerId,
                'product_id' => $productId,
            ]);

            return response()->json(['status' => 'added']);
        }
    }
}
