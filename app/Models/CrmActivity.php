<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class CrmActivity extends Model
{
    use HasFactory, LogsActivity;

    protected $table = 'crm_activities';

    protected $fillable = [
        'lead_id',
        'customer_id',
        'type',
        'subject',
        'description',
        'activity_date',
        'completed_at',
        'assigned_to',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'activity_date' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['type', 'subject', 'completed_at'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function lead(): BelongsTo
    {
        return $this->belongsTo(CrmLead::class, 'lead_id');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
