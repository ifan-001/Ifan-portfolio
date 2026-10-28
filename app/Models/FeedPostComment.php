<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeedPostComment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_admin' => 'boolean',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(FeedPost::class, 'feed_post_id');
    }
}
