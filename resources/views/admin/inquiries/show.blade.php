@extends('admin.layouts.admin')

@section('title', 'Inquiry from ' . $inquiry->name)
@section('page_title', 'Inquiry Details')

@section('content')
<div class="card" style="max-width:850px; margin: 0 auto; padding: 26px;">
    <!-- Header -->
    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px; border-bottom:1px solid var(--border-color); padding-bottom:16px; flex-wrap:wrap; gap:12px;">
        <div>
            <span style="font-size:0.75rem; text-transform:uppercase; letter-spacing:0.05em; color:var(--text-muted); font-weight:700;">Subject</span>
            <h2 style="font-size:1.25rem; font-weight:800; color:var(--text-primary); margin-top:2px;">
                {{ $inquiry->subject ?: 'General Portfolio Contact Inquiry' }}
            </h2>
        </div>
        <a href="{{ route('admin.inquiries.index') }}" class="btn btn-secondary btn-sm">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>
            <span>Back to Inbox</span>
        </a>
    </div>

    <!-- Sender Meta Card -->
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:16px; padding:16px 20px; background:rgba(255,255,255,0.02); border:1px solid var(--border-color); border-radius:var(--radius-md); margin-bottom:24px;">
        <div style="display:flex; align-items:center; gap:14px;">
            <div class="inbox-avatar-badge" style="width:48px; height:48px; font-size:1.15rem;">
                {{ strtoupper(substr(trim($inquiry->name), 0, 1)) ?: 'V' }}
            </div>
            <div>
                <div style="font-size:1.05rem; font-weight:700; color:var(--text-primary);">{{ $inquiry->name }}</div>
                <a href="mailto:{{ $inquiry->email }}" style="font-size:0.85rem; color:var(--accent-blue); text-decoration:none;">
                    {{ $inquiry->email }}
                </a>
            </div>
        </div>

        <div style="display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
            <div style="text-align:right;">
                <div style="font-size:0.72rem; color:var(--text-muted); text-transform:uppercase; font-weight:700;">Received</div>
                <div style="font-size:0.85rem; color:var(--text-secondary); margin-top:2px;">
                    {{ $inquiry->created_at->format('M d, Y · h:i A') }}
                </div>
            </div>

            <span class="badge {{ $inquiry->status === 'unread' ? 'badge-danger' : ($inquiry->status === 'replied' ? 'badge-success' : 'badge-info') }}">
                {{ ucfirst($inquiry->status) }}
            </span>
        </div>
    </div>

    <!-- Message Content Body -->
    <div style="padding:24px; background:rgba(11,17,32,0.85); border:1px solid var(--border-color); border-radius:var(--radius-md); font-size:0.95rem; line-height:1.75; color:var(--text-primary); white-space:pre-wrap; margin-bottom:28px; box-shadow:inset 0 2px 8px rgba(0,0,0,0.3);">
{{ $inquiry->message }}
    </div>

    <!-- Action Bar -->
    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:14px; border-top:1px solid var(--border-color); padding-top:20px;">
        <!-- Status Switchers -->
        <div style="display:flex; gap:8px;">
            <form action="{{ route('admin.inquiries.status', $inquiry->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="replied">
                <button type="submit" class="btn btn-secondary btn-sm" {{ $inquiry->status === 'replied' ? 'disabled' : '' }}>
                    ✓ Mark as Replied
                </button>
            </form>

            <form action="{{ route('admin.inquiries.status', $inquiry->id) }}" method="POST">
                @csrf
                <input type="hidden" name="status" value="archived">
                <button type="submit" class="btn btn-secondary btn-sm" {{ $inquiry->status === 'archived' ? 'disabled' : '' }}>
                    📁 Archive
                </button>
            </form>
        </div>

        <!-- Mailto Reply & Delete -->
        @php
            $replySubject = urlencode('Re: ' . ($inquiry->subject ?: 'Your inquiry to Hasnain Al Ifan'));
            $replyBody = urlencode("\n\n---\nOn {$inquiry->created_at->format('M d, Y')}, {$inquiry->name} wrote:\n{$inquiry->message}");
            $mailtoLink = "mailto:{$inquiry->email}?subject={$replySubject}&body={$replyBody}";
        @endphp
        <div style="display:flex; gap:10px;">
            <a href="{{ $mailtoLink }}" class="btn btn-primary btn-sm" target="_blank">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                <span>Reply via Email</span>
            </a>

            <form action="{{ route('admin.inquiries.destroy', $inquiry->id) }}" method="POST" onsubmit="return confirm('Permanently delete this inquiry from {{ $inquiry->name }}?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
            </form>
        </div>
    </div>
</div>
@endsection
