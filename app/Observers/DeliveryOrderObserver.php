<?php

namespace App\Observers;

use App\Models\DeliveryOrder;
use App\Services\StockService;

class DeliveryOrderObserver
{
    /**
     * Handle the DeliveryOrder "updated" event.
     */
    public function updated(DeliveryOrder $deliveryOrder): void
    {
        // Only process when status changes to 'confirmed' or 'delivered'
        if ($deliveryOrder->isDirty('status') && in_array($deliveryOrder->status, ['confirmed', 'delivered'])) {
            foreach ($deliveryOrder->items as $item) {
                StockService::decreaseStock(
                    productId: $item->product_id,
                    warehouseId: $deliveryOrder->warehouse_id,
                    quantity: $item->quantity_delivered,
                    unitCost: $item->unit_cost ?? 0,
                    warehouseLocationId: $item->warehouse_location_id ?? null,
                    referenceType: DeliveryOrder::class,
                    referenceId: $deliveryOrder->id,
                    description: "Delivery Order #{$deliveryOrder->delivery_number} confirmed"
                );

                // Update related SalesOrderItem delivered_quantity
                if ($item->sales_order_item_id) {
                    $soItem = $item->salesOrderItem;
                    if ($soItem) {
                        $soItem->increment('delivered_quantity', $item->quantity_delivered);
                    }
                }
            }
        }
    }
}
