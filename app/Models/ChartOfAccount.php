<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ChartOfAccount extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $table = 'chart_of_accounts';

    protected $fillable = [
        'parent_id',
        'code',
        'name',
        'type',
        'sub_type',
        'description',
        'normal_balance',
        'is_active',
        'is_locked',
        'level',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_locked' => 'boolean',
            'level' => 'integer',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['code', 'name', 'type', 'is_active', 'is_locked'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(ChartOfAccount::class, 'parent_id');
    }

    public function journalEntryLines(): HasMany
    {
        return $this->hasMany(JournalEntryLine::class, 'account_id');
    }
}
