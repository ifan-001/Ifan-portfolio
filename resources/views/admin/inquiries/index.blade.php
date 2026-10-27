@extends('admin.layouts.admin')

@section('title', 'Inquiries')
@section('page_title', 'Contact Inquiries Inbox')

@section('content')
<div class="card">
    <div class="card-header" style="flex-wrap:wrap; gap:12px;">
        <div style="display:flex; gap:8px;">
            <a href="{{ route('admin.inquiries.index', ['status' => 'all']) }}" class="btn {{ $status === 'all' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
                All ({{ $counts['all'] }})
            </a>
            <a href="{{ route('admin.inquiries.index', ['status' => 'unread']) }}" class="btn {{ $status === 'unread' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
                Unread ({{ $counts['unread'] }})
            </a>
            <a href="{{ route('admin.inquiries.index', ['status' => 'replied']) }}" class="btn {{ $status === 'replied' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
                Replied ({{ $counts['replied'] }})
            </a>
            <a href="{{ route('admin.inquiries.index', ['status' => 'archived']) }}" class="btn {{ $status === 'archived' ? 'btn-primary' : 'btn-secondary' }} btn-sm">
                Archived ({{ $counts['archived'] }})
            </a>
        </div>
    </div>

    @if($inquiries->isEmpty())
        <p style="color:var(--text-muted); font-size:0.88rem; padding:20px 0;">No inquiries matching the selected filter.</p>
    @else
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Sender</th>
                        <th>Subject / Preview</th>
                        <th>Status</th>
                        <th>Received</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($inquiries as $inq)
                        <tr style="{{ $inq->status === 'unread' ? 'background:rgba(59, 130, 246, 0.04);' : '' }}">
                            <td>
                                <div style="font-weight:700; font-size:0.95rem;">
                                    <a href="{{ route('admin.inquiries.show', $inq->id) }}" style="color:var(--text-primary); text-decoration:none;">
                                        {{ $inq->name }}
                                    </a>
                                </div>
                                <div style="font-size:0.78rem; color:var(--text-muted);">{{ $inq->email }}</div>
                            </td>
                            <td>
                                <div style="font-weight:600; font-size:0.88rem;">{{ $inq->subject ?: 'General Inquiry' }}</div>
                                <div style="font-size:0.8rem; color:var(--text-secondary);">{{ Str::limit($inq->message, 60) }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $inq->status === 'unread' ? 'badge-danger' : ($inq->status === 'replied' ? 'badge-success' : 'badge-info') }}">
                                    {{ $inq->status }}
                                </span>
                            </td>
                            <td style="font-size:0.8rem; color:var(--text-muted);">
                                {{ $inq->created_at->diffForHumans() }}
                            </td>
                            <td>
                                <div style="display:flex; gap:8px;">
                                    <a href="{{ route('admin.inquiries.show', $inq->id) }}" class="btn btn-secondary btn-sm">Read</a>
                                    <form action="{{ route('admin.inquiries.destroy', $inq->id) }}" method="POST" onsubmit="return confirm('Delete inquiry from {{ $inq->name }}?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">&times;</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top:20px;">
            {{ $inquiries->appends(['status' => $status])->links() }}
        </div>
    @endif
</div>
@endsection
