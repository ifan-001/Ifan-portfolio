@extends('admin.layouts.admin')

@section('title', 'Site content')
@section('page_title', 'Site content')

@section('content')
<div class="ad-page-head">
    <div>
        <h2>Site content</h2>
        <p>Every text, card and link on the public portfolio. Changes appear on the site immediately.</p>
    </div>
    <a href="{{ route('home') }}" target="_blank" class="btn btn-secondary">View site ↗</a>
</div>

<nav class="ad-tabs" aria-label="Content sections">
    @foreach(['texts' => 'Texts & SEO', 'about' => 'About cards', 'highlights' => 'Highlights', 'socials' => 'Social links'] as $key => $label)
        <a href="{{ route('admin.site-content', ['tab' => $key]) }}" class="ad-tab {{ $tab === $key ? 'active' : '' }}">{{ $label }}</a>
    @endforeach
</nav>

@if($tab === 'texts')
    <form method="POST" action="{{ route('admin.site-content.texts') }}" class="ad-form">
        @csrf
        @foreach($textGroups as $group => $fields)
            <section class="card ad-section">
                <h2 class="ad-h">{{ $group }}</h2>
                <div class="ad-text-grid">
                    @foreach($fields as $key => [$label, $default, $type])
                        <div class="form-group {{ $type === 'textarea' ? 'ad-span-2' : '' }}">
                            <label class="form-label" for="t-{{ $key }}">{{ $label }}</label>
                            @if($type === 'textarea')
                                <textarea id="t-{{ $key }}" class="form-control" name="texts[{{ $key }}]" rows="2" maxlength="600">{{ old('texts.' . $key, $texts[$key]) }}</textarea>
                            @else
                                <input id="t-{{ $key }}" class="form-control" name="texts[{{ $key }}]" value="{{ old('texts.' . $key, $texts[$key]) }}" maxlength="200">
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>
        @endforeach
        <p class="ad-hint">Leave a field empty to use the built-in default.</p>
        <div class="ad-sticky-save"><button class="btn btn-primary" type="submit">Save texts</button></div>
    </form>
@endif

@if($tab === 'about')
    <p class="ad-hint">The About section's numbered cards. Your biography paragraphs are edited from <a href="{{ route('admin.profile.edit') }}">Profile</a>.</p>
    <div class="ad-list">
        @foreach($focusAreas as $f)
            <form method="POST" action="{{ route('admin.site-content.focus.update', $f) }}" class="card ad-row-form">
                @csrf @method('PUT')
                <input class="form-control ad-w-num" name="number" value="{{ $f->number }}" maxlength="6" aria-label="Number">
                <div class="ad-row-fields">
                    <input class="form-control" name="title" value="{{ $f->title }}" maxlength="120" required aria-label="Title">
                    <textarea class="form-control" name="description" rows="2" maxlength="600" required aria-label="Description">{{ $f->description }}</textarea>
                </div>
                <input class="form-control ad-w-num" type="number" name="order" value="{{ $f->order }}" min="0" max="999" aria-label="Order" title="Order">
                <div class="ad-row-actions">
                    <button class="btn btn-primary btn-sm" type="submit">Save</button>
                    <button class="btn btn-danger btn-sm" type="submit" form="del-focus-{{ $f->id }}">Delete</button>
                </div>
            </form>
            <form id="del-focus-{{ $f->id }}" method="POST" action="{{ route('admin.site-content.focus.destroy', $f) }}" onsubmit="return confirm('Delete this card?')">@csrf @method('DELETE')</form>
        @endforeach
    </div>
    <form method="POST" action="{{ route('admin.site-content.focus.store') }}" class="card ad-add-form">
        @csrf
        <h3>Add a card</h3>
        <input class="form-control" name="title" placeholder="Title, e.g. Mobile Development" maxlength="120" required>
        <textarea class="form-control" name="description" rows="2" placeholder="One or two sentences" maxlength="600" required></textarea>
        <button class="btn btn-primary btn-sm" type="submit">+ Add card</button>
    </form>
@endif

