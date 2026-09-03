<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RewardPointTransaction;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    // 100 points = ৳3, i.e. 1 point = ৳0.03
    const POINTS_TO_TAKA_RATE = 0.03;

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

        $autoDiscount = $items->sum(function ($item) {
            return $item->product->bestAutoDiscountPerUnit() * $item->quantity;
        });


        // Only keep the coupon if this page load is right after applying it here.
        // Any coupon left over from an earlier visit (e.g. the cart page) gets cleared.
        // if (!request()->has('just_applied')) {
        //     session()->forget('coupon');
        // }

        //  Get coupon from session
        $coupon = session('coupon');
        $discount = $coupon['discount'] ?? 0;

        $productDiscount = max($discount, $autoDiscount);

        //  Load saved addresses if customer is logged in
        $savedAddresses = collect();
        $defaultAddress = null;
        $availablePoints = 0;
        $maxPointsDiscount = 0;

        if (auth('customer')->check()) {
            $customer = auth('customer')->user();
            $savedAddresses = $customer->addresses()->get();
            $defaultAddress = $savedAddresses->where('is_default', 1)->first()
                ?? $savedAddresses->first();

            $availablePoints = $customer->totalRewardPoints();
            // Points discount can never exceed the order subtotal (after coupon discount)
            $maxPointsDiscount = min($availablePoints * self::POINTS_TO_TAKA_RATE, $subtotal - $discount);
        }

        return view('checkout', compact(
            'items',
            'subtotal',
            'savedAddresses',
            'defaultAddress',
            'coupon',
            'discount',
            'availablePoints',
            'maxPointsDiscount',
            'autoDiscount',
            'productDiscount'
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
            'points_to_redeem' => 'nullable|integer|min:0',
        ]);

        $identifier = $this->getIdentifier();
        $items = Cart::where($identifier)->with('product')->get();

        if ($items->isEmpty()) {
            return redirect('/cart')->with('error', 'Your cart is empty!');
        }

        $subtotal = $items->sum(
            fn($item) => ($item->product->sale_price ?? $item->product->price) * $item->quantity
        );

        $autoDiscount = $items->sum(function ($item) {
            return $item->product->bestAutoDiscountPerUnit() * $item->quantity;
        });

        // Shipping cost — max from all products
        $shippingCost = 0;
        foreach ($items as $item) {
            $cost = $request->shipping_zone === 'inside_dhaka'
                ? ($item->product->shipping_inside_dhaka ?? 60)
                : ($item->product->shipping_outside_dhaka ?? 120);
            $shippingCost = max($shippingCost, $cost);
        }

        // Get coupon discount from session
        $couponDiscount = session('coupon.discount', 0);

        // ---- Reward points redemption ----
        $pointsRedeemed = 0;
        $pointsDiscount = 0;
        $customer = auth('customer')->check() ? auth('customer')->user() : null;

        $requestedPoints = intval($request->input('points_to_redeem', 0));

        if ($customer && $requestedPoints > 0) {
            $availablePoints = $customer->totalRewardPoints();

            // Never allow redeeming more than the customer actually has
            $pointsRedeemed = min($requestedPoints, $availablePoints);

            // Convert to taka, but never let it exceed what's left to pay after coupon discount
            $maxAllowedDiscount = max(0, $subtotal - $couponDiscount);
            $pointsDiscount = min($pointsRedeemed * self::POINTS_TO_TAKA_RATE, $maxAllowedDiscount);

            // Recalculate the exact number of points that maps to the (possibly capped) discount,
            // so we never deduct more points than the discount actually applied.
            if ($pointsDiscount < $pointsRedeemed * self::POINTS_TO_TAKA_RATE) {
                $pointsRedeemed = (int) ceil($pointsDiscount / self::POINTS_TO_TAKA_RATE);
            }
        }

        $productDiscount = max($couponDiscount, $autoDiscount);
        $totalDiscount = $productDiscount + $pointsDiscount;
        $grandTotal = $subtotal + $shippingCost - $totalDiscount;

        $order = Order::create([
            'order_number' => $this->generateOrderNumber(),
            'customer_id' => $customer->id ?? null,
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
            'discount_amount' => $couponDiscount,
            'points_redeemed' => $pointsRedeemed,
            'points_discount' => $pointsDiscount,
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

        // Deduct the redeemed points immediately (order placed, not waiting on delivery)
        if ($customer && $pointsRedeemed > 0) {
            RewardPointTransaction::create([
                'customer_id' => $customer->id,
                'order_id' => $order->id,
                'type' => 'spent',
                'points' => $pointsRedeemed,
                'description' => 'Redeemed on Order #' . $order->order_number,
            ]);
        }

        Cart::where($identifier)->delete();

        return redirect('/order-success/' . $order->id);
    }
}
