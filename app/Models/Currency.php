<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'symbol',
        'exchange_rate',
        'is_base',
        'is_active',
    ];

    protected $casts = [
        'exchange_rate' => 'decimal:6',
        'is_base' => 'boolean',
        'is_active' => 'boolean',
    ];

    public static function getBase(): ?self
    {
        return static::where('is_base', true)->first();
    }

    public function convertTo(float $amount, Currency $targetCurrency): float
    {
        if ($this->code === $targetCurrency->code) {
            return $amount;
        }

        // Convert to base first, then to target
        $baseAmount = $amount / $this->exchange_rate;
        return $baseAmount * $targetCurrency->exchange_rate;
    }
}
