@extends('admin.layouts.admin')

@section('title', 'Projects')
@section('page_title', 'Portfolio Projects Showcase')

@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Manage Projects ({{ $projects->count() }})</h2>
        <a href="{{ route('admin.projects.create') }}" class="btn btn-primary btn-sm">+ New Project</a>
    </div>

    @if($projects->isEmpty())
        <p style="color:var(--text-muted); font-size:0.88rem; padding:16px 0;">No projects created yet.</p>
    @else
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Project</th>
                        <th>Category</th>
                        <th>Featured</th>
                        <th>Live Demo / Repo</th>
                        <th>Order</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($projects as $proj)
                        <tr>
                            <td>
                                <div style="font-weight:700; font-size:0.95rem;">{{ $proj->title }}</div>
                                <div style="font-size:0.75rem; color:var(--text-muted);">Slug: {{ $proj->slug }}</div>
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $proj->category }}</span>
                            </td>
                            <td>
                                @if($proj->is_featured)
                                    <span class="badge badge-success">Flagship</span>
                                @else
                                    <span style="font-size:0.8rem; color:var(--text-muted);">Standard</span>
                                @endif
                            </td>
                            <td>
                                <div style="display:flex; gap:8px;">
                                    @if($proj->live_url)
                                        <a href="{{ $proj->live_url }}" target="_blank" style="color:var(--accent-blue); font-size:0.8rem; text-decoration:none;">Demo &nearr;</a>
                                    @endif
                                    @if($proj->github_url)
                                        <a href="{{ $proj->github_url }}" target="_blank" style="color:var(--text-secondary); font-size:0.8rem; text-decoration:none;">GitHub &nearr;</a>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $proj->order }}</td>
                            <td>
                                <div style="display:flex; gap:8px;">
                                    <a href="{{ route('admin.projects.edit', $proj->id) }}" class="btn btn-secondary btn-sm">Edit</a>
                                    <form action="{{ route('admin.projects.destroy', $proj->id) }}" method="POST" onsubmit="return confirm('Delete project {{ $proj->title }}?');" style="display:inline;">
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
    @endif
</div>
@endsection
