<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CrmLead extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'crm_leads';

    protected $fillable = [
        'company_id',
        'name',
        'email',
        'phone',
        'company_name',
        'source',
        'status',
        'assigned_to',
        'expected_revenue',
        'probability',
        'notes',
        'converted_customer_id',
    ];

    protected function casts(): array
    {
        return [
            'expected_revenue' => 'decimal:2',
            'probability' => 'decimal:2',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'status', 'assigned_to', 'expected_revenue', 'probability'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function convertedCustomer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'converted_customer_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(CrmActivity::class, 'lead_id');
    }
}
