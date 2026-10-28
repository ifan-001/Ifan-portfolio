@extends('admin.layouts.admin')

@section('title', 'Unified Inbox')

@section('content')
<div class="content-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px; margin-bottom:20px;">
    <div>
        <h1 style="font-size:1.5rem; font-weight:700; color:var(--text-primary); margin:0 0 4px 0; display:flex; align-items:center; gap:8px;">
            <span>📬 Unified Inbox</span>
            @if(!empty($counts['unread']) && $counts['unread'] > 0)
                <span class="nav-badge" style="background:#EF4444; font-size:0.75rem; padding:2px 8px;">{{ $counts['unread'] }} Unread</span>
            @endif
        </h1>
        <p style="color:var(--text-secondary); font-size:0.875rem; margin:0;">
            All live visitor chats and portfolio contact inquiries consolidated in one place.
        </p>
    </div>

    <div style="display:flex; gap:10px; align-items:center;">
        <form method="GET" action="{{ route('admin.inbox.index') }}" style="display:flex; gap:6px;">
            <input type="text" name="q" value="{{ $search ?? '' }}" placeholder="Search messages, names, email..."
                   style="background:var(--bg-secondary); border:1px solid var(--border-color); color:var(--text-primary); padding:7px 12px; border-radius:6px; font-size:0.85rem; width:220px;">
            @if(!empty($search))
                <a href="{{ route('admin.inbox.index') }}" class="btn btn-secondary btn-sm" title="Clear search">✕</a>
            @endif
            <button type="submit" class="btn btn-primary btn-sm">Search</button>
        </form>
    </div>
</div>

@if(session('status'))
    <div style="background:rgba(16,185,129,0.15); border:1px solid #10B981; color:#10B981; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:0.9rem;">
        {{ session('status') }}
    </div>
@endif

{{-- Filter Tabs --}}
<div class="inbox-filter-bar" style="display:flex; gap:8px; margin-bottom:24px; flex-wrap:wrap;">
    <a href="{{ route('admin.inbox.index', ['filter' => 'all']) }}" 
       class="btn btn-sm {{ ($filter ?? 'all') === 'all' ? 'btn-primary' : 'btn-secondary' }}" 
       style="border-radius:20px; padding:6px 14px;">
        All Messages ({{ $counts['all'] }})
    </a>
    <a href="{{ route('admin.inbox.index', ['filter' => 'random']) }}" 
       class="btn btn-sm {{ ($filter ?? '') === 'random' ? 'btn-primary' : 'btn-secondary' }}" 
       style="border-radius:20px; padding:6px 14px;">
        ⚡ Live / Random Chats ({{ $counts['random'] }})
    </a>
    <a href="{{ route('admin.inbox.index', ['filter' => 'inquiries']) }}" 
       class="btn btn-sm {{ ($filter ?? '') === 'inquiries' ? 'btn-primary' : 'btn-secondary' }}" 
       style="border-radius:20px; padding:6px 14px;">
        📬 Inquiries &amp; Named ({{ $counts['inquiries'] }})
    </a>
    <a href="{{ route('admin.inbox.index', ['filter' => 'unread']) }}" 
       class="btn btn-sm {{ ($filter ?? '') === 'unread' ? 'btn-primary' : 'btn-secondary' }}" 
       style="border-radius:20px; padding:6px 14px;">
        🔴 Unread ({{ $counts['unread'] }})
    </a>
</div>

