@extends('layouts.app', ['portfolio' => config('portfolio')])

@section('content')
<section class="section" style="min-height: 70vh; display: flex; align-items: center; justify-content: center; text-align: center;">
    <div class="container" style="max-width: 600px;">
        <span class="monogram" style="width: 56px; height: 56px; font-size: 1.5rem; margin: 0 auto 24px;">404</span>
        <h1 class="section-title" style="margin-bottom: 12px;">Page Not Found</h1>
        <p class="section-desc" style="margin: 0 auto 28px;">
            The page you are looking for does not exist or has been relocated.
        </p>
        <a href="{{ route('home') }}" class="btn btn-primary">
            &larr; Return to Portfolio Home
        </a>
    </div>
</section>
@endsection
