@extends('admin.layouts.admin')

@section('title', 'Live Chat Console')
@section('page_title', 'Live Visitor Messaging Console')

@section('content')
<div class="card" style="padding:0; overflow:hidden;">
    <div style="display:grid; grid-template-columns:300px 1fr; min-height:600px; max-height:calc(100vh - 160px);">
        <!-- Conversations Sidebar -->
        <div style="border-right:1px solid var(--border-color); background:rgba(15,23,42,0.6); display:flex; flex-direction:column;">
            <div style="padding:16px; border-bottom:1px solid var(--border-color); display:flex; align-items:center; justify-content:space-between;">
                <span style="font-weight:700; font-size:0.95rem;">Conversations</span>
                <span class="badge badge-info">{{ $conversations->count() }}</span>
            </div>

            <div style="flex:1; overflow-y:auto; padding:8px;">
                @if($conversations->isEmpty())
                    <p style="padding:20px 12px; font-size:0.85rem; color:var(--text-muted); text-align:center;">No visitor conversations yet.</p>
                @else
                    @foreach($conversations as $c)
                        @php
                            $isActive = $activeConversation && $activeConversation->id === $c->id;
                            $latestMsg = $c->messages->first();
                            $hasUnread = $c->messages()->where('sender', 'visitor')->where('is_read', false)->exists();
                        @endphp
                        <a href="{{ route('admin.chat.index', ['conversation_id' => $c->id]) }}" style="display:block; padding:12px; border-radius:var(--radius-md); text-decoration:none; margin-bottom:4px; background:{{ $isActive ? 'rgba(59, 130, 246, 0.15)' : 'transparent' }}; border:1px solid {{ $isActive ? 'var(--accent-blue)' : 'transparent' }}; transition:var(--transition);">
                            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
                                <span style="font-weight:700; font-size:0.88rem; color:{{ $isActive ? 'var(--accent-blue)' : 'var(--text-primary)' }};">
                                    {{ $c->visitor_name ?: 'Visitor #' . substr($c->session_id, 0, 6) }}
                                </span>
                                @if($hasUnread)
                                    <span style="width:8px; height:8px; border-radius:50%; background:#EF4444;"></span>
                                @endif
                            </div>
                            <div style="font-size:0.78rem; color:var(--text-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                {{ $latestMsg ? $latestMsg->message : 'Started chat' }}
                            </div>
                            <div style="font-size:0.7rem; color:var(--text-muted); margin-top:4px; text-align:right;">
                                {{ $c->last_message_at ? $c->last_message_at->diffForHumans() : '' }}
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Chat Conversation Window -->
        <div style="display:flex; flex-direction:column; background:var(--bg-card);">
            @if($activeConversation)
                <!-- Chat Window Header -->
                <div style="padding:14px 20px; border-bottom:1px solid var(--border-color); display:flex; justify-content:space-between; align-items:center; background:rgba(255,255,255,0.02);">
                    <div>
                        <div style="font-weight:700; font-size:1rem; color:var(--text-primary);">
                            {{ $activeConversation->visitor_name ?: 'Anonymous Visitor' }}
                        </div>
                        <div style="font-size:0.75rem; color:var(--text-muted);">
                            Session: {{ $activeConversation->session_id }} &middot;
                            {{ $activeConversation->visitor_email ?: 'No email provided' }}
                        </div>
                    </div>

                    <div style="display:flex; align-items:center; gap:8px;">
                        <span class="status-dot-sm {{ $status->is_online ? 'online' : 'offline' }}"></span>
                        <span style="font-size:0.8rem; color:var(--text-secondary);">
                            {{ $status->is_online ? 'You are Online' : 'You are Offline' }}
                        </span>
                    </div>
                </div>

                <!-- Messages Stream -->
                <div id="adminMessageStream" style="flex:1; overflow-y:auto; padding:20px; display:flex; flex-direction:column; gap:12px;">
                    @forelse($activeConversation->messages as $msg)
                        @php $isAdmin = $msg->sender === 'admin'; @endphp
                        <div style="align-self:{{ $isAdmin ? 'flex-end' : 'flex-start' }}; max-width:75%;">
                            <div style="padding:10px 16px; border-radius:14px; font-size:0.88rem; line-height:1.5; background:{{ $isAdmin ? 'var(--accent-blue)' : 'rgba(255,255,255,0.06)' }}; color:{{ $isAdmin ? '#ffffff' : 'var(--text-primary)' }}; border:1px solid {{ $isAdmin ? 'transparent' : 'var(--border-color)' }};">
                                {{ $msg->message }}
                            </div>
                            <div style="font-size:0.7rem; color:var(--text-muted); margin-top:3px; text-align:{{ $isAdmin ? 'right' : 'left' }};">
                                {{ $isAdmin ? 'You' : ($activeConversation->visitor_name ?: 'Visitor') }} &middot; {{ $msg->created_at->format('h:i A') }}
                            </div>
                        </div>
                    @empty
                        <div style="text-align:center; color:var(--text-muted); margin-top:40px; font-size:0.85rem;">
                            No messages exchanged yet in this session.
                        </div>
                    @endforelse
                </div>

                <!-- Reply Input Bar -->
                <div style="padding:16px 20px; border-top:1px solid var(--border-color); background:rgba(15,23,42,0.8);">
                    <form id="adminReplyForm" action="{{ route('admin.chat.reply') }}" method="POST" style="display:flex; gap:12px;">
                        @csrf
                        <input type="hidden" name="conversation_id" value="{{ $activeConversation->id }}">
                        <input type="text" id="adminReplyInput" name="message" class="form-control" required placeholder="Type your reply to visitor..." autocomplete="off">
                        <button type="submit" class="btn btn-primary" style="flex-shrink:0;">
                            <span>Send</span>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                        </button>
                    </form>
                </div>
            @else
                <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; height:100%; color:var(--text-muted); padding:40px;">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom:12px; opacity:0.5;"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                    <p>Select a conversation from the sidebar to inspect or reply.</p>
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
                // If message count changed, refresh stream smoothly
                const currentCount = stream.querySelectorAll('div[style*="max-width:75%"]').length;
                if (data.messages.length > currentCount) {
                    let html = '';
                    data.messages.forEach(msg => {
                        const isAdmin = msg.sender === 'admin';
                        html += `
                            <div style="align-self:${isAdmin ? 'flex-end' : 'flex-start'}; max-width:75%;">
                                <div style="padding:10px 16px; border-radius:14px; font-size:0.88rem; line-height:1.5; background:${isAdmin ? 'var(--accent-blue)' : 'rgba(255,255,255,0.06)'}; color:${isAdmin ? '#ffffff' : 'var(--text-primary)'}; border:1px solid ${isAdmin ? 'transparent' : 'var(--border-color)'};">
                                    ${escapeHtml(msg.message)}
                                </div>
                                <div style="font-size:0.7rem; color:var(--text-muted); margin-top:3px; text-align:${isAdmin ? 'right' : 'left'};">
                                    ${isAdmin ? 'You' : 'Visitor'} &middot; ${msg.time}
                                </div>
                            </div>
                        `;
                    });
                    stream.innerHTML = html;
                    stream.scrollTop = stream.scrollHeight;
                }
            }
        } catch (e) {
            // connection retry
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
