<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatStatus extends Model
{
    protected $fillable = [
        'is_online',
        'status_text',
        'last_active_at',
    ];

    protected $casts = [
        'is_online' => 'boolean',
        'last_active_at' => 'datetime',
    ];

    public static function current(): self
    {
        return self::firstOrCreate(
            ['id' => 1],
            [
                'is_online' => true,
                'status_text' => 'Active Now',
                'last_active_at' => now(),
            ]
        );
    }
}
