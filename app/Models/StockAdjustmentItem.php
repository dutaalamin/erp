<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StockAdjustmentItem extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'stock_adjustment_id',
        'product_id',
        'warehouse_location_id',
        'system_quantity',
        'actual_quantity',
        'difference_quantity',
        'unit_cost',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'system_quantity' => 'decimal:2',
            'actual_quantity' => 'decimal:2',
            'difference_quantity' => 'decimal:2',
            'unit_cost' => 'decimal:2',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['product_id', 'system_quantity', 'actual_quantity', 'difference_quantity'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function stockAdjustment(): BelongsTo
    {
        return $this->belongsTo(StockAdjustment::class);
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
