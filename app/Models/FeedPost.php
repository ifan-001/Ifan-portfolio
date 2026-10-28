<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedPost extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_pinned' => 'boolean',
        'likes_count' => 'integer',
        'published_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($post) {
            if (empty($post->published_at) && $post->status === 'published') {
                $post->published_at = now();
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderByDesc('is_pinned')
            ->orderByDesc('published_at');
    }

    public function getCategoryLabelAttribute(): string
    {
        return match ($this->category) {
            'industrial' => 'Industrial & Engineering',
            'project' => 'Project Update',
            'social' => 'Social & Community',
            'research' => 'Research & Tech',
            default => 'Professional Insight',
        };
    }

    public function getCategoryColorAttribute(): string
    {
        return match ($this->category) {
            'industrial' => '#F59E0B',
            'project' => '#10B981',
            'social' => '#EC4899',
            'research' => '#8B5CF6',
            default => '#3B82F6',
        };
    }
}
