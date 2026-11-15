@extends('admin.layouts.admin')

@section('title', 'Overview')
@section('page_title', 'Portfolio Control Center')

@php
    $hour = (int) now()->format('G');
    $greet = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
    $days = collect(range(6, 0))->map(fn ($d) => now()->subDays($d)->toDateString());
    $maxViews = max(1, max($viewsLast7Days ?: [0]));
    $actions = [
        ['url' => route('admin.feed.index'), 'icon' => '✍️', 'label' => 'Write a post', 'sub' => 'Share an update'],
        ['url' => route('admin.projects.index'), 'icon' => '🗂️', 'label' => 'Projects', 'sub' => 'Screenshots & features'],
        ['url' => route('admin.site-content'), 'icon' => '🔤', 'label' => 'Site texts', 'sub' => 'Headings, SEO, links'],
        ['url' => route('admin.resume.index'), 'icon' => '📄', 'label' => 'Resume / CV', 'sub' => 'Upload a new PDF'],
        ['url' => route('admin.skills.index'), 'icon' => '🧩', 'label' => 'Skills', 'sub' => 'Add or remove'],
        ['url' => route('admin.settings.index'), 'icon' => '⚙️', 'label' => 'Settings', 'sub' => 'Sections & password'],
    ];
@endphp

@section('content')
<div class="dx-dash">
    <section class="dx-hello">
        <div>
            <p class="dx-date">{{ now()->format('l, j F Y') }}</p>
            <h2>{{ $greet }}, {{ \Illuminate\Support\Str::of(auth()->user()->name)->before(' ') }} 👋</h2>
            <p class="dx-sub">Here's what's happening on your portfolio.</p>
        </div>
        <div class="dx-hello-actions">
            <form action="{{ route('admin.dashboard.toggle-status') }}" method="POST">
                @csrf
                <button type="submit" class="dx-status {{ $chatStatus->is_online ? 'is-on' : '' }}" title="Visitors see this on your chat bubble">
                    <span class="dx-status-dot"></span>
                    <span>{{ $chatStatus->is_online ? 'You are online' : 'You are away' }}</span>
                    <em>{{ $chatStatus->is_online ? 'Go away' : 'Go online' }}</em>
                </button>
            </form>
            <a href="{{ route('admin.content') }}" class="btn btn-primary">✦ Open Visual Portfolio Editor</a>
        </div>
    </section>

    <section class="dx-kpis">
        <a href="{{ route('admin.inbox.index') }}" class="dx-kpi is-alert">
            <span class="dx-kpi-ic">💬</span>
            <strong>{{ $unreadChats }}</strong><span>Unread chats</span>
        </a>
        <a href="{{ route('admin.inbox.index', ['filter' => 'inquiries']) }}" class="dx-kpi">
            <span class="dx-kpi-ic">✉️</span>
            <strong>{{ $unreadInquiries }}</strong><span>New inquiries</span>
        </a>
        <a href="{{ route('admin.analytics.index') }}" class="dx-kpi">
            <span class="dx-kpi-ic">👁️</span>
            <strong>{{ number_format($totalViews) }}</strong><span>Page views</span>
        </a>
        <a href="{{ route('admin.analytics.index') }}" class="dx-kpi">
            <span class="dx-kpi-ic">🧑‍💻</span>
            <strong>{{ number_format($uniqueVisitors) }}</strong><span>Visitors</span>
        </a>
    </section>

    <section class="dx-section">
        <h3 class="dx-h">Quick actions</h3>
        <div class="dx-actions">
            @foreach($actions as $a)
                <a href="{{ $a['url'] }}" class="dx-action"><span>{{ $a['icon'] }}</span><strong>{{ $a['label'] }}</strong><small>{{ $a['sub'] }}</small></a>
            @endforeach
        </div>
    </section>

    <div class="dx-cols">
        <section class="card dx-panel">
            <div class="dx-panel-head"><h3 class="dx-h">Latest chats</h3><a href="{{ route('admin.inbox.index') }}">Open inbox →</a></div>
            <div class="table-responsive dx-list">
                @forelse($recentChats as $c)
                    @php $last = $c->messages->first(); @endphp
                    <a href="{{ route('admin.inbox.index', ['conversation_id' => $c->id]) }}" class="dx-row">
                        <span class="dx-avatar">{{ strtoupper(mb_substr($c->visitor_name ?: 'V', 0, 1)) }}</span>
                        <span class="dx-row-main"><b>{{ $c->visitor_name ?: 'Anonymous visitor' }}</b><small>{{ \Illuminate\Support\Str::limit($last->message ?? '—', 60) }}</small></span>
                        <time>{{ optional($c->last_message_at)->diffForHumans(null, true) }}</time>
                    </a>
                @empty
                    <p class="dx-empty">No chats yet.</p>
                @endforelse
            </div>
        </section>

        <section class="card dx-panel">
            <div class="dx-panel-head"><h3 class="dx-h">Latest inquiries</h3><a href="{{ route('admin.inbox.index', ['filter' => 'inquiries']) }}">See all →</a></div>
            <div class="table-responsive dx-list">
                @forelse($recentInquiries as $iq)
                    <a href="{{ route('admin.inbox.index', ['inquiry_id' => $iq->id]) }}" class="dx-row">
                        <span class="dx-avatar dx-avatar-mail">{{ strtoupper(mb_substr($iq->name, 0, 1)) }}</span>
                        <span class="dx-row-main"><b>{{ $iq->name }} @if($iq->status === 'unread')<i class="dx-new">New</i>@endif</b><small>{{ \Illuminate\Support\Str::limit($iq->subject ?: $iq->message, 60) }}</small></span>
                        <time>{{ $iq->created_at->diffForHumans(null, true) }}</time>
                    </a>
                @empty
                    <p class="dx-empty">No inquiries yet.</p>
                @endforelse
            </div>
        </section>
    </div>

    <div class="dx-cols">
        <section class="card dx-panel">
            <div class="dx-panel-head"><h3 class="dx-h">Views this week</h3><a href="{{ route('admin.analytics.index') }}">Analytics →</a></div>
            <div class="dx-chart" role="img" aria-label="Page views over the last 7 days">
                @foreach($days as $d)
                    @php $v = $viewsLast7Days[$d] ?? 0; @endphp
                    <div class="dx-bar"><span style="height: {{ max(4, round($v / $maxViews * 100)) }}%" title="{{ $v }} views"></span><em>{{ \Carbon\Carbon::parse($d)->format('D') }}</em></div>
                @endforeach
            </div>
            <div class="dx-mini-stats">
                <div><strong>{{ $projectCount }}</strong><span>Projects</span></div>
                <div><strong>{{ $skillCount }}</strong><span>Skills</span></div>
                <div><strong>{{ $blogCount }}</strong><span>Articles</span></div>
            </div>
        </section>

        <section class="card dx-panel web-app-hero-card">
            <div class="dx-app">
                <div class="dx-app-icon">IF</div>
                <div>
                    <h3 class="dx-h">{{ $appInfo['name'] ?? 'Hasnain Portfolio Manager' }}</h3>
                    <p class="dx-sub">Get a chime and vibration on your phone for every new message. Version {{ $appInfo['version'] ?? 'v1.0.0' }}.</p>
                </div>
            </div>
            <div class="dx-app-actions">
                <button type="button" class="btn btn-primary" onclick="openQrInstallModal()">📱 Install Web App on Phone</button>
                <button type="button" class="btn btn-secondary" onclick="openAppVersionModal()">App info</button>
            </div>
        </section>
    </div>
</div>
@endsection
