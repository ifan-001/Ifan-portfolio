<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnalyticsEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index()
    {
        // 1. Totals
        $totalViews = AnalyticsEvent::where('event_type', 'page_view')->count();
        $uniqueVisitors = AnalyticsEvent::where('event_type', 'page_view')
            ->distinct('ip_hash')
            ->count('ip_hash');
        $resumeDownloads = AnalyticsEvent::where('event_type', 'resume_download')->count();
        $projectClicks = AnalyticsEvent::where('event_type', 'project_click')->count();

        // 2. 14-Day View History
        $dailyViews = AnalyticsEvent::where('event_type', 'page_view')
            ->where('created_at', '>=', now()->subDays(13)->startOfDay())
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as views'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('views', 'date')
            ->toArray();

        // 3. Device Breakdown
        $devices = AnalyticsEvent::where('event_type', 'page_view')
            ->select('device_type', DB::raw('count(*) as count'))
            ->groupBy('device_type')
            ->pluck('count', 'device_type')
            ->toArray();

        // 4. Top Targets / Actions
        $topTargets = AnalyticsEvent::whereNotNull('target')
            ->select('target', 'event_type', DB::raw('count(*) as count'))
            ->groupBy('target', 'event_type')
            ->orderByDesc('count')
            ->take(10)
            ->get();

        // 5. Recent Activity Log (anonymized)
        $recentEvents = AnalyticsEvent::orderByDesc('created_at')->take(20)->get();

        return view('admin.analytics.index', compact(
            'totalViews',
            'uniqueVisitors',
            'resumeDownloads',
            'projectClicks',
            'dailyViews',
            'devices',
            'topTargets',
            'recentEvents'
        ));
    }
}
