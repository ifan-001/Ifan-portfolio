@extends('admin.layouts.admin')

@section('title', 'Edit Article')
@section('page_title', 'Edit: ' . $post->title)

@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Modify Article Content</h2>
        <div style="display:flex; gap:8px;">
            @if($post->status === 'published')
                <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="btn btn-secondary btn-sm">Preview Public Article &nearr;</a>
            @endif
            <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary btn-sm">&larr; Back to Articles</a>
        </div>
    </div>

    <form action="{{ route('admin.blog.update', $post->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-grid-2">
            <div class="form-group">
                <label for="title" class="form-label">Article Title *</label>
                <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $post->title) }}" required>
            </div>

            <div class="form-group">
                <label for="slug" class="form-label">URL Slug *</label>
                <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug', $post->slug) }}" required>
            </div>
        </div>

        <div class="form-group">
            <label for="excerpt" class="form-label">Short Excerpt *</label>
            <textarea id="excerpt" name="excerpt" class="form-control" rows="2" required>{{ old('excerpt', $post->excerpt) }}</textarea>
        </div>

        <div class="form-group">
            <label for="content" class="form-label">Article Body * (Markdown / HTML)</label>
            <textarea id="content" name="content" class="form-control" rows="16" required style="font-family:monospace; line-height:1.6;">{{ old('content', $post->content) }}</textarea>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label for="tags_text" class="form-label">Tags (Comma-separated)</label>
                <input type="text" id="tags_text" name="tags_text" class="form-control" value="{{ old('tags_text', is_array($post->tags) ? implode(', ', $post->tags) : '') }}">
            </div>

            <div class="form-group">
                <label for="status" class="form-label">Publication Status *</label>
                <select id="status" name="status" class="form-control" required>
                    <option value="draft" {{ old('status', $post->status) === 'draft' ? 'selected' : '' }}>Draft (Private)</option>
                    <option value="published" {{ old('status', $post->status) === 'published' ? 'selected' : '' }}>Published (Live on Public Website)</option>
                </select>
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label for="cover_image" class="form-label">Replace Cover Image</label>
                <input type="file" id="cover_image" name="cover_image" accept="image/*" class="form-control">
                @if($post->cover_image)
                    <div style="font-size:0.75rem; color:var(--text-muted); margin-top:4px;">Current: {{ $post->cover_image }}</div>
                @endif
            </div>

            <div class="form-group">
                <label for="read_time" class="form-label">Estimated Read Time (Minutes)</label>
                <input type="number" id="read_time" name="read_time" class="form-control" value="{{ old('read_time', $post->read_time) }}" min="1" max="60">
            </div>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:24px;">
            <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
    </form>
</div>
@endsection
