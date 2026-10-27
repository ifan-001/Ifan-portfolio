<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResearchItem extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'technologies' => 'array',
        'order' => 'integer',
    ];
}
