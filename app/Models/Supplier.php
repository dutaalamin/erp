<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Supplier extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'company_id',
        'code',
        'name',
        'legal_name',
        'tax_id',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'contact_person',
        'payment_term_days',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'payment_term_days' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['code', 'name', 'is_active', 'payment_term_days'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function purchaseInvoices(): HasMany
    {
        return $this->hasMany(PurchaseInvoice::class);
    }
}
