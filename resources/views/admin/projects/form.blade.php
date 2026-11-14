@extends('admin.layouts.admin')

@php
    $isNew = ! $project->exists;
    $sc = $project->showcase ?? [];
    $stats = old('stats', $sc['stats'] ?? []);
    $features = old('features', $sc['features'] ?? []);
    $gallery = $project->gallery ?? [];
    $mobile = $project->mobile_gallery ?? [];
@endphp

@section('title', $isNew ? 'New Project' : 'Edit ' . $project->title)
@section('page_title', $isNew ? 'New project' : 'Edit project')

@section('content')
<form action="{{ $isNew ? route('admin.projects.store') : route('admin.projects.update', $project->id) }}" method="POST" enctype="multipart/form-data" class="ad-form" id="project-form">
    @csrf
    @unless($isNew) @method('PUT') @endunless
    <input type="hidden" name="showcase_form" value="1">

    <div class="ad-form-head">
        <a href="{{ route('admin.projects.index') }}" class="ad-back">&larr; All projects</a>
        <div class="ad-form-actions">
            @unless($isNew)
                <a href="{{ route('home') }}#project-{{ $project->slug }}" target="_blank" class="btn btn-secondary btn-sm">View on site ↗</a>
            @endunless
            <button type="submit" class="btn btn-primary">{{ $isNew ? 'Create project' : 'Save changes' }}</button>
        </div>
    </div>

    <div class="ad-cols">
        <div class="ad-col-main">
            <section class="card ad-section">
                <h2 class="ad-h">Basics</h2>
                <div class="form-grid-2">
                    <div class="form-group"><label class="form-label">Project name *</label><input class="form-control" name="title" value="{{ old('title', $project->title) }}" required maxlength="150"></div>
                    <div class="form-group"><label class="form-label">URL slug {{ $isNew ? '' : '*' }}</label><input class="form-control" name="slug" value="{{ old('slug', $project->slug) }}" placeholder="auto from name" maxlength="150"></div>
                </div>
                <div class="form-group"><label class="form-label">One-line tagline <small>(shown on the card)</small></label><input class="form-control" name="sc[tagline]" value="{{ old('sc.tagline', $sc['tagline'] ?? '') }}" maxlength="200" placeholder="e.g. Digital practice and telemedicine platform for doctors"></div>
                <div class="form-group"><label class="form-label">Summary *</label><textarea class="form-control" name="summary" rows="3" required maxlength="2000">{{ old('summary', $project->summary) }}</textarea></div>
                <div class="form-group"><label class="form-label">The problem it solves</label><textarea class="form-control" name="sc[problem]" rows="3" maxlength="1500">{{ old('sc.problem', $sc['problem'] ?? '') }}</textarea></div>
                <div class="form-group"><label class="form-label">My role</label><input class="form-control" name="sc[role]" value="{{ old('sc.role', $sc['role'] ?? '') }}" maxlength="300"></div>
                <div class="form-group"><label class="form-label">Tech stack <small>(comma separated)</small></label><input class="form-control" name="technologies_text" value="{{ old('technologies_text', implode(', ', $project->technologies ?? [])) }}" maxlength="800"></div>
            </section>

            <section class="card ad-section">
                <div class="ad-h-row"><h2 class="ad-h">Key features</h2><button type="button" class="btn btn-secondary btn-sm" data-repeat-add="features">+ Add feature</button></div>
                <div class="ad-repeat" data-repeat="features">
                    @foreach($features as $i => $f)
                        <div class="ad-repeat-row">
                            <input class="form-control" name="features[{{ $i }}][title]" value="{{ $f['title'] ?? '' }}" placeholder="Feature title" maxlength="120">
                            <textarea class="form-control" name="features[{{ $i }}][desc]" rows="2" placeholder="Short description" maxlength="600">{{ $f['desc'] ?? '' }}</textarea>
                            <button type="button" class="ad-x" data-repeat-remove title="Remove">&times;</button>
                        </div>
                    @endforeach
                </div>
                <template data-repeat-template="features">
                    <div class="ad-repeat-row">
                        <input class="form-control" name="features[__i__][title]" placeholder="Feature title" maxlength="120">
                        <textarea class="form-control" name="features[__i__][desc]" rows="2" placeholder="Short description" maxlength="600"></textarea>
                        <button type="button" class="ad-x" data-repeat-remove title="Remove">&times;</button>
                    </div>
                </template>
            </section>

            @foreach(['gallery' => ['Web screenshots', $gallery, 'gallery_new', 'Wide screenshots of the website or admin panel (16:10 works best).'], 'mobile_gallery' => ['App / mobile screenshots', $mobile, 'mobile_new', 'Portrait phone screenshots of the app or mobile site.']] as $key => [$label, $items, $newKey, $hint])
                <section class="card ad-section">
                    <div class="ad-h-row"><h2 class="ad-h">{{ $label }} <span class="ad-count">{{ count($items) }}</span></h2></div>
                    <p class="ad-hint">{{ $hint }} Tick "Remove" to delete an image. The first web screenshot is used as the cover.</p>
                    <div class="ad-gallery {{ $key === 'mobile_gallery' ? 'is-phone' : '' }}">
                        @foreach($items as $i => $img)
                            <div class="ad-shot">
                                <img src="{{ asset(ltrim($img['src'], '/')) }}" alt="" loading="lazy">
                                <input type="hidden" name="{{ $key }}[{{ $i }}][src]" value="{{ $img['src'] }}">
                                <input class="form-control" name="{{ $key }}[{{ $i }}][caption]" value="{{ $img['caption'] ?? '' }}" placeholder="Caption" maxlength="150">
                                <label class="ad-remove"><input type="checkbox" name="{{ $key }}[{{ $i }}][remove]" value="1"> Remove</label>
                            </div>
                        @endforeach
                        <label class="ad-upload">
                            <input type="file" name="{{ $newKey }}[]" accept="image/png,image/jpeg,image/webp" multiple data-preview>
                            <span>+ Add images</span>
                            <small>PNG, JPG or WebP · up to 8 MB each</small>
                        </label>
                    </div>
                </section>
            @endforeach
        </div>

        <aside class="ad-col-side">
            <section class="card ad-section">
                <h2 class="ad-h">Display</h2>
                <div class="form-group"><label class="form-label">Category</label>
                    <select class="form-control" name="category">
                        @foreach(['professional' => 'Professional', 'web' => 'Web', 'mobile' => 'Mobile', 'desktop' => 'Desktop', 'ai' => 'AI & Vision'] as $v => $l)
                            <option value="{{ $v }}" @selected(old('category', $project->category) === $v)>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group"><label class="form-label">Type label</label><input class="form-control" name="tagline" value="{{ old('tagline', $project->tagline) }}" placeholder="e.g. SaaS Web Platform · PWA" maxlength="200"></div>
                <div class="form-grid-2">
                    <div class="form-group"><label class="form-label">Status</label><input class="form-control" name="sc[status]" value="{{ old('sc.status', $sc['status'] ?? '') }}" placeholder="Live, In development..." maxlength="80"></div>
                    <div class="form-group"><label class="form-label">Year</label><input class="form-control" name="sc[year]" value="{{ old('sc.year', $sc['year'] ?? '') }}" maxlength="20"></div>
                </div>
                <div class="form-grid-2">
                    <div class="form-group"><label class="form-label">Accent colour</label><input type="color" class="form-control ad-color" name="sc[accent]" value="{{ old('sc.accent', $sc['accent'] ?? '#3B82F6') }}"></div>
                    <div class="form-group"><label class="form-label">Icon</label>
                        <select class="form-control" name="sc[icon]">
                            @foreach($icons as $v => $l)<option value="{{ $v }}" @selected(old('sc.icon', $sc['icon'] ?? 'code') === $v)>{{ $l }}</option>@endforeach
                        </select>
                    </div>
                </div>
                <label class="ad-switch"><input type="checkbox" name="is_featured" value="1" @checked(old('is_featured', $project->is_featured))> <span>Featured (large card)</span></label>
                <div class="form-group" style="margin-top:12px"><label class="form-label">Order <small>(lower shows first)</small></label><input type="number" class="form-control" name="order" min="0" max="999" value="{{ old('order', $project->order) }}"></div>
            </section>

            <section class="card ad-section">
                <h2 class="ad-h">Links</h2>
                <div class="form-group"><label class="form-label">Live site</label><input type="url" class="form-control" name="live_url" value="{{ old('live_url', $project->live_url) }}" placeholder="https://"></div>
                <div class="form-group"><label class="form-label">Source code</label><input type="url" class="form-control" name="github_url" value="{{ old('github_url', $project->github_url) }}" placeholder="https://github.com/..."></div>
            </section>

            <section class="card ad-section">
                <div class="ad-h-row"><h2 class="ad-h">Highlight numbers</h2><button type="button" class="btn btn-secondary btn-sm" data-repeat-add="stats">+ Add</button></div>
                <div class="ad-repeat" data-repeat="stats">
                    @foreach($stats as $i => $st)
                        <div class="ad-repeat-row ad-repeat-inline">
                            <input class="form-control" name="stats[{{ $i }}][value]" value="{{ $st['value'] ?? '' }}" placeholder="475" maxlength="30">
                            <input class="form-control" name="stats[{{ $i }}][label]" value="{{ $st['label'] ?? '' }}" placeholder="Passing tests" maxlength="60">
                            <button type="button" class="ad-x" data-repeat-remove title="Remove">&times;</button>
                        </div>
                    @endforeach
                </div>
                <template data-repeat-template="stats">
                    <div class="ad-repeat-row ad-repeat-inline">
                        <input class="form-control" name="stats[__i__][value]" placeholder="475" maxlength="30">
                        <input class="form-control" name="stats[__i__][label]" placeholder="Passing tests" maxlength="60">
                        <button type="button" class="ad-x" data-repeat-remove title="Remove">&times;</button>
                    </div>
                </template>
            </section>

            <section class="card ad-section">
                <h2 class="ad-h">Cover image</h2>
                @if($project->thumbnail)<img class="ad-cover" src="{{ asset(ltrim($project->thumbnail, '/')) }}" alt="">@endif
                <input type="file" name="thumbnail_image" accept="image/png,image/jpeg,image/webp" class="form-control">
                <p class="ad-hint">Optional. If empty, the first web screenshot is used.</p>
            </section>
        </aside>
    </div>

    <div class="ad-sticky-save"><button type="submit" class="btn btn-primary">{{ $isNew ? 'Create project' : 'Save changes' }}</button></div>
