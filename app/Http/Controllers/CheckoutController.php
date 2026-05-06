<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    private function getIdentifier()
    {
        if (auth('customer')->check()) {
            return ['user_id' => auth('customer')->id()];
        }

        return ['session_id' => session()->getId()];
    }

    private function generateOrderNumber(): string
    {
        $lastOrder = Order::where('order_number', 'like', 'SA%')
            ->orderByRaw('CAST(SUBSTRING(order_number, 3) AS UNSIGNED) DESC')
            ->first();

        $lastNumber = $lastOrder ? intval(substr($lastOrder->order_number, 2)) : 10000;

        return 'SA' . ($lastNumber + 1);
    }

    public function index()
{
    $identifier = $this->getIdentifier();

    $items = Cart::where($identifier)
        ->with('product')
        ->get();

    if ($items->isEmpty()) {
        return redirect('/cart')->with('error', 'Your cart is empty!');
    }

    $subtotal = $items->sum(function ($item) {
        return ($item->product->sale_price ?? $item->product->price) * $item->quantity;
    });

    //  Get coupon from session
    $coupon   = session('coupon');
    $discount = $coupon['discount'] ?? 0;

    //  Load saved addresses if customer is logged in
    $savedAddresses = collect();
    $defaultAddress = null;

    if (auth('customer')->check()) {
        $savedAddresses = auth('customer')->user()->addresses()->get();
        $defaultAddress = $savedAddresses->where('is_default', 1)->first()
            ?? $savedAddresses->first();
    }

    return view('checkout', compact(
        'items', 'subtotal', 'savedAddresses',
        'defaultAddress', 'coupon', 'discount'
    ));
}

    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:cod,pfs',
            'shipping_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:15',
            'shipping_address' => 'required|string',
            'shipping_city' => 'required|string|max:255',
            'shipping_zone' => 'required|in:inside_dhaka,outside_dhaka',
        ]);

        $identifier = $this->getIdentifier();
        $items = Cart::where($identifier)->with('product')->get();

        if ($items->isEmpty()) {
            return redirect('/cart')->with('error', 'Your cart is empty!');
        }

        $subtotal = $items->sum(
            fn($item) => ($item->product->sale_price ?? $item->product->price) * $item->quantity
        );

        // Shipping cost — max from all products
        $shippingCost = 0;
        foreach ($items as $item) {
            $cost = $request->shipping_zone === 'inside_dhaka'
                ? ($item->product->shipping_inside_dhaka ?? 60)
                : ($item->product->shipping_outside_dhaka ?? 120);
            $shippingCost = max($shippingCost, $cost);
        }

        $grandTotal = $subtotal + $shippingCost;

        // Get coupon discount from session
        $discount = session('coupon.discount', 0);
        $grandTotal = $subtotal + $shippingCost - $discount;

        $order = Order::create([
            'order_number' => $this->generateOrderNumber(),
            'customer_id' => auth('customer')->id() ?? null,
            'shipping_name' => $request->shipping_name,
            'shipping_phone' => $request->shipping_phone,
            'shipping_address' => $request->shipping_address,
            'shipping_city' => $request->shipping_city,
            'shipping_zip' => $request->shipping_zip ?? null,
            'shipping_zone' => $request->shipping_zone,
            'customer_note' => $request->customer_note ?? null,
            'payment_method' => $request->payment_method,
            'payment_status' => 'pending',
            'order_status' => 'processing',
            'total_amount' => $subtotal,
            'shipping_cost' => $shippingCost,
            'discount_amount' => $discount,
            'grand_total' => $grandTotal,
        ]);

        // ✅ Clear coupon after order placed
        session()->forget('coupon');

        foreach ($items as $item) {
            $price = $item->product->sale_price ?? $item->product->price;
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'unit_price' => $price,
                'sale_price' => $item->product->sale_price ?? null,
                'subtotal' => $price * $item->quantity,
            ]);
        }

        Cart::where($identifier)->delete();

        return redirect('/order-success/' . $order->id);
    }
}
