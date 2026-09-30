<?php

namespace App\Observers;

use App\Models\StockAdjustment;
use App\Services\StockService;

class StockAdjustmentObserver
{
    /**
     * Handle the StockAdjustment "updated" event.
     */
    public function updated(StockAdjustment $stockAdjustment): void
    {
        // Only process when status changes to 'confirmed'
        if ($stockAdjustment->isDirty('status') && $stockAdjustment->status === 'confirmed') {
            foreach ($stockAdjustment->items as $item) {
                StockService::adjustStock(
                    productId: $item->product_id,
                    warehouseId: $stockAdjustment->warehouse_id,
                    newQuantity: $item->actual_quantity,
                    unitCost: $item->unit_cost ?? 0,
                    warehouseLocationId: $item->warehouse_location_id ?? null,
                    referenceType: StockAdjustment::class,
                    referenceId: $stockAdjustment->id,
                    description: "Stock Adjustment #{$stockAdjustment->adjustment_number} confirmed"
                );
            }
        }
    }
}
