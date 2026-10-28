@extends('admin.layouts.admin')

@section('title', 'Inquiries Inbox')
@section('page_title', 'Contact Inquiries Inbox')

@section('content')
<div class="card" style="padding: 24px;">
    <!-- Modern Inbox Filter Bar -->
    <div class="inbox-filter-bar">
        <a href="{{ route('admin.inquiries.index', ['status' => 'all']) }}" class="inbox-filter-btn {{ $status === 'all' ? 'active' : '' }}">
            <span>All Messages</span>
            <span class="filter-count">{{ $counts['all'] }}</span>
        </a>
        <a href="{{ route('admin.inquiries.index', ['status' => 'unread']) }}" class="inbox-filter-btn {{ $status === 'unread' ? 'active' : '' }}">
            <span>Unread</span>
            <span class="filter-count" style="{{ $counts['unread'] > 0 ? 'background:#EF4444; color:#fff;' : '' }}">{{ $counts['unread'] }}</span>
        </a>
        <a href="{{ route('admin.inquiries.index', ['status' => 'replied']) }}" class="inbox-filter-btn {{ $status === 'replied' ? 'active' : '' }}">
            <span>Replied</span>
            <span class="filter-count">{{ $counts['replied'] }}</span>
        </a>
        <a href="{{ route('admin.inquiries.index', ['status' => 'archived']) }}" class="inbox-filter-btn {{ $status === 'archived' ? 'active' : '' }}">
            <span>Archived</span>
            <span class="filter-count">{{ $counts['archived'] }}</span>
        </a>
    </div>

    @if($inquiries->isEmpty())
        <div style="text-align:center; padding:50px 20px; color:var(--text-muted);">
            <div style="width:64px; height:64px; border-radius:50%; background:rgba(255,255,255,0.03); border:1px dashed var(--border-color); display:flex; align-items:center; justify-content:center; margin:0 auto 16px; color:var(--text-muted);">
                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            </div>
            <h3 style="font-size:1.05rem; font-weight:700; color:var(--text-primary); margin-bottom:6px;">No Inquiries Found</h3>
            <p style="font-size:0.85rem; max-width:400px; margin:0 auto;">
                There are no contact messages matching the <strong>"{{ ucfirst($status) }}"</strong> filter right now. New submissions will appear here instantly.
            </p>
        </div>
    @else
        <!-- Modern Eye-Catching Inbox Card List -->
        <div class="inbox-cards-list">
            @foreach($inquiries as $inq)
                @php
                    $initial = strtoupper(substr(trim($inq->name), 0, 1)) ?: 'V';
                    $isUnread = $inq->status === 'unread';
                @endphp
                <div class="inbox-item-card {{ $isUnread ? 'unread' : '' }}">
                    <div class="inbox-avatar-badge">
                        {{ $initial }}
                    </div>

                    <div class="inbox-card-content">
                        <div class="inbox-meta-row">
                            <span class="inbox-sender-name">{{ $inq->name }}</span>
                            <span class="inbox-sender-email">&lt;{{ $inq->email }}&gt;</span>
                            <span class="inbox-time-tag">{{ $inq->created_at->diffForHumans() }}</span>
                        </div>

                        <div class="inbox-subject-line">
                            {{ $inq->subject ?: 'General Portfolio Contact Inquiry' }}
                        </div>

                        <div class="inbox-excerpt-line">
                            {{ Str::limit($inq->message, 120) }}
                        </div>
                    </div>

                    <div class="inbox-actions-row">
                        <span class="badge {{ $inq->status === 'unread' ? 'badge-danger' : ($inq->status === 'replied' ? 'badge-success' : 'badge-info') }}">
                            {{ ucfirst($inq->status) }}
                        </span>

                        <a href="{{ route('admin.inquiries.show', $inq->id) }}" class="btn btn-secondary btn-sm" title="Read message">
                            <span>Open</span>
                            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14"></path><path d="m12 5 7 7-7 7"></path></svg>
                        </a>

                        <form action="{{ route('admin.inquiries.destroy', $inq->id) }}" method="POST" onsubmit="return confirm('Delete inquiry from {{ $inq->name }}?');" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete message" style="padding:6px 10px;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top:24px;">
            {{ $inquiries->appends(['status' => $status])->links() }}
        </div>
    @endif
</div>
@endsection
