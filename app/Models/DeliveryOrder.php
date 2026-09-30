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

class DeliveryOrder extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, HasAutoNumber;

    protected $fillable = [
        'company_id',
        'sales_order_id',
        'warehouse_id',
        'do_number',
        'delivery_date',
        'shipping_address',
        'status',
        'notes',
        'delivered_by',
    ];

    protected function casts(): array
    {
        return [
            'delivery_date' => 'date',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['do_number', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function salesOrder(): BelongsTo
    {
        return $this->belongsTo(SalesOrder::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function deliveredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivered_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(DeliveryOrderItem::class);
    }

    protected function getNumberField(): string
    {
        return 'do_number';
    }

    protected function getNumberType(): string
    {
        return 'delivery_order';
    }
}
