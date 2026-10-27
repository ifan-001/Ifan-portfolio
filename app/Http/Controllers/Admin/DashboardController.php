<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnalyticsEvent;
use App\Models\BlogPost;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\ChatStatus;
use App\Models\ContactMessage;
use App\Models\Project;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Display the Admin & Mobile PWA Dashboard.
     */
    public function index()
    {
        // 1. Core KPIs
        $totalViews = AnalyticsEvent::where('event_type', 'page_view')->count();
        $uniqueVisitors = AnalyticsEvent::where('event_type', 'page_view')
            ->distinct('ip_hash')
            ->count('ip_hash');
        
        $unreadInquiries = ContactMessage::where('status', 'unread')->count();
        $unreadChats = ChatMessage::where('sender', 'visitor')
            ->where('is_read', false)
            ->count();

        $projectCount = Project::count();
        $skillCount = Skill::count();
        $blogCount = BlogPost::where('status', 'published')->count();

        // 2. Chat Availability Status
        $chatStatus = ChatStatus::current();

        // 3. Recent Inquiries
        $recentInquiries = ContactMessage::orderByDesc('created_at')->take(5)->get();

        // 4. Recent Chat Conversations
        $recentChats = ChatConversation::with(['messages' => function ($q) {
            $q->latest()->limit(1);
        }])->orderByDesc('last_message_at')->take(5)->get();

        // 5. Views in last 7 days for mini chart
        $viewsLast7Days = AnalyticsEvent::where('event_type', 'page_view')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('count', 'date')
            ->toArray();

        // 6. Device breakdown
        $deviceStats = AnalyticsEvent::where('event_type', 'page_view')
            ->select('device_type', DB::raw('count(*) as count'))
            ->groupBy('device_type')
            ->pluck('count', 'device_type')
            ->toArray();

        return view('admin.dashboard.index', compact(
            'totalViews',
            'uniqueVisitors',
            'unreadInquiries',
            'unreadChats',
            'projectCount',
            'skillCount',
            'blogCount',
            'chatStatus',
            'recentInquiries',
            'recentChats',
            'viewsLast7Days',
            'deviceStats'
        ));
    }

    /**
     * Toggle availability status directly from dashboard / PWA header.
     */
    public function toggleStatus(Request $request)
    {
        $status = ChatStatus::current();
        
        if ($request->has('is_online')) {
            $isOnline = filter_var($request->input('is_online'), FILTER_VALIDATE_BOOLEAN);
        } else {
            $isOnline = !$status->is_online;
        }

        $status->is_online = $isOnline;
        $status->status_text = $isOnline ? 'Active Now' : 'Offline';
        $status->last_active_at = now();
        $status->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'is_online' => $status->is_online,
                'status_text' => $status->status_text,
            ]);
        }

        return redirect()->back()->with('status', 'Availability updated to ' . $status->status_text);
    }
}
