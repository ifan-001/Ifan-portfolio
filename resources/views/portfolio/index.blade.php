@extends('layouts.app')

@section('content')
    {{-- Hero Section --}}
    @if($portfolio['settings']['show_hero'] ?? true)
        @include('partials.hero')

        {{-- Evidence-Based Career Highlights --}}
        <div class="container" style="margin-top: -20px; position: relative; z-index: 10;">
            @include('partials.highlights')
        </div>
    @endif

    {{-- About Section --}}
    @if($portfolio['settings']['show_about'] ?? true)
        @include('partials.about')
    @endif

    {{-- Professional Experience --}}
    @if($portfolio['settings']['show_experience'] ?? true)
        @include('partials.experience')
    @endif

    {{-- Projects Showcase --}}
    @if($portfolio['settings']['show_projects'] ?? true)
        @include('partials.projects')
    @endif

    {{-- Technical Skills --}}
    @if($portfolio['settings']['show_skills'] ?? true)
        @include('partials.skills')
    @endif

    {{-- Education Timeline --}}
    @if($portfolio['settings']['show_education'] ?? true)
        @include('partials.education')
    @endif

    {{-- Research Interests & Capstone --}}
    @if($portfolio['settings']['show_research'] ?? true)
        @include('partials.research')
    @endif

    {{-- Engineering Blog Articles --}}
    @if($portfolio['settings']['show_blog'] ?? true)
        @include('partials.blog')
    @endif

    {{-- Contact Section --}}
    @if($portfolio['settings']['show_contact'] ?? true)
        @include('partials.contact')
    @endif
@endsection
