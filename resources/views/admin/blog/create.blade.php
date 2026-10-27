@extends('admin.layouts.admin')

@section('title', 'Write Article')
@section('page_title', 'Create New Engineering Article')

@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Article Content &amp; Publication Settings</h2>
        <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary btn-sm">&larr; Back to Articles</a>
    </div>

    <form action="{{ route('admin.blog.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-grid-2">
            <div class="form-group">
                <label for="title" class="form-label">Article Title *</label>
                <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" required placeholder="e.g. Building DoctorQR: Engineering a Production Platform">
            </div>

            <div class="form-group">
                <label for="slug" class="form-label">URL Slug (Auto-generated if empty)</label>
                <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug') }}" placeholder="building-doctorqr-production-platform">
            </div>
        </div>

        <div class="form-group">
            <label for="excerpt" class="form-label">Short Excerpt * (Appears on blog directory and search results)</label>
            <textarea id="excerpt" name="excerpt" class="form-control" rows="2" placeholder="A concise 2-sentence summary of the article.">{{ old('excerpt') }}</textarea>
        </div>

        <div class="form-group">
            <label for="content" class="form-label">Article Body * (Markdown / HTML formatted)</label>
            <textarea id="content" name="content" class="form-control" rows="14" required style="font-family:monospace; line-height:1.6;" placeholder="## Introduction&#10;&#10;Write your technical insights, architectural walkthroughs, and code snippets here.">{{ old('content') }}</textarea>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label for="tags_text" class="form-label">Tags (Comma-separated)</label>
                <input type="text" id="tags_text" name="tags_text" class="form-control" value="{{ old('tags_text') }}" placeholder="Laravel, PHP, WebRTC, Architecture">
            </div>

            <div class="form-group">
                <label for="status" class="form-label">Publication Status *</label>
                <select id="status" name="status" class="form-control" required>
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Draft (Private)</option>
                    <option value="published" {{ old('status', 'published') === 'published' ? 'selected' : '' }}>Published (Live on Public Website)</option>
                </select>
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label for="cover_image" class="form-label">Cover Header Image</label>
                <input type="file" id="cover_image" name="cover_image" accept="image/*" class="form-control">
            </div>

            <div class="form-group">
                <label for="read_time" class="form-label">Estimated Read Time (Minutes - leave blank to auto-calculate)</label>
                <input type="number" id="read_time" name="read_time" class="form-control" value="{{ old('read_time') }}" min="1" max="60" placeholder="e.g. 5">
            </div>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:24px;">
            <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save &amp; Publish Article</button>
        </div>
    </form>
</div>
@endsection
