<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
        'ip',
        'is_replied',
    ];

    protected $casts = [
        'is_replied' => 'boolean',
    ];
}
