<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\Customer;
use App\Models\Order_Item;
use App\Models\Product;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    protected function beforeCreate(): void
    {
        $items = $this->data['items'] ?? [];

        foreach ($items as $item) {
            $product = Product::find($item['product_id'] ?? null);
            if (! $product) {
                continue;
            }

            if ($product->stock_status === 'out_of_stock' || $product->stock <= 0) {
                Notification::make()
                    ->title('Out of Stock')
                    ->body("\"{$product->name}\" is out of stock.")
                    ->danger()
                    ->persistent()
                    ->send();
                $this->halt();

                return;
            }

            if (intval($item['quantity'] ?? 1) > $product->stock) {
                Notification::make()
                    ->title('Insufficient Stock')
                    ->body("Only {$product->stock} units of \"{$product->name}\" available.")
                    ->danger()
                    ->persistent()
                    ->send();
                $this->halt();

                return;
            }
        }
    }

    protected function afterCreate(): void
    {
        $order = $this->record;
        $data = $this->data;
        $items = $data['items'] ?? [];

        // ✅ Sync shipping to customer table
        $this->syncShippingToCustomer($data);

        // ✅ Save order items
        foreach ($items as $item) {
            if (empty($item['product_id'])) {
                continue;
            }

            Order_Item::create([
                'order_id' => $order->id,
                'product_id' => $item['product_id'],
                'unit_price' => $item['mrp_price'] ?? $item['unit_price'] ?? 0,
                'sale_price' => $item['unit_price'] ?? 0,
                'quantity' => $item['quantity'] ?? 1,
                'subtotal' => $item['subtotal'] ?? 0,
            ]);

            // ✅ Deduct stock
            $product = Product::find($item['product_id']);
            if ($product) {
                $newStock = max(0, $product->stock - intval($item['quantity'] ?? 1));
                $product->update([
                    'stock' => $newStock,
                    'stock_status' => $newStock <= 0 ? 'out_of_stock' : 'in_stock',
                ]);
            }
        }
    }

    // ✅ Shared method to sync shipping to customer
    protected function syncShippingToCustomer(array $data): void
    {
        $customerId = $data['customer_id'] ?? $this->record->customer_id ?? null;

        if (! $customerId) {
            return;
        }

        Customer::where('id', $customerId)->update([
            'shipping_name' => $data['shipping_name'] ?? null,
            'shipping_mobile' => $data['shipping_phone'] ?? null,
            'shipping_city' => $data['shipping_city'] ?? null,
            'shipping_address' => $data['shipping_address'] ?? null,
        ]);
    }
}
