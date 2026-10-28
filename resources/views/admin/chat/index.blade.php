@extends('admin.layouts.admin')

@section('title', 'Live Chat Console')
@section('page_title', 'Live Visitor Messaging Console')

@section('content')
<div class="chat-console-card">
    <div class="chat-console-grid">
        <!-- Conversations Sidebar (Hidden on mobile when conversation is active) -->
        <div class="chat-convo-sidebar" style="{{ $activeConversation && request()->has('conversation_id') ? 'display: none;' : '' }} @media (min-width: 901px) { display: flex !important; }">
            <div class="chat-convo-header">
                <div style="display:flex; align-items:center; gap:8px;">
                    <span style="font-weight:700; font-size:0.95rem; color:var(--text-primary);">Visitor Chats</span>
                    <span class="badge badge-info" style="font-size:0.7rem; padding:2px 7px;">{{ $conversations->count() }}</span>
                </div>

                <a href="{{ route('admin.chat.index') }}" class="btn btn-secondary btn-xs" title="Refresh conversation list">
                    ↻ Refresh
                </a>
            </div>

            <div class="chat-convo-scroll">
                @if($conversations->isEmpty())
                    <div style="padding:40px 16px; text-align:center; color:var(--text-muted);">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom:8px; opacity:0.5;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                        <p style="font-size:0.85rem; margin:0;">No live chats yet.</p>
                        <span style="font-size:0.75rem;">Visitor messages will appear here in real-time.</span>
                    </div>
                @else
                    @foreach($conversations as $c)
                        @php
                            $isActive = $activeConversation && $activeConversation->id === $c->id;
                            $latestMsg = $c->messages->first();
                            $hasUnread = $c->messages()->where('sender', 'visitor')->where('is_read', false)->exists();
                            $visitorName = $c->visitor_name ?: 'Visitor #' . substr($c->session_id, 0, 6);
                            $initial = strtoupper(substr($visitorName, 0, 1)) ?: 'V';
                        @endphp
                        <a href="{{ route('admin.chat.index', ['conversation_id' => $c->id]) }}" class="chat-convo-item {{ $isActive ? 'active' : '' }}">
                            <div class="chat-convo-avatar">
                                {{ $initial }}
                                @if($hasUnread)
                                    <span class="chat-unread-dot" title="New unread message"></span>
                                @endif
                            </div>

                            <div style="flex:1; min-width:0;">
                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:2px;">
                                    <span style="font-weight:700; font-size:0.88rem; color:{{ $isActive ? 'var(--accent-blue)' : 'var(--text-primary)' }}; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                        {{ $visitorName }}
                                    </span>
                                    <span style="font-size:0.68rem; color:var(--text-muted); flex-shrink:0;">
                                        {{ $c->last_message_at ? $c->last_message_at->diffForHumans(null, true) : '' }}
                                    </span>
                                </div>
                                <div style="font-size:0.78rem; color:var(--text-secondary); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                    {{ $latestMsg ? $latestMsg->message : 'Started conversation' }}
                                </div>
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Chat Main Pane -->
        <div class="chat-main-pane">
            @if($activeConversation)
                <!-- Chat Window Header -->
                <div class="chat-header-bar">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <a href="{{ route('admin.chat.index') }}" class="btn btn-secondary btn-xs" style="display:inline-flex;" title="Back to conversations list">
                            &larr; Back
                        </a>

                        <div class="chat-convo-avatar" style="width:36px; height:36px; font-size:0.85rem; background:var(--accent-blue); color:#fff;">
                            {{ strtoupper(substr($activeConversation->visitor_name ?: 'V', 0, 1)) }}
                        </div>

                        <div>
                            <div style="font-weight:700; font-size:0.95rem; color:var(--text-primary);">
                                {{ $activeConversation->visitor_name ?: 'Visitor #' . substr($activeConversation->session_id, 0, 6) }}
                            </div>
                            <div style="font-size:0.72rem; color:var(--text-muted);">
                                {{ $activeConversation->visitor_email ?: 'Anonymous Visitor' }} &middot; Session: {{ substr($activeConversation->session_id, 0, 8) }}
                            </div>
                        </div>
                    </div>

                    <div style="display:flex; align-items:center; gap:10px;">
                        <span class="status-pill" style="padding:4px 10px; font-size:0.75rem;">
                            <span class="status-dot-sm {{ $status->is_online ? 'online' : 'offline' }}"></span>
                            <span>{{ $status->is_online ? 'You are Live' : 'Offline' }}</span>
                        </span>
                    </div>
                </div>

                <!-- Messages Stream Box -->
                <div id="adminMessageStream" class="chat-stream-box">
                    @forelse($activeConversation->messages as $msg)
                        @php $isAdmin = $msg->sender === 'admin'; @endphp
                        <div class="chat-bubble-wrap {{ $isAdmin ? 'admin' : 'visitor' }}">
                            <div class="chat-bubble">
                                {{ $msg->message }}
                            </div>
                            <span class="chat-bubble-time">
                                {{ $isAdmin ? 'You' : ($activeConversation->visitor_name ?: 'Visitor') }} &middot; {{ $msg->created_at->format('h:i A') }}
                            </span>
                        </div>
                    @empty
                        <div style="text-align:center; color:var(--text-muted); margin:auto; font-size:0.85rem;">
                            No messages exchanged yet in this session.
                        </div>
                    @endforelse
                </div>

                <!-- Reply Input Bar -->
                <div class="chat-reply-bar">
                    <form id="adminReplyForm" action="{{ route('admin.chat.reply') }}" method="POST">
                        @csrf
                        <input type="hidden" name="conversation_id" value="{{ $activeConversation->id }}">
                        <div class="chat-reply-input-wrap">
                            <input type="text" id="adminReplyInput" name="message" class="chat-reply-input" required placeholder="Type reply to {{ $activeConversation->visitor_name ?: 'visitor' }}..." autocomplete="off" autofocus>
                            <button type="submit" class="chat-reply-send-btn" title="Send Reply">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                            </button>
                        </div>
                    </form>
                </div>
            @else
                <!-- Clean Empty State -->
                <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; height:100%; min-height:480px; color:var(--text-muted); padding:40px; text-align:center;">
                    <div style="width:68px; height:68px; border-radius:50%; background:rgba(59,130,246,0.08); border:1px solid rgba(59,130,246,0.2); display:flex; align-items:center; justify-content:center; color:var(--accent-blue); margin-bottom:16px;">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                    </div>
                    <h3 style="font-size:1.1rem; font-weight:700; color:var(--text-primary); margin-bottom:6px;">Select a Visitor Conversation</h3>
                    <p style="font-size:0.85rem; max-width:380px; margin:0 auto; line-height:1.5;">
                        Choose an active visitor thread from the sidebar to view chat history and reply in real time.
                    </p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($activeConversation)