{{-- SECTION 1: TOP - Random & Live Visitor Chats --}}
@if(($filter ?? 'all') === 'all' || ($filter ?? '') === 'random' || ($filter ?? '') === 'unread')
    @php
        $filteredRandom = $randomChats;
        if (($filter ?? '') === 'unread') {
            $filteredRandom = $randomChats->filter(function($c) {
                return $c->messages->where('sender', 'visitor')->where('is_read', false)->count() > 0;
            });
        }
    @endphp

    @if($filteredRandom->isNotEmpty())
        <div style="margin-bottom:30px;">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px; border-bottom:1px solid var(--border-color); padding-bottom:8px;">
                <h2 style="font-size:1.05rem; font-weight:600; color:var(--text-primary); margin:0; display:flex; align-items:center; gap:8px;">
                    <span style="color:#3B82F6;">⚡</span>
                    <span>Live &amp; Random Portfolio Chats</span>
                    <span style="font-size:0.75rem; background:rgba(59,130,246,0.15); color:#3B82F6; padding:2px 8px; border-radius:12px; font-weight:500;">Top Section · Real-Time</span>
                </h2>
                <span style="font-size:0.8rem; color:var(--text-secondary);">{{ $filteredRandom->count() }} active sessions</span>
            </div>

            <div style="display:flex; flex-direction:column; gap:10px;">
                @foreach($filteredRandom as $chat)
                    @php
                        $lastMsg = $chat->messages->first();
                        $unreadCount = $chat->messages->where('sender', 'visitor')->where('is_read', false)->count();
                        $visitorLabel = $chat->visitor_name ?: ('Visitor #' . substr($chat->session_id, -6));
                    @endphp
                    <div class="inbox-card" style="background:var(--bg-secondary); border:1px solid {{ $unreadCount > 0 ? '#3B82F6' : 'var(--border-color)' }}; border-radius:10px; padding:14px 18px; display:flex; align-items:center; justify-content:space-between; gap:16px; transition:transform 0.15s, border-color 0.15s; cursor:pointer;"
                         onclick="window.location='{{ route('admin.inbox.index', ['conversation_id' => $chat->id]) }}'">
                        
                        <div style="display:flex; align-items:center; gap:14px; min-width:0; flex:1;">
                            {{-- Monogram Avatar --}}
                            <div style="width:42px; height:42px; border-radius:50%; background:linear-gradient(135deg, #1E3A8A, #3B82F6); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:0.95rem; flex-shrink:0; position:relative;">
                                💬
                                @if($unreadCount > 0)
                                    <span style="position:absolute; top:-2px; right:-2px; width:12px; height:12px; background:#EF4444; border:2px solid var(--bg-secondary); border-radius:50%;"></span>
                                @endif
                            </div>

                            <div style="min-width:0; flex:1;">
                                <div style="display:flex; align-items:center; gap:8px; margin-bottom:3px; flex-wrap:wrap;">
                                    <span style="font-weight:600; color:var(--text-primary); font-size:0.95rem;">{{ $visitorLabel }}</span>
                                    <span style="font-size:0.7rem; background:rgba(59,130,246,0.12); color:#3B82F6; padding:1px 6px; border-radius:4px; font-weight:600;">LIVE CHAT</span>
                                    @if($unreadCount > 0)
                                        <span style="font-size:0.7rem; background:#EF4444; color:#fff; padding:1px 6px; border-radius:4px; font-weight:600;">{{ $unreadCount }} NEW</span>
                                    @endif
                                    <span style="font-size:0.75rem; color:var(--text-secondary); margin-left:auto;">
                                        {{ $chat->last_message_at ? $chat->last_message_at->diffForHumans() : $chat->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <div style="font-size:0.85rem; color:var(--text-secondary); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                    {{ $lastMsg ? $lastMsg->message : 'Session started. No messages yet.' }}
                                </div>
                            </div>
                        </div>

                        <div style="display:flex; align-items:center; gap:8px; flex-shrink:0;" onclick="event.stopPropagation();">
                            <a href="{{ route('admin.inbox.index', ['conversation_id' => $chat->id]) }}" class="btn btn-primary btn-sm" style="display:inline-flex; align-items:center; gap:4px;">
                                <span>Open Chat</span>
                                <span>&rarr;</span>
                            </a>
                            <form action="{{ route('admin.inbox.chat.destroy', $chat->id) }}" method="POST" onsubmit="return confirm('Delete this chat session?');" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary btn-sm" style="color:#EF4444; padding:6px 10px;" title="Delete chat">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @elseif(($filter ?? '') === 'random')
        <div style="text-align:center; padding:40px 20px; background:var(--bg-secondary); border-radius:10px; margin-bottom:30px; border:1px dashed var(--border-color);">
            <p style="color:var(--text-secondary); margin:0;">No live or random visitor chat sessions found.</p>
        </div>
    @endif
@endif

{{-- SECTION 2: BOTTOM - Contact Inquiries & Named Direct Messages --}}
@if(($filter ?? 'all') === 'all' || ($filter ?? '') === 'inquiries' || ($filter ?? '') === 'unread')
    @php
        $filteredInquiries = $inquiries;
        if (($filter ?? '') === 'unread') {
            $filteredInquiries = $inquiries->where('status', 'unread');
        }
    @endphp

    <div>
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px; border-bottom:1px solid var(--border-color); padding-bottom:8px;">
            <h2 style="font-size:1.05rem; font-weight:600; color:var(--text-primary); margin:0; display:flex; align-items:center; gap:8px;">
                <span style="color:#10B981;">📬</span>
                <span>Contact Inquiries &amp; Named Messages</span>
                <span style="font-size:0.75rem; background:rgba(16,185,129,0.15); color:#10B981; padding:2px 8px; border-radius:12px; font-weight:500;">Direct Inquiries</span>
            </h2>
            <span style="font-size:0.8rem; color:var(--text-secondary);">{{ $filteredInquiries->count() }} messages</span>
        </div>

        @if($filteredInquiries->isNotEmpty() || $namedChats->isNotEmpty())
            <div style="display:flex; flex-direction:column; gap:10px;">
                {{-- Named Direct Chats --}}
                @foreach($namedChats as $chat)
                    @php
                        $lastMsg = $chat->messages->first();
                        $unreadCount = $chat->messages->where('sender', 'visitor')->where('is_read', false)->count();
                    @endphp
                    <div class="inbox-card" style="background:var(--bg-secondary); border:1px solid {{ $unreadCount > 0 ? '#10B981' : 'var(--border-color)' }}; border-radius:10px; padding:14px 18px; display:flex; align-items:center; justify-content:space-between; gap:16px; cursor:pointer;"
                         onclick="window.location='{{ route('admin.inbox.index', ['conversation_id' => $chat->id]) }}'">
                        <div style="display:flex; align-items:center; gap:14px; min-width:0; flex:1;">
                            <div style="width:42px; height:42px; border-radius:50%; background:linear-gradient(135deg, #047857, #10B981); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:0.95rem; flex-shrink:0;">
                                {{ strtoupper(substr($chat->visitor_name ?? 'U', 0, 1)) }}
                            </div>
                            <div style="min-width:0; flex:1;">
                                <div style="display:flex; align-items:center; gap:8px; margin-bottom:3px; flex-wrap:wrap;">
                                    <span style="font-weight:600; color:var(--text-primary); font-size:0.95rem;">{{ $chat->visitor_name }}</span>
                                    @if($chat->visitor_email)
                                        <span style="font-size:0.8rem; color:var(--text-secondary);">&bull; {{ $chat->visitor_email }}</span>
                                    @endif
                                    <span style="font-size:0.7rem; background:rgba(16,185,129,0.12); color:#10B981; padding:1px 6px; border-radius:4px; font-weight:600;">IDENTIFIED CHAT</span>
                                    <span style="font-size:0.75rem; color:var(--text-secondary); margin-left:auto;">
                                        {{ $chat->last_message_at ? $chat->last_message_at->diffForHumans() : $chat->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <div style="font-size:0.85rem; color:var(--text-secondary); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                    {{ $lastMsg ? $lastMsg->message : 'Direct conversation started.' }}
                                </div>
                            </div>
                        </div>

                        <div style="display:flex; align-items:center; gap:8px; flex-shrink:0;" onclick="event.stopPropagation();">
                            <a href="{{ route('admin.inbox.index', ['conversation_id' => $chat->id]) }}" class="btn btn-secondary btn-sm" style="display:inline-flex; align-items:center; gap:4px;">
                                <span>Open</span>
                                <span>&rarr;</span>
                            </a>
                        </div>
                    </div>
                @endforeach

                {{-- Contact Inquiries --}}
                @foreach($filteredInquiries as $inquiry)
                    @php
                        $isUnread = $inquiry->status === 'unread';
                        $statusColor = match($inquiry->status) {
                            'unread' => '#EF4444',
                            'replied' => '#10B981',
                            'archived' => '#6B7280',
                            default => '#3B82F6',
                        };
                    @endphp
                    <div class="inbox-card" style="background:var(--bg-secondary); border:1px solid {{ $isUnread ? 'var(--accent-blue)' : 'var(--border-color)' }}; border-radius:10px; padding:14px 18px; display:flex; align-items:center; justify-content:space-between; gap:16px; cursor:pointer;"
                         onclick="window.location='{{ route('admin.inbox.index', ['inquiry_id' => $inquiry->id]) }}'">
                        
                        <div style="display:flex; align-items:center; gap:14px; min-width:0; flex:1;">
                            <div style="width:42px; height:42px; border-radius:50%; background:linear-gradient(135deg, #1E3A8A, #3B82F6); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:0.95rem; flex-shrink:0;">
                                {{ strtoupper(substr($inquiry->name, 0, 1)) }}
                            </div>

                            <div style="min-width:0; flex:1;">
                                <div style="display:flex; align-items:center; gap:8px; margin-bottom:3px; flex-wrap:wrap;">
                                    <span style="font-weight:600; color:var(--text-primary); font-size:0.95rem;">{{ $inquiry->name }}</span>
                                    <span style="font-size:0.8rem; color:var(--text-secondary);">&bull; {{ $inquiry->email }}</span>
                                    <span style="font-size:0.7rem; background:rgba(255,255,255,0.06); color:{{ $statusColor }}; border:1px solid {{ $statusColor }}; padding:1px 6px; border-radius:4px; font-weight:600;">
                                        {{ strtoupper($inquiry->status) }}
                                    </span>
                                    <span style="font-size:0.75rem; color:var(--text-secondary); margin-left:auto;">
                                        {{ $inquiry->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <div style="font-size:0.85rem; color:var(--text-primary); font-weight:500; margin-bottom:2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                    {{ $inquiry->subject ?: 'General Portfolio Inquiry' }}
                                </div>
                                <div style="font-size:0.8rem; color:var(--text-secondary); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                    {{ $inquiry->message }}
                                </div>
                            </div>
                        </div>

                        <div style="display:flex; align-items:center; gap:8px; flex-shrink:0;" onclick="event.stopPropagation();">
                            <a href="{{ route('admin.inbox.index', ['inquiry_id' => $inquiry->id]) }}" class="btn btn-secondary btn-sm" style="display:inline-flex; align-items:center; gap:4px;">
                                <span>Open</span>
                                <span>&rarr;</span>
                            </a>
                            <form action="{{ route('admin.inbox.inquiry.destroy', $inquiry->id) }}" method="POST" onsubmit="return confirm('Delete this inquiry?');" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-secondary btn-sm" style="color:#EF4444; padding:6px 10px;" title="Delete inquiry">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="text-align:center; padding:40px 20px; background:var(--bg-secondary); border-radius:10px; border:1px dashed var(--border-color);">
                <p style="color:var(--text-secondary); margin:0;">No contact inquiries found matching this criteria.</p>
            </div>
        @endif
    </div>
@endif

<style>
.inbox-card:hover {
    transform: translateY(-1px);
    border-color: var(--accent-blue) !important;
}
</style>
@endsection
