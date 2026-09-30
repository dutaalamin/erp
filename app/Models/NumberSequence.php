<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NumberSequence extends Model
{
    protected $fillable = [
        'type',
        'prefix',
        'suffix',
        'next_number',
        'pad_length',
        'reset_period',
        'last_reset_at',
    ];

    protected $casts = [
        'next_number' => 'integer',
        'pad_length' => 'integer',
        'last_reset_at' => 'date',
    ];
}