@if($tab === 'highlights')
    <p class="ad-hint">The fact boxes shown under the hero (e.g. current role, flagship project).</p>
    <div class="ad-list">
        @foreach($achievements as $a)
            <form method="POST" action="{{ route('admin.site-content.highlight.update', $a) }}" class="card ad-row-form">
                @csrf @method('PUT')
                <div class="ad-row-fields ad-row-3">
                    <input class="form-control" name="category" value="{{ $a->category }}" maxlength="60" required aria-label="Label" placeholder="Label">
                    <input class="form-control" name="title" value="{{ $a->title }}" maxlength="120" required aria-label="Value" placeholder="Value">
                    <input class="form-control" name="description" value="{{ $a->description }}" maxlength="300" aria-label="Detail" placeholder="Detail">
                </div>
                <input class="form-control ad-w-num" type="number" name="order" value="{{ $a->order }}" min="0" max="999" aria-label="Order" title="Order">
                <div class="ad-row-actions">
                    <button class="btn btn-primary btn-sm" type="submit">Save</button>
                    <button class="btn btn-danger btn-sm" type="submit" form="del-hl-{{ $a->id }}">Delete</button>
                </div>
            </form>
            <form id="del-hl-{{ $a->id }}" method="POST" action="{{ route('admin.site-content.highlight.destroy', $a) }}" onsubmit="return confirm('Delete this highlight?')">@csrf @method('DELETE')</form>
        @endforeach
    </div>
    <form method="POST" action="{{ route('admin.site-content.highlight.store') }}" class="card ad-add-form">
        @csrf
        <h3>Add a highlight</h3>
        <div class="ad-row-3">
            <input class="form-control" name="category" placeholder="Label, e.g. Current Role" maxlength="60" required>
            <input class="form-control" name="title" placeholder="Value, e.g. Junior Software Engineer" maxlength="120" required>
            <input class="form-control" name="description" placeholder="Detail (optional)" maxlength="300">
        </div>
        <button class="btn btn-primary btn-sm" type="submit">+ Add highlight</button>
    </form>
@endif

@if($tab === 'socials')
    <p class="ad-hint">Shown in the hero, contact section and footer. Links must start with https://, mailto: or tel:.</p>
    <div class="ad-list">
        @foreach($socials as $s)
            <form method="POST" action="{{ route('admin.site-content.social.update', $s) }}" class="card ad-row-form">
                @csrf @method('PUT')
                <select class="form-control ad-w-icon" name="icon" aria-label="Icon">
                    @foreach($socialIcons as $v => $l)<option value="{{ $v }}" @selected($s->icon === $v)>{{ $l }}</option>@endforeach
                </select>
                <div class="ad-row-fields ad-row-3">
                    <input class="form-control" name="name" value="{{ $s->name }}" maxlength="40" required aria-label="Name" placeholder="Name">
                    <input class="form-control" name="url" value="{{ $s->url }}" maxlength="255" required aria-label="URL" placeholder="https://">
                    <input class="form-control" name="handle" value="{{ $s->handle }}" maxlength="100" aria-label="Handle" placeholder="@handle">
                </div>
                <label class="ad-switch"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" @checked($s->is_active)> <span>Show</span></label>
                <div class="ad-row-actions">
                    <button class="btn btn-primary btn-sm" type="submit">Save</button>
                    <button class="btn btn-danger btn-sm" type="submit" form="del-so-{{ $s->id }}">Delete</button>
                </div>
            </form>
            <form id="del-so-{{ $s->id }}" method="POST" action="{{ route('admin.site-content.social.destroy', $s) }}" onsubmit="return confirm('Delete {{ addslashes($s->name) }}?')">@csrf @method('DELETE')</form>
        @endforeach
    </div>
    <form method="POST" action="{{ route('admin.site-content.social.store') }}" class="card ad-add-form">
        @csrf
        <h3>Add a link</h3>
        <div class="ad-row-3">
            <select class="form-control" name="icon">@foreach($socialIcons as $v => $l)<option value="{{ $v }}">{{ $l }}</option>@endforeach</select>
            <input class="form-control" name="name" placeholder="Name, e.g. YouTube" maxlength="40" required>
            <input class="form-control" name="url" placeholder="https://..." maxlength="255" required>
        </div>
        <input class="form-control" name="handle" placeholder="Handle (optional)" maxlength="100">
        <button class="btn btn-primary btn-sm" type="submit">+ Add link</button>
    </form>
@endif
@endsection
