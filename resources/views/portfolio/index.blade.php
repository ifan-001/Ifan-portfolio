@extends('layouts.app')

@section('content')
    {{-- Hero Section --}}
    @include('partials.hero')

    {{-- Evidence-Based Career Highlights --}}
    <div class="container" style="margin-top: -20px; position: relative; z-index: 10;">
        @include('partials.highlights')
    </div>

    {{-- About Section --}}
    @include('partials.about')

    {{-- Professional Experience --}}
    @include('partials.experience')

    {{-- Projects Showcase --}}
    @include('partials.projects')

    {{-- Technical Skills --}}
    @include('partials.skills')

    {{-- Education Timeline --}}
    @include('partials.education')

    {{-- Research Interests & Capstone --}}
    @include('partials.research')

    {{-- Contact Section --}}
    @include('partials.contact')
@endsection
