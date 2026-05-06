<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

// use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    private function getIdentifier()
    {
        if (auth('customer')->check()) {
            return ['user_id' => auth('customer')->id()];
        }

        return ['session_id' => session()->getId()];
    }


    public function index()
    {
        $items = Cart::where($this->getIdentifier())
            ->with('product.brand')
            ->get();

        $subtotal = $items->sum(function ($item) {
            return ($item->product->sale_price ?? $item->product->price) * $item->quantity;
        });

        return view('cart', compact('items', 'subtotal'));
    }

    public function add(Request $request)
    {
        $product = Product::findOrFail($request->product_id);
        $identifier = $this->getIdentifier();

        $cart = Cart::where($identifier)
            ->where('product_id', $product->id)
            ->first();

        if ($cart) {
            $cart->increment('quantity', $request->quantity ?? 1);
        } else {
            Cart::create(array_merge($identifier, [
                'product_id' => $product->id,
                'quantity' => $request->quantity ?? 1,
            ]));
        }

        return response()->json([
            'success' => true,
            'message' => 'Added to cart!',
            'count' => $this->getCartCount(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::where($this->getIdentifier())->findOrFail($id);
        $cart->update(['quantity' => max(1, $request->quantity)]);

        $item = $cart->fresh('product');
        $subtotal = ($item->product->sale_price ?? $item->product->price) * $item->quantity;

        return response()->json([
            'success' => true,
            'subtotal' => number_format($subtotal),
            'count' => $this->getCartCount(),
        ]);
    }

    public function remove($id)
    {
        Cart::where($this->getIdentifier())->findOrFail($id)->delete();

        return response()->json([
            'success' => true,
            'count' => $this->getCartCount(),
        ]);
    }

    public function count()
    {
        return response()->json(['count' => $this->getCartCount()]);
    }

    private function getCartCount()
    {
        return Cart::where($this->getIdentifier())->sum('quantity');
    }

    public function applyCoupon(Request $request)
    {
        $code = strtoupper(trim($request->coupon_code));

        if (empty($code)) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter a coupon code.',
            ]);
        }

        // Find coupon
        $coupon = \App\Models\Coupon::where('code', $code)->first();

        if (!$coupon) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid coupon code.',
            ]);
        }

        // Check if valid (active + date range)
        if (!$coupon->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'This coupon has expired or is not active.',
            ]);
        }

        // Get cart items
        $items = Cart::where($this->getIdentifier())->with('product')->get();

        if ($items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Your cart is empty.',
            ]);
        }

        // Check if coupon applies to any product in cart
        $applicableSubtotal = 0;
        foreach ($items as $item) {
            if ($coupon->appliesToProduct($item->product_id)) {
                $applicableSubtotal += ($item->product->sale_price ?? $item->product->price) * $item->quantity;
            }
        }

        if ($applicableSubtotal <= 0) {
            return response()->json([
                'success' => false,
                'message' => 'This coupon is not applicable to any product in your cart.',
            ]);
        }

        // Calculate discount
        $discount = $coupon->calculateDiscount($applicableSubtotal);

        // Save coupon to session
        session([
            'coupon' => [
                'code' => $coupon->code,
                'discount' => $discount,
                'type' => $coupon->type,
                'amount' => $coupon->amount,
            ]
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Coupon applied successfully!',
            'discount' => number_format($discount),
            'code' => $coupon->code,
        ]);
    }

    public function removeCoupon()
    {
        session()->forget('coupon');
        return response()->json(['success' => true]);
    }
}
