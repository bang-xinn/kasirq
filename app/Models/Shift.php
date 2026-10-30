<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Shift extends Model
{
    protected $fillable = [
        'user_id',
        'starting_cash',
        'expected_ending_cash',
        'actual_ending_cash',
        'start_time',
        'end_time',
        'status',
    ];

    protected $casts = [
        'starting_cash' => 'decimal:2',
        'expected_ending_cash' => 'decimal:2',
        'actual_ending_cash' => 'decimal:2',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
