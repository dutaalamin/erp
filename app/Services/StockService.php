<?php

namespace App\Services;

use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StockService
{
    /**
     * Increase stock (used by Goods Receipt confirmation)
     */
    public static function increaseStock(
        int $productId,
        int $warehouseId,
        float $quantity,
        float $unitCost = 0,
        ?int $warehouseLocationId = null,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?string $description = null
    ): void {
        DB::transaction(function () use ($productId, $warehouseId, $quantity, $unitCost, $warehouseLocationId, $referenceType, $referenceId, $description) {
            $stock = Stock::firstOrCreate(
                [
                    'product_id' => $productId,
                    'warehouse_id' => $warehouseId,
                    'warehouse_location_id' => $warehouseLocationId,
                ],
                ['quantity' => 0, 'reserved_quantity' => 0]
            );

            $beforeQty = $stock->quantity;
            $stock->increment('quantity', $quantity);

            StockMovement::create([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'warehouse_location_id' => $warehouseLocationId,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'type' => 'in',
                'quantity' => $quantity,
                'before_quantity' => $beforeQty,
                'after_quantity' => $beforeQty + $quantity,
                'unit_cost' => $unitCost,
                'description' => $description,
                'moved_by' => Auth::id(),
                'moved_at' => now(),
            ]);
        });
    }

    /**
     * Decrease stock (used by Delivery Order confirmation)
     */
    public static function decreaseStock(
        int $productId,
        int $warehouseId,
        float $quantity,
        float $unitCost = 0,
        ?int $warehouseLocationId = null,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?string $description = null
    ): void {
        DB::transaction(function () use ($productId, $warehouseId, $quantity, $unitCost, $warehouseLocationId, $referenceType, $referenceId, $description) {
            $stock = Stock::firstOrCreate(
                [
                    'product_id' => $productId,
                    'warehouse_id' => $warehouseId,
                    'warehouse_location_id' => $warehouseLocationId,
                ],
                ['quantity' => 0, 'reserved_quantity' => 0]
            );

            $beforeQty = $stock->quantity;
            $stock->decrement('quantity', $quantity);

            StockMovement::create([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'warehouse_location_id' => $warehouseLocationId,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'type' => 'out',
                'quantity' => $quantity,
                'before_quantity' => $beforeQty,
                'after_quantity' => $beforeQty - $quantity,
                'unit_cost' => $unitCost,
                'description' => $description,
                'moved_by' => Auth::id(),
                'moved_at' => now(),
            ]);
        });
    }

    /**
     * Adjust stock (used by Stock Adjustment confirmation)
     */
    public static function adjustStock(
        int $productId,
        int $warehouseId,
        float $newQuantity,
        float $unitCost = 0,
        ?int $warehouseLocationId = null,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?string $description = null
    ): void {
        DB::transaction(function () use ($productId, $warehouseId, $newQuantity, $unitCost, $warehouseLocationId, $referenceType, $referenceId, $description) {
            $stock = Stock::firstOrCreate(
                [
                    'product_id' => $productId,
                    'warehouse_id' => $warehouseId,
                    'warehouse_location_id' => $warehouseLocationId,
                ],
                ['quantity' => 0, 'reserved_quantity' => 0]
            );

            $beforeQty = $stock->quantity;
            $stock->update(['quantity' => $newQuantity]);

            StockMovement::create([
                'product_id' => $productId,
                'warehouse_id' => $warehouseId,
                'warehouse_location_id' => $warehouseLocationId,
                'reference_type' => $referenceType,
                'reference_id' => $referenceId,
                'type' => 'adjustment',
                'quantity' => abs($newQuantity - $beforeQty),
                'before_quantity' => $beforeQty,
                'after_quantity' => $newQuantity,
                'unit_cost' => $unitCost,
                'description' => $description,
                'moved_by' => Auth::id(),
                'moved_at' => now(),
            ]);
        });
    }
}
