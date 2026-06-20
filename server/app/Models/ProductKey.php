<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class ProductKey extends Model
{
    protected $fillable = [
        'key',
        'duration_days',
        'status',
        'used_by_workshop_id',
        'used_at',
    ];

    protected $casts = [
        'used_at' => 'datetime',
    ];

    public function workshop(): BelongsTo
    {
        return $this->belongsTo(Workshop::class, 'used_by_workshop_id');
    }

    public function isUsed(): bool
    {
        return $this->status === 'used';
    }

    /**
     * Generate a unique, secure product key with a customized prefix.
     * Format: SUHAIM-XXXX-XXXX-XXXX-XXXX
     */
    public static function generateSecureKey(): string
    {
        do {
            $key = 'SUHAIM-' . 
                   Str::upper(Str::random(4)) . '-' . 
                   Str::upper(Str::random(4)) . '-' . 
                   Str::upper(Str::random(4)) . '-' . 
                   Str::upper(Str::random(4));
        } while (static::where('key', $key)->exists());

        return $key;
    }
}
