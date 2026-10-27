@extends('admin.layouts.admin')

@section('title', 'Edit Project')
@section('page_title', 'Edit Project: ' . $project->title)

@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Modify Project Details</h2>
        <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary btn-sm">&larr; Back to Projects</a>
    </div>

    <form action="{{ route('admin.projects.update', $project->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-grid-2">
            <div class="form-group">
                <label for="title" class="form-label">Project Title *</label>
                <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $project->title) }}" required>
            </div>

            <div class="form-group">
                <label for="slug" class="form-label">URL Slug *</label>
                <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug', $project->slug) }}" required>
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label for="category" class="form-label">Category *</label>
                <select id="category" name="category" class="form-control" required>
                    <option value="professional" {{ $project->category === 'professional' ? 'selected' : '' }}>Professional Web Application / SaaS</option>
                    <option value="mobile" {{ $project->category === 'mobile' ? 'selected' : '' }}>Mobile Application</option>
                    <option value="web" {{ $project->category === 'web' ? 'selected' : '' }}>Full-Stack Web Project</option>
                    <option value="desktop" {{ $project->category === 'desktop' ? 'selected' : '' }}>Desktop Application</option>
                    <option value="ai" {{ $project->category === 'ai' ? 'selected' : '' }}>AI &amp; Computer Vision</option>
                </select>
            </div>

            <div class="form-group">
                <label for="tagline" class="form-label">Category Tagline</label>
                <input type="text" id="tagline" name="tagline" class="form-control" value="{{ old('tagline', $project->tagline) }}">
            </div>
        </div>

        <div class="form-group">
            <label for="summary" class="form-label">Brief Summary *</label>
            <textarea id="summary" name="summary" class="form-control" rows="3" required>{{ old('summary', $project->summary) }}</textarea>
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Detailed Highlights / Contribution (Separate paragraphs with double newline)</label>
            <textarea id="description" name="description" class="form-control" rows="6">{{ old('description', $project->description) }}</textarea>
        </div>

        <div class="form-group">
            <label for="technologies_text" class="form-label">Technologies (Comma-separated)</label>
            <input type="text" id="technologies_text" name="technologies_text" class="form-control" value="{{ old('technologies_text', is_array($project->technologies) ? implode(', ', $project->technologies) : '') }}">
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label for="live_url" class="form-label">Live Deployment URL</label>
                <input type="url" id="live_url" name="live_url" class="form-control" value="{{ old('live_url', $project->live_url) }}">
            </div>

            <div class="form-group">
                <label for="github_url" class="form-label">GitHub Repository URL</label>
                <input type="url" id="github_url" name="github_url" class="form-control" value="{{ old('github_url', $project->github_url) }}">
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label for="thumbnail_image" class="form-label">Replace Thumbnail Image</label>
                <input type="file" id="thumbnail_image" name="thumbnail_image" accept="image/*" class="form-control">
                @if($project->thumbnail)
                    <div style="font-size:0.75rem; color:var(--text-muted); margin-top:4px;">Current: {{ $project->thumbnail }}</div>
                @endif
            </div>

            <div class="form-group">
                <label for="order" class="form-label">Display Order</label>
                <input type="number" id="order" name="order" class="form-control" value="{{ old('order', $project->order) }}">
            </div>
        </div>

        <div class="form-group">
            <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $project->is_featured) ? 'checked' : '' }} style="accent-color:var(--accent-blue);">
                <span style="font-weight:600;">Flagship Project (Displays prominently in public Projects section)</span>
            </label>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:24px;">
            <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </div>
    </form>
</div>
@endsection
