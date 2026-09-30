<?php

namespace App\Observers;

use App\Models\GoodsReceipt;
use App\Services\StockService;

class GoodsReceiptObserver
{
    /**
     * Handle the GoodsReceipt "updated" event.
     */
    public function updated(GoodsReceipt $goodsReceipt): void
    {
        // Only process when status changes to 'confirmed'
        if ($goodsReceipt->isDirty('status') && $goodsReceipt->status === 'confirmed') {
            foreach ($goodsReceipt->items as $item) {
                StockService::increaseStock(
                    productId: $item->product_id,
                    warehouseId: $goodsReceipt->warehouse_id,
                    quantity: $item->quantity_received,
                    unitCost: $item->unit_cost ?? 0,
                    warehouseLocationId: $item->warehouse_location_id ?? null,
                    referenceType: GoodsReceipt::class,
                    referenceId: $goodsReceipt->id,
                    description: "Goods Receipt #{$goodsReceipt->receipt_number} confirmed"
                );

                // Update related PurchaseOrderItem received_quantity
                if ($item->purchase_order_item_id) {
                    $poItem = $item->purchaseOrderItem;
                    if ($poItem) {
                        $poItem->increment('received_quantity', $item->quantity_received);
                    }
                }
            }
        }
    }
}
