<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class UnitOfMeasure extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'unit_of_measures';

    protected $fillable = [
        'code',
        'name',
        'symbol',
        'type',
        'base_unit_id',
        'conversion_factor',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'conversion_factor' => 'decimal:6',
            'is_active' => 'boolean',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['code', 'name', 'symbol', 'is_active'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function baseUnit(): BelongsTo
    {
        return $this->belongsTo(UnitOfMeasure::class, 'base_unit_id');
    }

    public function conversions(): HasMany
    {
        return $this->hasMany(UnitOfMeasure::class, 'base_unit_id');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'unit_of_measure_id');
    }
}
