<?php

namespace App\Models;

use App\Traits\HasAutoNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StockAdjustment extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, HasAutoNumber;

    protected $fillable = [
        'company_id',
        'warehouse_id',
        'adjustment_number',
        'adjustment_date',
        'type',
        'status',
        'notes',
        'adjusted_by',
        'approved_by',
    ];

    protected function casts(): array
    {
        return [
            'adjustment_date' => 'date',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['adjustment_number', 'type', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function adjustedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'adjusted_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(StockAdjustmentItem::class);
    }

    protected function getNumberField(): string
    {
        return 'adjustment_number';
    }

    protected function getNumberType(): string
    {
        return 'stock_adjustment';
    }
}
