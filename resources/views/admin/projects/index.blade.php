@extends('admin.layouts.admin')

@section('title', 'Projects')
@section('page_title', 'Projects')

@section('content')
<div class="ad-page-head">
    <div>
        <h2>Projects <span class="ad-count">{{ $projects->count() }}</span></h2>
        <p>Everything shown in the portfolio's Projects section and case studies. Edit text, screenshots, features and links.</p>
    </div>
    <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">+ New project</a>
</div>

@if($projects->isEmpty())
    <div class="ad-empty">No projects yet. Create your first one.</div>
@else
    <div class="ad-cards">
        @foreach($projects as $proj)
            @php $sc = $proj->showcase ?? []; @endphp
            <article class="ad-card" style="--acc: {{ $sc['accent'] ?? '#3B82F6' }}">
                <a href="{{ route('admin.projects.edit', $proj->id) }}" class="ad-card-media">
                    @if($proj->thumbnail)
                        <img src="{{ asset(ltrim($proj->thumbnail, '/')) }}" alt="" loading="lazy">
                    @else
                        <span class="ad-card-ph">{{ \Illuminate\Support\Str::of($proj->title)->substr(0, 2)->upper() }}</span>
                    @endif
                    @if($proj->is_featured)<span class="ad-pill">Featured</span>@endif
                </a>
                <div class="ad-card-body">
                    <div class="ad-card-meta"><span>{{ ucfirst($proj->category) }}</span>@if(!empty($sc['status']))<span>· {{ $sc['status'] }}</span>@endif</div>
                    <h3>{{ $proj->title }}</h3>
                    <div class="ad-card-stats">
                        <span title="Web screenshots">🖥 {{ count($proj->gallery ?? []) }}</span>
                        <span title="App screenshots">📱 {{ count($proj->mobile_gallery ?? []) }}</span>
                        <span title="Features">★ {{ count($sc['features'] ?? []) }}</span>
                        <span title="Display order">#{{ $proj->order }}</span>
                    </div>
                    <div class="ad-card-actions">
                        <a href="{{ route('admin.projects.edit', $proj->id) }}" class="btn btn-primary btn-sm">Edit</a>
                        <a href="{{ route('home') }}#project-{{ $proj->slug }}" target="_blank" class="btn btn-secondary btn-sm">View ↗</a>
                        <form action="{{ route('admin.projects.destroy', $proj->id) }}" method="POST" onsubmit="return confirm('Delete {{ addslashes($proj->title) }}?');">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" title="Delete">Delete</button>
                        </form>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
@endif
@endsection
