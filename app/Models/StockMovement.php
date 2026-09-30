<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StockMovement extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'product_id',
        'warehouse_id',
        'warehouse_location_id',
        'reference_type',
        'reference_id',
        'type',
        'quantity',
        'before_quantity',
        'after_quantity',
        'unit_cost',
        'description',
        'moved_by',
        'moved_at',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:2',
            'before_quantity' => 'decimal:2',
            'after_quantity' => 'decimal:2',
            'unit_cost' => 'decimal:2',
            'moved_at' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['type', 'quantity', 'product_id', 'warehouse_id'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
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

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    public function movedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moved_by');
    }
}
