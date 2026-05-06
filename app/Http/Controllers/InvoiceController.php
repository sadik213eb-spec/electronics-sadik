<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Order_Item;

class InvoiceController extends Controller
{
    public function show(Order $order)
    {
        $order->load('customer');

        $items = Order_Item::with('product')
            ->where('order_id', $order->id)
            ->get();

        return view('invoices.order', compact('order', 'items'));
    }
}
