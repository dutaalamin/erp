<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class GoodsReceiptItem extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'goods_receipt_id',
        'purchase_order_item_id',
        'product_id',
        'warehouse_location_id',
        'quantity_received',
        'quantity_rejected',
        'unit_cost',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity_received' => 'decimal:2',
            'quantity_rejected' => 'decimal:2',
            'unit_cost' => 'decimal:2',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['product_id', 'quantity_received', 'quantity_rejected'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function goodsReceipt(): BelongsTo
    {
        return $this->belongsTo(GoodsReceipt::class);
    }

    public function purchaseOrderItem(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrderItem::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouseLocation(): BelongsTo
    {
        return $this->belongsTo(WarehouseLocation::class);
    }
}
