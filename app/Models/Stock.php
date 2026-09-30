<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Stock extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'warehouse_location_id',
        'quantity',
        'reserved_quantity',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'reserved_quantity' => 'decimal:2',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['quantity', 'reserved_quantity'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected function availableQuantity(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->quantity - $this->reserved_quantity,
        );
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function warehouseLocation(): BelongsTo
    {
        return $this->belongsTo(WarehouseLocation::class);
    }
}
