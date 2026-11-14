@extends('admin.layouts.admin')
@section('page_title', 'Inquiry')

@section('title', 'Inquiry from ' . $inquiry->name)

@section('content')
<div style="max-width:850px; margin:0 auto;">
    {{-- Header with prominent Back Button --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:12px;">
        <a href="{{ route('admin.inbox.index') }}" class="btn btn-secondary btn-sm" style="display:inline-flex; align-items:center; gap:6px; font-weight:600; padding:8px 14px; border-radius:8px;">
            <span>&larr;</span>
            <span>Back to Message List</span>
        </a>

        <div style="display:flex; align-items:center; gap:10px;">
            @php
                $statusColor = match($inquiry->status) {
                    'unread' => '#EF4444',
                    'replied' => '#10B981',
                    'archived' => '#6B7280',
                    default => '#3B82F6',
                };
            @endphp
            <span style="font-size:0.75rem; background:rgba(255,255,255,0.06); color:{{ $statusColor }}; border:1px solid {{ $statusColor }}; padding:3px 10px; border-radius:12px; font-weight:600;">
                STATUS: {{ strtoupper($inquiry->status) }}
            </span>

            <form action="{{ route('admin.inbox.inquiry.destroy', $inquiry->id) }}" method="POST" onsubmit="return confirm('Permanently delete this inquiry?');" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-secondary btn-sm" style="color:#EF4444;" title="Delete this inquiry">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    <span>Delete</span>
                </button>
            </form>
        </div>
    </div>

    @if(session('status'))
        <div style="background:rgba(16,185,129,0.15); border:1px solid #10B981; color:#10B981; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:0.9rem;">
            {{ session('status') }}
        </div>
    @endif

    {{-- Inquiry Card --}}
    <div style="background:var(--bg-secondary); border:1px solid var(--border-color); border-radius:12px; overflow:hidden; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);">
        {{-- Sender Info Header --}}
        <div style="padding:24px; border-bottom:1px solid var(--border-color); display:flex; gap:16px; align-items:flex-start; flex-wrap:wrap;">
            <div style="width:54px; height:54px; border-radius:50%; background:linear-gradient(135deg, #1E3A8A, #3B82F6); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:1.3rem; flex-shrink:0;">
                {{ strtoupper(substr($inquiry->name, 0, 1)) }}
            </div>

            <div style="flex:1; min-width:240px;">
                <h1 style="font-size:1.3rem; font-weight:700; color:var(--text-primary); margin:0 0 4px 0;">
                    {{ $inquiry->name }}
                </h1>
                <div style="display:flex; flex-wrap:wrap; gap:12px; font-size:0.875rem; color:var(--text-secondary);">
                    <a href="mailto:{{ $inquiry->email }}" style="color:var(--accent-blue); text-decoration:none; display:inline-flex; align-items:center; gap:4px;">
                        <span>✉</span>
                        <span>{{ $inquiry->email }}</span>
                    </a>
                    <span>&bull;</span>
                    <span>Received {{ $inquiry->created_at->format('M d, Y · g:i A') }} ({{ $inquiry->created_at->diffForHumans() }})</span>
                    @if($inquiry->ip)
                        <span>&bull;</span>
                        <span style="font-family:monospace; font-size:0.8rem;">IP: {{ $inquiry->ip }}</span>
                    @endif
                </div>
            </div>

            <div>
                <a href="mailto:{{ $inquiry->email }}?subject={{ urlencode('Re: ' . ($inquiry->subject ?: 'Your Portfolio Inquiry')) }}"
                   class="btn btn-primary" style="display:inline-flex; align-items:center; gap:6px; font-weight:600; padding:9px 16px;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    <span>Reply via Email</span>
                </a>
            </div>
        </div>

        {{-- Subject & Body --}}
        <div style="padding:28px 24px;">
            <div style="font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em; color:var(--text-secondary); margin-bottom:6px; font-weight:600;">
                SUBJECT
            </div>
            <div style="font-size:1.15rem; font-weight:600; color:var(--text-primary); margin-bottom:24px;">
                {{ $inquiry->subject ?: '(No Subject Provided)' }}
            </div>

            <div style="font-size:0.8rem; text-transform:uppercase; letter-spacing:0.05em; color:var(--text-secondary); margin-bottom:8px; font-weight:600;">
                MESSAGE BODY
            </div>
            <div style="font-size:1rem; line-height:1.7; color:var(--text-primary); background:rgba(0,0,0,0.15); border:1px solid var(--border-color); border-radius:8px; padding:20px; white-space:pre-wrap; font-family:inherit;">
{{ $inquiry->message }}
            </div>
        </div>

        {{-- Footer Actions: Status Changer --}}
        <div style="padding:16px 24px; border-top:1px solid var(--border-color); background:rgba(0,0,0,0.1); display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <span style="font-size:0.85rem; color:var(--text-secondary); font-weight:500;">
                Update Inquiry Status:
            </span>

            <div style="display:flex; gap:8px;">
                <form action="{{ route('admin.inbox.inquiry.status', $inquiry->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <input type="hidden" name="status" value="replied">
                    <button type="submit" class="btn btn-sm {{ $inquiry->status === 'replied' ? 'btn-primary' : 'btn-secondary' }}">
                        ✓ Mark Replied
                    </button>
                </form>

                <form action="{{ route('admin.inbox.inquiry.status', $inquiry->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <input type="hidden" name="status" value="read">
                    <button type="submit" class="btn btn-sm {{ $inquiry->status === 'read' ? 'btn-primary' : 'btn-secondary' }}">
                        Mark Read
                    </button>
                </form>

                <form action="{{ route('admin.inbox.inquiry.status', $inquiry->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <input type="hidden" name="status" value="unread">
                    <button type="submit" class="btn btn-sm {{ $inquiry->status === 'unread' ? 'btn-primary' : 'btn-secondary' }}">
                        Mark Unread
                    </button>
                </form>

                <form action="{{ route('admin.inbox.inquiry.status', $inquiry->id) }}" method="POST" style="display:inline;">
                    @csrf
                    <input type="hidden" name="status" value="archived">
                    <button type="submit" class="btn btn-sm {{ $inquiry->status === 'archived' ? 'btn-primary' : 'btn-secondary' }}">
                        Archive
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
