@extends('admin.layouts.admin')

@section('title', 'Blog Management')
@section('page_title', 'Engineering Blog & Articles')

@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Published Articles &amp; Drafts</h2>
        <a href="{{ route('admin.blog.create') }}" class="btn btn-primary btn-sm">+ Write New Article</a>
    </div>

    @if($posts->isEmpty())
        <p style="color:var(--text-muted); font-size:0.88rem; padding:16px 0;">No blog posts created yet.</p>
    @else
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Article Title</th>
                        <th>Status</th>
                        <th>Read Time</th>
                        <th>Views</th>
                        <th>Published Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($posts as $post)
                        <tr>
                            <td>
                                <div style="font-weight:700; font-size:0.95rem;">{{ $post->title }}</div>
                                <div style="font-size:0.75rem; color:var(--text-muted);">/blog/{{ $post->slug }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $post->status === 'published' ? 'badge-success' : 'badge-warning' }}">
                                    {{ $post->status }}
                                </span>
                            </td>
                            <td style="font-size:0.85rem; color:var(--text-secondary);">
                                {{ $post->read_time }} min read
                            </td>
                            <td style="font-weight:700; font-size:0.9rem;">
                                {{ number_format($post->views_count) }}
                            </td>
                            <td style="font-size:0.8rem; color:var(--text-muted);">
                                {{ $post->published_at ? $post->published_at->format('M d, Y') : 'Draft' }}
                            </td>
                            <td>
                                <div style="display:flex; gap:8px;">
                                    @if($post->status === 'published')
                                        <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="btn btn-secondary btn-sm" title="View Public Post">View</a>
                                    @endif
                                    <a href="{{ route('admin.blog.edit', $post->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                                    <form action="{{ route('admin.blog.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Delete article {{ $post->title }}?');" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top:20px;">
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection
