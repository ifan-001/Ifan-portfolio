@extends('admin.layouts.admin')

@section('title', 'Overview')
@section('page_title', 'Portfolio Control Center')

@section('content')
<!-- 3-Pillar Master Architecture Hub -->
<div class="pillar-hub-grid" style="margin-bottom: 28px;">
    <!-- Pillar 1: Core Operations -->
    <div class="pillar-hub-card active">
        <div class="pillar-hub-top">
            <span class="pillar-hub-icon">⚡</span>
            <span class="badge badge-primary">Pillar 1 &middot; Active</span>
        </div>
        <h2 class="pillar-hub-title">Core Operations</h2>
        <p class="pillar-hub-desc">
            দৈনন্দিন ওয়েবসাইট পরিচালনা, লাইভ উপস্থিতি সুইচ, ভিজিটর সরাসরি চ্যাট কনসোল এবং যোগাযোগ ইনবক্স।
        </p>
        <div class="pillar-hub-tags">
            <a href="{{ route('admin.dashboard') }}" class="pillar-tag active">Dashboard Overview</a>
            <a href="{{ route('admin.chat.index') }}" class="pillar-tag">
                Live Chat Console @if($unreadChats > 0)<span class="tag-counter" style="background:#EF4444;">{{ $unreadChats }}</span>@endif
            </a>
            <a href="{{ route('admin.inquiries.index') }}" class="pillar-tag">
                Inquiries Inbox @if($unreadInquiries > 0)<span class="tag-counter">{{ $unreadInquiries }}</span>@endif
            </a>
        </div>
    </div>

    <!-- Pillar 2: Content Management (Live In-Place Visual CMS) -->
    <div class="pillar-hub-card featured">
        <div class="pillar-hub-top">
            <span class="pillar-hub-icon" style="background:rgba(59, 130, 246, 0.2); color:#3B82F6;">✦</span>
            <span class="badge badge-success">Pillar 2 &middot; In-Place CMS</span>
        </div>
        <h2 class="pillar-hub-title">Content Management</h2>
        <p class="pillar-hub-desc">
            সম্পূর্ণ পোর্টফোলিও ওয়েবসাইটটি সরাসরি খুলবে এবং প্রতিটি সেকশন (Profile, Bio, Skills, Projects, Experience, Blog) সরাসরি সাইট থেকেই এডিট করা যাবে।
        </p>
        <div style="margin-top:auto; padding-top:16px;">
            <a href="{{ route('admin.content') }}" class="btn btn-primary" style="width:100%; justify-content:center; gap:8px;">
                <span>Open Visual Portfolio Editor</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
            </a>
        </div>
    </div>

    <!-- Pillar 3: System & Insights -->
    <div class="pillar-hub-card">
        <div class="pillar-hub-top">
            <span class="pillar-hub-icon">⚙</span>
            <span class="badge" style="background:rgba(255,255,255,0.08); color:var(--text-secondary);">Pillar 3</span>
        </div>
        <h2 class="pillar-hub-title">System &amp; Insights</h2>
        <p class="pillar-hub-desc">
            ওয়েবসাইট ট্রাফিক অ্যানালিটিক্স, ভিজিটর পরিসংখ্যান, নিরাপত্তা, পাসওয়ার্ড পরিবর্তন এবং প্ল্যাটফর্ম সেটিংস।
        </p>
        <div class="pillar-hub-tags">
            <a href="{{ route('admin.analytics.index') }}" class="pillar-tag">Analytics &amp; Telemetry</a>
            <a href="{{ route('admin.settings.index') }}" class="pillar-tag">Platform Settings</a>
        </div>
    </div>
</div>

<!-- KPI Row -->
<div class="kpi-grid">
    <div class="kpi-card">
        <span class="kpi-label">Total Portfolio Views</span>
        <span class="kpi-value">{{ number_format($totalViews) }}</span>
        <span class="kpi-sub">Privacy-first anonymous telemetry</span>
    </div>

    <div class="kpi-card">
        <span class="kpi-label">Unique Visitors</span>
        <span class="kpi-value">{{ number_format($uniqueVisitors) }}</span>
        <span class="kpi-sub">Daily hashed visitor sessions</span>
    </div>

    <div class="kpi-card">
        <span class="kpi-label">Unread Inquiries</span>
        <span class="kpi-value" style="{{ $unreadInquiries > 0 ? 'color:#3B82F6;' : '' }}">{{ $unreadInquiries }}</span>
        <span class="kpi-sub"><a href="{{ route('admin.inquiries.index') }}" style="color:var(--text-secondary); text-decoration:none;">Open inbox &rarr;</a></span>
    </div>

    <div class="kpi-card">
        <span class="kpi-label">Unread Live Chats</span>
        <span class="kpi-value" style="{{ $unreadChats > 0 ? 'color:#EF4444;' : '' }}">{{ $unreadChats }}</span>
        <span class="kpi-sub"><a href="{{ route('admin.chat.index') }}" style="color:var(--text-secondary); text-decoration:none;">Open live console &rarr;</a></span>
    </div>
