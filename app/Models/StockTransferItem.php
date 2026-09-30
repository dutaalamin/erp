<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StockTransferItem extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'stock_transfer_id',
        'product_id',
        'from_location_id',
        'to_location_id',
        'quantity',
        'received_quantity',
        'unit_cost',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'received_quantity' => 'decimal:2',
            'unit_cost' => 'decimal:2',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['product_id', 'quantity', 'received_quantity'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function stockTransfer(): BelongsTo
    {
        return $this->belongsTo(StockTransfer::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function fromLocation(): BelongsTo
    {
        return $this->belongsTo(WarehouseLocation::class, 'from_location_id');
    }

    public function toLocation(): BelongsTo
    {
        return $this->belongsTo(WarehouseLocation::class, 'to_location_id');
    }
}
