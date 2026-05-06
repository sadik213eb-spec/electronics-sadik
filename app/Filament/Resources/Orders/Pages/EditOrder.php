<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Models\CustomerAddress;
use App\Models\Order_Item;
use App\Models\Product;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected array $originalQuantities = [];

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['items'] = Order_Item::where('order_id', $this->record->id)
            ->get()
            ->map(fn ($item) => [
                'product_id' => $item->product_id,
                'mrp_price' => $item->unit_price,
                'unit_price' => $item->sale_price ?? $item->unit_price,
                'quantity' => $item->quantity,
                'subtotal' => $item->subtotal,
                'available_stock' => optional($item->product)->stock ?? 0,
            ])
            ->toArray();

        // ✅ Pre-select the address dropdown if a matching address exists
        $order = $this->record;
        if ($order->customer_id) {
            $matchingAddress = CustomerAddress::where('customer_id', $order->customer_id)
                ->where('name', $order->shipping_name)
                ->where('mobile', $order->shipping_phone)
                ->first();

            if ($matchingAddress) {
                $data['selected_address_id'] = $matchingAddress->id;
            }
        }

        return $data;
    }

    protected function beforeSave(): void
    {
        $existingItems = Order_Item::where('order_id', $this->record->id)->get();
        foreach ($existingItems as $item) {
            $this->originalQuantities[$item->product_id] = $item->quantity;
        }
    }

    protected function afterSave(): void
    {
        $order = $this->record->fresh();
        $data = $this->data;
        $items = $data['items'] ?? [];

        // ✅ Delete old items and re-save
        Order_Item::where('order_id', $order->id)->delete();

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

            // ✅ Adjust stock
            $product = Product::find($item['product_id']);
            if ($product) {
                $original = $this->originalQuantities[$item['product_id']] ?? 0;
                $diff = intval($item['quantity'] ?? 1) - $original;
                $newStock = max(0, $product->stock - $diff);
                $product->update([
                    'stock' => $newStock,
                    'stock_status' => $newStock <= 0 ? 'out_of_stock' : 'in_stock',
                ]);
            }
        }
    }
}
