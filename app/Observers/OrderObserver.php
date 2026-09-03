<?php

namespace App\Observers;

use App\Models\Order;

class OrderObserver
{
    public function updated(Order $order): void
    {
        if (!$order->isDirty('order_status')) {
            return;
        }

        if ($order->order_status === 'delivered' && $order->customer) {
            $order->customer->awardPointsForOrder($order);
        }

        if ($order->order_status === 'cancelled' && $order->customer) {
            $order->customer->refundPointsForCancelledOrder($order);
        }
    }
}