</div>

<!-- Availability & PWA Status Banner -->
<div class="card" style="margin-bottom:28px; background:linear-gradient(135deg, rgba(30, 41, 59, 0.7) 0%, rgba(15, 23, 42, 0.9) 100%);">
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px;">
        <div style="display:flex; align-items:center; gap:16px;">
            <div style="width:44px; height:44px; border-radius:50%; background:{{ $chatStatus->is_online ? 'rgba(16, 185, 129, 0.15)' : 'rgba(239, 68, 68, 0.15)' }}; display:flex; align-items:center; justify-content:center;">
                <span class="status-dot-sm {{ $chatStatus->is_online ? 'online' : 'offline' }}" style="width:14px; height:14px;"></span>
            </div>
            <div>
                <h2 style="font-size:1.1rem; font-weight:700;">Live Chat Status: <span style="color:{{ $chatStatus->is_online ? 'var(--status-online)' : 'var(--status-offline)' }};">{{ $chatStatus->is_online ? 'Online (Active Now)' : 'Offline' }}</span></h2>
                <p style="font-size:0.85rem; color:var(--text-secondary);">
                    {{ $chatStatus->is_online ? 'Visitors on your portfolio website see a green status dot and can chat with you live.' : 'Visitors see a red offline indicator and are guided to leave an inquiry.' }}
                </p>
            </div>
        </div>

        <form action="{{ route('admin.dashboard.toggle-status') }}" method="POST">
            @csrf
            <button type="submit" class="btn {{ $chatStatus->is_online ? 'btn-secondary' : 'btn-primary' }}">
                <span>Switch to {{ $chatStatus->is_online ? 'Offline' : 'Online' }}</span>
            </button>
        </form>
    </div>
</div>

<!-- Grid: Recent Inquiries & Recent Chats -->
<div class="form-grid-2" style="margin-bottom:28px;">
    <!-- Recent Inquiries -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recent Contact Inquiries</h3>
            <a href="{{ route('admin.inquiries.index') }}" class="btn btn-secondary btn-sm">View All</a>
        </div>

        @if($recentInquiries->isEmpty())
            <p style="color:var(--text-muted); font-size:0.88rem; padding:12px 0;">No inquiries received yet.</p>
        @else
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Sender</th>
                            <th>Subject</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentInquiries as $inq)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.inquiries.show', $inq->id) }}" style="color:var(--text-primary); font-weight:600; text-decoration:none;">
                                        {{ $inq->name }}
                                    </a>
                                    <div style="font-size:0.75rem; color:var(--text-muted);">{{ $inq->email }}</div>
                                </td>
                                <td>
                                    <span style="font-size:0.82rem;">{{ Str::limit($inq->subject ?: $inq->message, 30) }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $inq->status === 'unread' ? 'badge-danger' : ($inq->status === 'replied' ? 'badge-success' : 'badge-info') }}">
                                        {{ $inq->status }}
                                    </span>
                                </td>
                                <td style="font-size:0.78rem; color:var(--text-muted);">
                                    {{ $inq->created_at->diffForHumans() }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    <!-- Recent Live Chats -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Live Chat Conversations</h3>
            <a href="{{ route('admin.chat.index') }}" class="btn btn-secondary btn-sm">Open Console</a>
        </div>

        @if($recentChats->isEmpty())
            <p style="color:var(--text-muted); font-size:0.88rem; padding:12px 0;">No live chat conversations recorded yet.</p>
        @else
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Visitor</th>
                            <th>Latest Message</th>
                            <th>Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentChats as $chat)
                            @php $latest = $chat->messages->first(); @endphp
                            <tr>
                                <td>
                                    <span style="font-weight:600;">{{ $chat->visitor_name ?: 'Anonymous Visitor' }}</span>
                                    <div style="font-size:0.72rem; color:var(--text-muted);">#{{ substr($chat->session_id, 0, 8) }}</div>
                                </td>
                                <td>
                                    <span style="font-size:0.82rem; color:var(--text-secondary);">{{ Str::limit($latest?->message ?: 'No messages', 32) }}</span>
                                </td>
                                <td style="font-size:0.78rem; color:var(--text-muted);">
                                    {{ $chat->last_message_at ? $chat->last_message_at->diffForHumans() : 'N/A' }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.chat.index', ['conversation_id' => $chat->id]) }}" class="btn btn-primary btn-sm">Reply</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection

