@extends('admin.layouts.admin')
@section('page_title', 'Inbox & live chat')

@section('title', 'Unified Inbox')

@section('content')
<div class="inbox-wrapper">
    {{-- Top Header Box --}}
    <div class="inbox-header-box">
        <div>
            <h1 class="inbox-header-title">
                <span>📬 Unified Inbox</span>
                @if(!empty($counts['unread']) && $counts['unread'] > 0)
                    <span class="inbox-unread-chip">{{ $counts['unread'] }} Unread</span>
                @endif
            </h1>
            <p style="color:var(--text-secondary); font-size:0.875rem; margin:0;">
                Consolidated real-time visitor chats and direct portfolio contact inquiries.
            </p>
        </div>

        <form method="GET" action="{{ route('admin.inbox.index') }}" class="inbox-search-form">
            <span class="inbox-search-icon">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
            </span>
            <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Search messages, names, email..." class="inbox-search-input">
            @if(!empty($search))
                <a href="{{ route('admin.inbox.index') }}" class="btn btn-secondary btn-sm" style="border-radius:var(--radius-full); padding:7px 11px;" title="Clear search">✕</a>
            @endif
            <button type="submit" class="btn btn-primary btn-sm" style="border-radius:var(--radius-full); padding:7px 15px;">Search</button>
        </form>
    </div>

    @if(session('status'))
        <div style="background:rgba(16,185,129,0.15); border:1px solid #10B981; color:#10B981; padding:12px 18px; border-radius:10px; margin-bottom:20px; font-size:0.875rem; font-weight:600; display:flex; align-items:center; gap:8px;">
            <span>✓</span>
            <span>{{ session('status') }}</span>
        </div>
    @endif

    {{-- Filter Tabs Bar --}}
    <div class="inbox-tabs-bar">
        <a href="{{ route('admin.inbox.index', ['filter' => 'all']) }}" 
           class="inbox-tab-pill {{ ($filter ?? 'all') === 'all' ? 'active' : '' }}">
            <span>All Messages</span>
            <span class="tab-count">{{ $counts['all'] }}</span>
        </a>
        <a href="{{ route('admin.inbox.index', ['filter' => 'random']) }}" 
           class="inbox-tab-pill {{ ($filter ?? '') === 'random' ? 'active' : '' }}">
            <span>⚡ Anonymous Chats</span>
            <span class="tab-count">{{ $counts['random'] }}</span>
        </a>
        <a href="{{ route('admin.inbox.index', ['filter' => 'named']) }}" 
           class="inbox-tab-pill {{ ($filter ?? '') === 'named' ? 'active' : '' }}">
            <span>👤 Named Conversations</span>
            <span class="tab-count">{{ $counts['named'] }}</span>
        </a>
        <a href="{{ route('admin.inbox.index', ['filter' => 'inquiries']) }}" 
           class="inbox-tab-pill {{ ($filter ?? '') === 'inquiries' ? 'active' : '' }}">
            <span>📬 Contact Inquiries</span>
            <span class="tab-count">{{ $counts['inquiries'] }}</span>
        </a>
        <a href="{{ route('admin.inbox.index', ['filter' => 'unread']) }}" 
           class="inbox-tab-pill {{ ($filter ?? '') === 'unread' ? 'active' : '' }}">
            <span>🔴 Unread</span>
            <span class="tab-count">{{ $counts['unread'] }}</span>
        </a>
    </div>

    {{-- =====================================================================
         SECTION 1: TOP SECTION — ⚡ Live & Random Portfolio Chats
         (Always displayed at the very top for anonymous & quick chats)
         ===================================================================== --}}
    @if(($filter ?? 'all') === 'all' || ($filter ?? '') === 'random' || ($filter ?? '') === 'unread')
        @php
            $filteredRandom = $randomChats;
            if (($filter ?? '') === 'unread') {
                $filteredRandom = $randomChats->filter(function($c) {
                    return $c->messages->where('sender', 'visitor')->where('is_read', false)->count() > 0;
                });
            }
        @endphp

        <div class="inbox-random-section">
            <div class="inbox-section-head">
                <h2>
                    <span style="color:#3B82F6;">⚡</span>
                    <span>Live &amp; Random Portfolio Chats</span>
                    <span class="inbox-badge-top">Top Section &bull; Anonymous Visitors</span>
                </h2>
                <span class="inbox-badge-count">{{ $filteredRandom->count() }} active session{{ $filteredRandom->count() === 1 ? '' : 's' }}</span>
            </div>

            @if($filteredRandom->isNotEmpty())
                <div class="inbox-card-list">
                    @foreach($filteredRandom as $chat)
                        @php
                            $lastMsg = $chat->messages->first();
                            $unreadCount = $chat->messages->where('sender', 'visitor')->where('is_read', false)->count();
                            $displayTitle = 'Anonymous Visitor #' . substr($chat->session_id, -6);
                        @endphp
                        <div class="inbox-item-card {{ $unreadCount > 0 ? 'is-unread' : '' }}"
                             onclick="window.location='{{ route('admin.inbox.index', ['conversation_id' => $chat->id]) }}'">
                            
                            <div style="display:flex; align-items:center; gap:14px; min-width:0; flex:1;">
                                <div class="inbox-avatar-circle inbox-avatar-random">
                                    <span>⚡</span>
                                    @if($unreadCount > 0)
                                        <span class="inbox-unread-dot" title="{{ $unreadCount }} new messages"></span>
                                    @endif
                                </div>

                                <div style="min-width:0; flex:1;">
                                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px; flex-wrap:wrap;">
                                        <span class="inbox-meta-title">{{ $displayTitle }}</span>
                                        <span class="inbox-badge-top">QUICK CHAT</span>
                                        @if($unreadCount > 0)
                                            <span style="font-size:0.7rem; background:#EF4444; color:#fff; padding:1px 7px; border-radius:10px; font-weight:700;">{{ $unreadCount }} NEW</span>
                                        @endif
                                        <span class="inbox-meta-time">
                                            {{ $chat->last_message_at ? $chat->last_message_at->diffForHumans() : $chat->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <div class="inbox-meta-snippet">
                                        {{ $lastMsg ? $lastMsg->message : 'Session started. Waiting for message...' }}
                                    </div>
                                </div>
                            </div>

                            <div style="display:flex; align-items:center; gap:8px; flex-shrink:0;" onclick="event.stopPropagation();">
                                <a href="{{ route('admin.inbox.index', ['conversation_id' => $chat->id]) }}" class="inbox-btn-open">
                                    <span>Open Live Chat</span>
                                    <span>&rarr;</span>
                                </a>
                                <form action="{{ route('admin.inbox.chat.destroy', $chat->id) }}" method="POST" onsubmit="return confirm('Delete this anonymous chat session?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inbox-btn-delete" title="Delete conversation">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="inbox-empty-card">
                    <span class="inbox-empty-icon">💬</span>
                    <strong>No Anonymous Chats Right Now</strong>
                    <p>When portfolio visitors drop in and send quick live messages without providing a name or email, their real-time chat sessions appear right here at the very top.</p>
                </div>
            @endif
        </div>
    @endif

    {{-- =====================================================================
         SECTION 2: 👤 Identified Direct Conversations (User Provided Name/Gmail)
         ===================================================================== --}}
    @if(($filter ?? 'all') === 'all' || ($filter ?? '') === 'named' || ($filter ?? '') === 'unread')
        @php
            $filteredNamed = $namedChats;
            if (($filter ?? '') === 'unread') {
                $filteredNamed = $namedChats->filter(function($c) {
                    return $c->messages->where('sender', 'visitor')->where('is_read', false)->count() > 0;
                });
            }
        @endphp

        <div class="inbox-section-block">
            <div class="inbox-section-head">
                <h2>
                    <span style="color:#10B981;">👤</span>
                    <span>Identified Direct Conversations</span>
                    <span class="inbox-badge-named">Name &amp; Email Identified</span>
                </h2>
                <span class="inbox-badge-count">{{ $filteredNamed->count() }} conversation{{ $filteredNamed->count() === 1 ? '' : 's' }}</span>
            </div>

            @if($filteredNamed->isNotEmpty())
                <div class="inbox-card-list">
                    @foreach($filteredNamed as $chat)
                        @php
                            $lastMsg = $chat->messages->first();
                            $unreadCount = $chat->messages->where('sender', 'visitor')->where('is_read', false)->count();
                            $displayName = $chat->visitor_name ?: $chat->visitor_email;
                            $monogram = strtoupper(substr($displayName, 0, 1));
                        @endphp
                        <div class="inbox-item-card {{ $unreadCount > 0 ? 'is-unread' : '' }}"
                             onclick="window.location='{{ route('admin.inbox.index', ['conversation_id' => $chat->id]) }}'">
                            
                            <div style="display:flex; align-items:center; gap:14px; min-width:0; flex:1;">
                                <div class="inbox-avatar-circle inbox-avatar-named">
                                    <span>{{ $monogram }}</span>
                                    @if($unreadCount > 0)
                                        <span class="inbox-unread-dot" title="{{ $unreadCount }} new messages"></span>
                                    @endif
                                </div>

                                <div style="min-width:0; flex:1;">
                                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px; flex-wrap:wrap;">
                                        <span class="inbox-meta-title">{{ $displayName }}</span>
                                        @if($chat->visitor_email && $chat->visitor_name)
                                            <span class="inbox-meta-email">&bull; {{ $chat->visitor_email }}</span>
                                        @endif
                                        <span class="inbox-badge-named">IDENTIFIED USER</span>
                                        @if($unreadCount > 0)
                                            <span style="font-size:0.7rem; background:#EF4444; color:#fff; padding:1px 7px; border-radius:10px; font-weight:700;">{{ $unreadCount }} NEW</span>
                                        @endif
                                        <span class="inbox-meta-time">
                                            {{ $chat->last_message_at ? $chat->last_message_at->diffForHumans() : $chat->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <div class="inbox-meta-snippet">
                                        {{ $lastMsg ? $lastMsg->message : 'Direct conversation started.' }}
                                    </div>
                                </div>
                            </div>

                            <div style="display:flex; align-items:center; gap:8px; flex-shrink:0;" onclick="event.stopPropagation();">
                                <a href="{{ route('admin.inbox.index', ['conversation_id' => $chat->id]) }}" class="inbox-btn-open">
                                    <span>Open Conversation</span>
                                    <span>&rarr;</span>
                                </a>
                                <form action="{{ route('admin.inbox.chat.destroy', $chat->id) }}" method="POST" onsubmit="return confirm('Delete this conversation?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inbox-btn-delete" title="Delete conversation">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @elseif(($filter ?? '') === 'named')
                <div class="inbox-empty-card">
                    <span class="inbox-empty-icon">👤</span>
                    <strong>No Named Conversations Found</strong>
                    <p>When visitors provide their Name or Gmail address, their personal conversation box will appear here.</p>
                </div>
            @endif
        </div>
    @endif

    {{-- =====================================================================
         SECTION 3: 📬 Contact Inquiries & Named Messages
         ===================================================================== --}}
    @if(($filter ?? 'all') === 'all' || ($filter ?? '') === 'inquiries' || ($filter ?? '') === 'unread')
        @php
            $filteredInquiries = $inquiries;
            if (($filter ?? '') === 'unread') {
                $filteredInquiries = $inquiries->where('status', 'unread');
            }
        @endphp

        <div class="inbox-section-block">
            <div class="inbox-section-head">
                <h2>
                    <span style="color:#A855F7;">📬</span>
                    <span>Contact Inquiries &amp; Named Messages</span>
                    <span class="inbox-badge-inquiry">Portfolio Contact Form</span>
                </h2>
                <span class="inbox-badge-count">{{ $filteredInquiries->count() }} message{{ $filteredInquiries->count() === 1 ? '' : 's' }}</span>
            </div>

            @if($filteredInquiries->isNotEmpty())
                <div class="inbox-card-list">
                    @foreach($filteredInquiries as $inquiry)
                        @php
                            $isUnread = $inquiry->status === 'unread';
                            $statusColor = match($inquiry->status) {
                                'unread' => '#EF4444',
                                'replied' => '#10B981',
                                'archived' => '#6B7280',
                                default => '#3B82F6',
                            };
                            $monogram = strtoupper(substr($inquiry->name, 0, 1));
                        @endphp
                        <div class="inbox-item-card {{ $isUnread ? 'is-unread' : '' }}"
                             onclick="window.location='{{ route('admin.inbox.index', ['inquiry_id' => $inquiry->id]) }}'">
                            
                            <div style="display:flex; align-items:center; gap:14px; min-width:0; flex:1;">
                                <div class="inbox-avatar-circle inbox-avatar-inquiry">
                                    <span>{{ $monogram }}</span>
                                    @if($isUnread)
                                        <span class="inbox-unread-dot" title="Unread inquiry"></span>
                                    @endif
                                </div>

                                <div style="min-width:0; flex:1;">
                                    <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px; flex-wrap:wrap;">
                                        <span class="inbox-meta-title">{{ $inquiry->name }}</span>
                                        <span class="inbox-meta-email">&bull; {{ $inquiry->email }}</span>
                                        <span style="font-size:0.68rem; background:rgba(255,255,255,0.06); color:{{ $statusColor }}; border:1px solid {{ $statusColor }}; padding:1px 7px; border-radius:10px; font-weight:700;">
                                            {{ strtoupper($inquiry->status) }}
                                        </span>
                                        <span class="inbox-meta-time">
                                            {{ $inquiry->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <div style="font-size:0.85rem; color:var(--text-primary); font-weight:600; margin-bottom:2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                        {{ $inquiry->subject ?: 'General Portfolio Inquiry' }}
                                    </div>
                                    <div class="inbox-meta-snippet">
                                        {{ $inquiry->message }}
                                    </div>
                                </div>
                            </div>

                            <div style="display:flex; align-items:center; gap:8px; flex-shrink:0;" onclick="event.stopPropagation();">
                                <a href="{{ route('admin.inbox.index', ['inquiry_id' => $inquiry->id]) }}" class="inbox-btn-open">
                                    <span>View Inquiry</span>
                                    <span>&rarr;</span>
                                </a>
                                <form action="{{ route('admin.inbox.inquiry.destroy', $inquiry->id) }}" method="POST" onsubmit="return confirm('Delete this inquiry?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inbox-btn-delete" title="Delete inquiry">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @elseif(($filter ?? '') === 'inquiries')
                <div class="inbox-empty-card">
                    <span class="inbox-empty-icon">📬</span>
                    <strong>No Contact Inquiries Found</strong>
                    <p>Contact messages sent from the main portfolio contact form will be listed here.</p>
                </div>
            @endif
        </div>
    @endif
</div>

<script>
// Live inbox: when a new chat or inquiry arrives (or something is read) the list refreshes itself.
(function () {
    const wrap = document.querySelector('.inbox-wrapper');
    if (!wrap) return;
    const checkUrl = "{{ route('admin.notifications.check') }}";
    let signature = null;
    let busy = false;

    function refresh() {
        const search = wrap.querySelector('.inbox-search-input');
        if (busy || (search && document.activeElement === search)) return;
        busy = true;
        fetch(location.href, { credentials: 'same-origin', headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(r => r.text())
            .then(html => {
                const fresh = new DOMParser().parseFromString(html, 'text/html').querySelector('.inbox-wrapper');
                if (fresh) wrap.innerHTML = fresh.innerHTML;
            })
            .catch(() => {})
            .finally(() => { busy = false; });
    }

    function tick() {
        fetch(checkUrl, { credentials: 'same-origin', headers: { 'Accept': 'application/json' } })
            .then(r => r.json())
            .then(d => {
                if (signature !== null && d.signature !== signature) refresh();
                signature = d.signature;
            })
            .catch(() => {})
            .finally(() => { setTimeout(tick, document.hidden ? 9000 : 3500); });
    }
    setTimeout(tick, 1500);
})();
</script>
@endsection
