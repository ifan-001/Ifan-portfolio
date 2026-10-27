<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'proficiency' => 'integer',
        'is_featured' => 'boolean',
        'order' => 'integer',
    ];
}
