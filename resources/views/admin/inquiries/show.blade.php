@extends('admin.layouts.admin')

@section('title', 'Inquiry from ' . $inquiry->name)
@section('page_title', 'Inquiry Details')

@section('content')
<div class="card" style="max-width:800px;">
    <div class="card-header">
        <h2 class="card-title">{{ $inquiry->subject ?: 'General Inquiry' }}</h2>
        <a href="{{ route('admin.inquiries.index') }}" class="btn btn-secondary btn-sm">&larr; Back to Inbox</a>
    </div>

    <!-- Sender Meta Box -->
    <div style="display:flex; justify-content:space-between; flex-wrap:wrap; gap:16px; padding:16px; background:rgba(255,255,255,0.02); border:1px solid var(--border-color); border-radius:var(--radius-md); margin-bottom:24px;">
        <div>
            <div style="font-size:0.75rem; text-transform:uppercase; color:var(--text-muted); font-weight:700;">From</div>
            <div style="font-size:1.05rem; font-weight:700; color:var(--text-primary); margin-top:2px;">{{ $inquiry->name }}</div>
            <a href="mailto:{{ $inquiry->email }}" style="font-size:0.85rem; color:var(--accent-blue); text-decoration:none;">{{ $inquiry->email }}</a>
        </div>

        <div>
            <div style="font-size:0.75rem; text-transform:uppercase; color:var(--text-muted); font-weight:700;">Received At</div>
            <div style="font-size:0.9rem; font-weight:600; color:var(--text-primary); margin-top:2px;">
                {{ $inquiry->created_at->format('d M Y, h:i A') }} ({{ $inquiry->created_at->diffForHumans() }})
            </div>
            <div style="font-size:0.75rem; color:var(--text-muted);">IP: {{ $inquiry->ip ?: 'Protected' }}</div>
        </div>

        <div>
            <div style="font-size:0.75rem; text-transform:uppercase; color:var(--text-muted); font-weight:700;">Status</div>
            <span class="badge {{ $inquiry->status === 'unread' ? 'badge-danger' : ($inquiry->status === 'replied' ? 'badge-success' : 'badge-info') }}" style="margin-top:4px;">
                {{ $inquiry->status }}
            </span>
        </div>
    </div>

    <!-- Message Content Body -->
    <div style="padding:20px; background:rgba(11,17,32,0.8); border:1px solid var(--border-color); border-radius:var(--radius-md); font-size:0.95rem; line-height:1.7; color:var(--text-primary); white-space:pre-wrap; margin-bottom:28px;">
{{ $inquiry->message }}
    </div>

    <!-- Action Bar -->
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px; border-top:1px solid var(--border-color); padding-top:20px;">
        <!-- Status Switchers -->
        <div style="display:flex; gap:8px;">
            <form action="{{ route('admin.inquiries.status', $inquiry->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="replied">
                <button type="submit" class="btn btn-secondary btn-sm" {{ $inquiry->status === 'replied' ? 'disabled' : '' }}>
                    Mark as Replied
                </button>
            </form>

            <form action="{{ route('admin.inquiries.status', $inquiry->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="archived">
                <button type="submit" class="btn btn-secondary btn-sm" {{ $inquiry->status === 'archived' ? 'disabled' : '' }}>
                    Archive
                </button>
            </form>
        </div>

        <!-- Mailto Reply Trigger -->
        @php
            $replySubject = urlencode('Re: ' . ($inquiry->subject ?: 'Your inquiry to Hasnain Al Ifan'));
            $replyBody = urlencode("\n\n---\nOn {$inquiry->created_at->format('M d, Y')}, {$inquiry->name} wrote:\n{$inquiry->message}");
            $mailtoLink = "mailto:{$inquiry->email}?subject={$replySubject}&body={$replyBody}";
        @endphp
        <div style="display:flex; gap:12px;">
            <a href="{{ $mailtoLink }}" class="btn btn-primary" target="_blank">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                <span>Reply via Email</span>
            </a>

            <form action="{{ route('admin.inquiries.destroy', $inquiry->id) }}" method="POST" onsubmit="return confirm('Delete this inquiry?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection
