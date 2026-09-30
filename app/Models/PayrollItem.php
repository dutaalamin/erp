<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PayrollItem extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'payroll_period_id',
        'employee_id',
        'basic_salary',
        'allowances',
        'deductions',
        'overtime_pay',
        'tax',
        'net_salary',
        'notes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'basic_salary' => 'decimal:2',
            'allowances' => 'decimal:2',
            'deductions' => 'decimal:2',
            'overtime_pay' => 'decimal:2',
            'tax' => 'decimal:2',
            'net_salary' => 'decimal:2',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['employee_id', 'net_salary', 'status'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function payrollPeriod(): BelongsTo
    {
        return $this->belongsTo(PayrollPeriod::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
