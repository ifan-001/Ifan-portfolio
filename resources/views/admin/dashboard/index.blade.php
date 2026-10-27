@extends('admin.layouts.admin')

@section('title', 'Overview')
@section('page_title', 'Portfolio Control Center')

@section('content')
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

<!-- Quick CMS Links Grid -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Content Management Shortcuts</h3>
    </div>
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(180px, 1fr)); gap:16px;">
        <a href="{{ route('admin.profile.edit') }}" class="btn btn-secondary" style="justify-content:flex-start; padding:14px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
            <span>Edit Profile &amp; Bio</span>
        </a>
        <a href="{{ route('admin.resume.index') }}" class="btn btn-secondary" style="justify-content:flex-start; padding:14px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>
            <span>Replace CV PDF</span>
        </a>
        <a href="{{ route('admin.projects.create') }}" class="btn btn-secondary" style="justify-content:flex-start; padding:14px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon></svg>
            <span>+ Add New Project</span>
        </a>
        <a href="{{ route('admin.blog.create') }}" class="btn btn-secondary" style="justify-content:flex-start; padding:14px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"></path><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"></path></svg>
            <span>+ Write Blog Article</span>
        </a>
        <a href="{{ route('admin.skills.index') }}" class="btn btn-secondary" style="justify-content:flex-start; padding:14px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 18l6-6-6-6"></path><path d="M8 6l-6 6 6 6"></path></svg>
            <span>Manage Skills ({{ $skillCount }})</span>
        </a>
        <a href="{{ route('admin.analytics.index') }}" class="btn btn-secondary" style="justify-content:flex-start; padding:14px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
            <span>View Full Analytics</span>
        </a>
    </div>
</div>
@endsection
