<?php

namespace App\Services;

use App\Models\NumberSequence;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NumberSequenceService
{
    /**
     * Generate next number for a given type.
     * Format: PREFIX-YYYY-NNNN or PREFIX-YYYYMM-NNNN
     */
    public static function generate(string $type): string
    {
        return DB::transaction(function () use ($type) {
            $sequence = NumberSequence::lockForUpdate()->where('type', $type)->first();

            if (!$sequence) {
                throw new \Exception("Number sequence for type [{$type}] not found.");
            }

            // Check if reset is needed
            static::checkReset($sequence);

            $number = $sequence->next_number;
            $sequence->increment('next_number');

            // Build the formatted number
            $paddedNumber = str_pad($number, $sequence->pad_length, '0', STR_PAD_LEFT);
            $now = Carbon::now();

            $parts = [$sequence->prefix];

            if ($sequence->reset_period === 'yearly') {
                $parts[] = $now->format('Y');
            } elseif ($sequence->reset_period === 'monthly') {
                $parts[] = $now->format('Ym');
            }

            $parts[] = $paddedNumber;

            if ($sequence->suffix) {
                $parts[] = $sequence->suffix;
            }

            return implode('-', $parts);
        });
    }

    protected static function checkReset(NumberSequence $sequence): void
    {
        $now = Carbon::now();

        if ($sequence->reset_period === 'yearly') {
            if (!$sequence->last_reset_at || Carbon::parse($sequence->last_reset_at)->year < $now->year) {
                $sequence->update([
                    'next_number' => 1,
                    'last_reset_at' => $now->toDateString(),
                ]);
            }
        } elseif ($sequence->reset_period === 'monthly') {
            if (!$sequence->last_reset_at || Carbon::parse($sequence->last_reset_at)->format('Y-m') < $now->format('Y-m')) {
                $sequence->update([
                    'next_number' => 1,
                    'last_reset_at' => $now->toDateString(),
                ]);
            }
        }
    }
}
