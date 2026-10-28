@extends('admin.layouts.admin')

@section('title', 'Chat Session - ' . ($conversation->visitor_name ?: 'Visitor #' . substr($conversation->session_id, -6)))

@section('content')
<div style="max-width:900px; margin:0 auto;">
    {{-- Header with prominent Back Button --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; flex-wrap:wrap; gap:12px;">
        <a href="{{ route('admin.inbox.index') }}" class="btn btn-secondary btn-sm" style="display:inline-flex; align-items:center; gap:6px; font-weight:600; padding:8px 14px; border-radius:8px;">
            <span>&larr;</span>
            <span>Back to Message List</span>
        </a>

        <div style="display:flex; align-items:center; gap:10px;">
            <span style="font-size:0.8rem; background:rgba(59,130,246,0.15); color:#3B82F6; padding:3px 10px; border-radius:12px; font-weight:600;">
                LIVE CHAT CONSOLE
            </span>
            <form action="{{ route('admin.inbox.chat.destroy', $conversation->id) }}" method="POST" onsubmit="return confirm('Permanently delete this chat conversation?');" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-secondary btn-sm" style="color:#EF4444;" title="Delete this conversation">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    <span>Delete</span>
                </button>
            </form>
        </div>
    </div>

    @if(session('status'))
        <div style="background:rgba(16,185,129,0.15); border:1px solid #10B981; color:#10B981; padding:10px 14px; border-radius:8px; margin-bottom:16px; font-size:0.85rem;">
            {{ session('status') }}
        </div>
    @endif

    {{-- Chat Box Card --}}
    <div style="background:var(--bg-secondary); border:1px solid var(--border-color); border-radius:12px; overflow:hidden; display:flex; flex-direction:column; height:calc(85vh - 120px); min-height:500px;">
        {{-- Chat Box Header --}}
        <div style="padding:14px 20px; border-bottom:1px solid var(--border-color); background:rgba(0,0,0,0.15); display:flex; align-items:center; justify-content:space-between; gap:12px;">
            <div style="display:flex; align-items:center; gap:12px;">
                <div style="width:40px; height:40px; border-radius:50%; background:linear-gradient(135deg, #1E3A8A, #3B82F6); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700;">
                    💬
                </div>
                <div>
                    <h2 style="font-size:1rem; font-weight:700; color:var(--text-primary); margin:0;">
                        {{ $conversation->visitor_name ?: ('Visitor #' . substr($conversation->session_id, -6)) }}
                    </h2>
                    <div style="font-size:0.75rem; color:var(--text-secondary); display:flex; gap:8px;">
                        <span>Session: {{ substr($conversation->session_id, 0, 16) }}...</span>
                        @if($conversation->visitor_email)
                            <span>&bull; {{ $conversation->visitor_email }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div style="display:flex; align-items:center; gap:6px;">
                <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#10B981;"></span>
                <span style="font-size:0.8rem; color:var(--text-secondary);">Active Session</span>
            </div>
        </div>

        {{-- Messages Scroll Area --}}
        <div id="chat-messages-container" style="flex:1; overflow-y:auto; padding:20px; display:flex; flex-direction:column; gap:12px;">
            @forelse($conversation->messages as $msg)
                @php $isAdmin = $msg->sender === 'admin'; @endphp
                <div style="display:flex; justify-content:{{ $isAdmin ? 'flex-end' : 'flex-start' }};">
                    <div style="max-width:75%; padding:10px 16px; border-radius:{{ $isAdmin ? '16px 16px 4px 16px' : '16px 16px 16px 4px' }}; background:{{ $isAdmin ? 'var(--accent-blue)' : 'rgba(255,255,255,0.06)' }}; color:{{ $isAdmin ? '#ffffff' : 'var(--text-primary)' }}; border:1px solid {{ $isAdmin ? 'transparent' : 'var(--border-color)' }}; box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                        <div style="font-size:0.7rem; margin-bottom:4px; opacity:0.8; font-weight:600; text-align:{{ $isAdmin ? 'right' : 'left' }};">
                            {{ $isAdmin ? 'You (Admin)' : ($conversation->visitor_name ?: 'Visitor') }} &bull; {{ $msg->created_at->format('g:i A') }}
                        </div>
                        <div style="font-size:0.9rem; line-height:1.45; word-break:break-word;">
                            {{ $msg->message }}
                        </div>
                    </div>
                </div>
            @empty
                <div style="text-align:center; padding:40px 20px; color:var(--text-secondary); margin:auto;">
                    No messages in this chat session yet.
                </div>
            @endforelse
        </div>

        {{-- Reply Bar --}}
        <div style="padding:14px 20px; border-top:1px solid var(--border-color); background:rgba(0,0,0,0.1);">
            <form id="chat-reply-form" method="POST" action="{{ route('admin.inbox.chat.reply') }}" style="display:flex; gap:10px; align-items:center;">
                @csrf
                <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                <input type="text" name="message" id="chat-reply-input" required autocomplete="off"
                       placeholder="Type your reply to this visitor... (Press Enter to send)"
                       style="flex:1; background:var(--bg-primary); border:1px solid var(--border-color); color:var(--text-primary); padding:10px 16px; border-radius:24px; font-size:0.9rem;">
                <button type="submit" class="btn btn-primary" style="border-radius:24px; padding:10px 20px; display:inline-flex; align-items:center; gap:6px; font-weight:600;">
                    <span>Send</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('chat-messages-container');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }

    const form = document.getElementById('chat-reply-form');
    const input = document.getElementById('chat-reply-input');

    if (form && input) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const text = input.value.trim();
            if (!text) return;

            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;

            const formData = new FormData(form);
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    // Append bubble locally
                    const bubble = document.createElement('div');
                    bubble.style.display = 'flex';
                    bubble.style.justifyContent = 'flex-end';
                    bubble.innerHTML = `
                        <div style="max-width:75%; padding:10px 16px; border-radius:16px 16px 4px 16px; background:var(--accent-blue); color:#ffffff; box-shadow:0 2px 4px rgba(0,0,0,0.1);">
                            <div style="font-size:0.7rem; margin-bottom:4px; opacity:0.8; font-weight:600; text-align:right;">
                                You (Admin) &bull; ${data.created_at || 'Just now'}
                            </div>
                            <div style="font-size:0.9rem; line-height:1.45; word-break:break-word;">
                                ${text}
                            </div>
                        </div>
                    `;
                    container.appendChild(bubble);
                    container.scrollTop = container.scrollHeight;
                    input.value = '';
                }
            })
            .catch(err => {
                console.error(err);
                form.submit(); // fallback to normal submit
            })
            .finally(() => {
                submitBtn.disabled = false;
                input.focus();
            });
        });
    }
});
</script>
@endsection