<script>
    const stream = document.getElementById('adminMessageStream');
    if (stream) stream.scrollTop = stream.scrollHeight;

    // Real-time polling every 3 seconds for active conversation
    const activeConversationId = {{ $activeConversation->id }};
    const pollUrl = "{{ url('/admin/chat/poll') }}/" + activeConversationId;

    setInterval(async () => {
        try {
            const res = await fetch(pollUrl);
            const data = await res.json();
            if (data.status === 'success' && data.messages) {
                const currentCount = stream.querySelectorAll('.chat-bubble-wrap').length;
                if (data.messages.length > currentCount) {
                    let html = '';
                    data.messages.forEach(msg => {
                        const isAdmin = msg.sender === 'admin';
                        html += `
                            <div class="chat-bubble-wrap ${isAdmin ? 'admin' : 'visitor'}">
                                <div class="chat-bubble">
                                    ${escapeHtml(msg.message)}
                                </div>
                                <span class="chat-bubble-time">
                                    ${isAdmin ? 'You' : 'Visitor'} &middot; ${msg.time}
                                </span>
                            </div>
                        `;
                    });
                    stream.innerHTML = html;
                    stream.scrollTop = stream.scrollHeight;
                }
            }
        } catch (e) {
            // retry on next interval
        }
    }, 3000);

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
</script>
@endif
@endpush
