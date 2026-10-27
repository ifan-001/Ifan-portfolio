@extends('admin.layouts.admin')

@section('title', 'Add Project')
@section('page_title', 'Create New Showcase Project')

@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Project Details</h2>
        <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary btn-sm">&larr; Back to Projects</a>
    </div>

    <form action="{{ route('admin.projects.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-grid-2">
            <div class="form-group">
                <label for="title" class="form-label">Project Title *</label>
                <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" required placeholder="DoctorQR — Doctor Practice Platform">
            </div>

            <div class="form-group">
                <label for="slug" class="form-label">URL Slug (Auto-generated if empty)</label>
                <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug') }}" placeholder="doctor-qr">
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label for="category" class="form-label">Category *</label>
                <select id="category" name="category" class="form-control" required>
                    <option value="professional">Professional Web Application / SaaS</option>
                    <option value="mobile">Mobile Application</option>
                    <option value="web">Full-Stack Web Project</option>
                    <option value="desktop">Desktop Application</option>
                    <option value="ai">AI &amp; Computer Vision</option>
                </select>
            </div>

            <div class="form-group">
                <label for="tagline" class="form-label">Category Tagline</label>
                <input type="text" id="tagline" name="tagline" class="form-control" value="{{ old('tagline') }}" placeholder="Professional SaaS / Laravel">
            </div>
        </div>

        <div class="form-group">
            <label for="summary" class="form-label">Brief Summary *</label>
            <textarea id="summary" name="summary" class="form-control" rows="3" required placeholder="A brief explanation of what the application achieves and its impact.">{{ old('summary') }}</textarea>
        </div>

        <div class="form-group">
            <label for="description" class="form-label">Detailed Highlights / Contribution (Separate paragraphs with double newline)</label>
            <textarea id="description" name="description" class="form-control" rows="5" placeholder="Key architecture decisions, workflows implemented, and engineering achievements.">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="technologies_text" class="form-label">Technologies (Comma-separated)</label>
            <input type="text" id="technologies_text" name="technologies_text" class="form-control" value="{{ old('technologies_text') }}" placeholder="PHP 8.2+, Laravel 12, MySQL, Agora RTC, Blade">
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label for="live_url" class="form-label">Live Deployment URL</label>
                <input type="url" id="live_url" name="live_url" class="form-control" value="{{ old('live_url') }}" placeholder="https://example.com">
            </div>

            <div class="form-group">
                <label for="github_url" class="form-label">GitHub Repository URL</label>
                <input type="url" id="github_url" name="github_url" class="form-control" value="{{ old('github_url') }}" placeholder="https://github.com/ifan-001/repo">
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label for="thumbnail_image" class="form-label">Screenshot / Thumbnail Image</label>
                <input type="file" id="thumbnail_image" name="thumbnail_image" accept="image/*" class="form-control">
            </div>

            <div class="form-group">
                <label for="order" class="form-label">Display Order</label>
                <input type="number" id="order" name="order" class="form-control" value="{{ old('order', 0) }}">
            </div>
        </div>

        <div class="form-group">
            <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                <input type="checkbox" name="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} style="accent-color:var(--accent-blue);">
                <span style="font-weight:600;">Flagship Project (Displays prominently in public Projects section)</span>
            </label>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:24px;">
            <a href="{{ route('admin.projects.index') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Project</button>
        </div>
    </form>
</div>
@endsection
