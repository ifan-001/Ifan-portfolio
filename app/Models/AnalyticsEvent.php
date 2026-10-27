<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnalyticsEvent extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = ['id'];

    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Anonymously track a page view or interaction.
     */
    public static function track(string $eventType, ?string $pageUrl = null, ?string $target = null, ?string $referrer = null, ?string $ip = null, ?string $userAgent = null, array $metadata = []): self
    {
        $ipHash = $ip ? hash('sha256', $ip . date('Y-m-d') . config('app.key')) : null;
        
        $deviceType = 'desktop';
        if ($userAgent) {
            $ua = strtolower($userAgent);
            if (str_contains($ua, 'mobile') || str_contains($ua, 'android') || str_contains($ua, 'iphone')) {
                $deviceType = 'mobile';
            } elseif (str_contains($ua, 'tablet') || str_contains($ua, 'ipad')) {
                $deviceType = 'tablet';
            }
        }

        return static::create([
            'event_type' => $eventType,
            'page_url' => $pageUrl,
            'target' => $target,
            'referrer' => $referrer,
            'ip_hash' => $ipHash,
            'device_type' => $deviceType,
            'metadata' => empty($metadata) ? null : $metadata,
            'created_at' => now(),
        ]);
    }
}
