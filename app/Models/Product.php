<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Product extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'category_id',
        'code',
        'name',
        'description',
        'type',
        'unit_of_measure_id',
        'purchase_unit_of_measure_id',
        'sale_price',
        'purchase_price',
        'cost_price',
        'minimum_stock',
        'weight',
        'barcode',
        'internal_reference',
        'is_purchasable',
        'is_sellable',
        'is_active',
        'image_path',
    ];

    protected function casts(): array
    {
        return [
            'sale_price' => 'decimal:2',
            'purchase_price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'minimum_stock' => 'decimal:2',
            'weight' => 'decimal:3',
            'is_purchasable' => 'boolean',
            'is_sellable' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['code', 'name', 'type', 'sale_price', 'purchase_price', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function unitOfMeasure(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasure::class, 'unit_of_measure_id');
    }

    public function purchaseUnitOfMeasure(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasure::class, 'purchase_unit_of_measure_id');
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }
}