</form>

@unless($isNew)
    <form action="{{ route('admin.projects.destroy', $project->id) }}" method="POST" class="ad-danger" onsubmit="return confirm('Delete {{ addslashes($project->title) }}? This removes it from the portfolio.')">
        @csrf @method('DELETE')
        <div><strong>Delete project</strong><p>Removes the project and its uploaded images.</p></div>
        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
    </form>
@endunless
@endsection

@push('scripts')
<script>
(function () {
    document.addEventListener('click', function (e) {
        var add = e.target.closest('[data-repeat-add]');
        if (add) {
            var name = add.getAttribute('data-repeat-add');
            var list = document.querySelector('[data-repeat="' + name + '"]');
            var tpl = document.querySelector('[data-repeat-template="' + name + '"]');
            var i = Date.now();
            list.insertAdjacentHTML('beforeend', tpl.innerHTML.replace(/__i__/g, i));
            var first = list.lastElementChild.querySelector('input,textarea');
            if (first) first.focus();
        }
        var rm = e.target.closest('[data-repeat-remove]');
        if (rm) rm.closest('.ad-repeat-row').remove();
    });
    // Preview selected uploads
    document.querySelectorAll('[data-preview]').forEach(function (input) {
        input.addEventListener('change', function () {
            var box = input.closest('.ad-upload');
            var n = input.files.length;
            box.querySelector('span').textContent = n ? n + ' image' + (n > 1 ? 's' : '') + ' ready to upload' : '+ Add images';
        });
    });
})();
</script>
@endpush
