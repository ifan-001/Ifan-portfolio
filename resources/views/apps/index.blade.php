@extends('layouts.app')

@section('title', 'Apps, Tools & Games | Hasnain Al Ifan')
@section('meta_description', 'Free tools and games built by Hasnain Al Ifan: CV builder with professional templates, developer toolkit, and LinkedIn-style puzzles like Queens, Tango and Zip.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/apps.css') }}?v={{ filemtime(public_path('css/apps.css')) }}">
@endpush

@section('content')
<div class="ax-page">
    <a href="{{ route('home') }}" class="ax-back">&larr; Back to portfolio</a>

    <header class="ax-hero">
        <div class="ax-hero-copy">
            <span class="ax-kicker">Interactive Lab</span>
            <h1>Apps, tools &amp; <span class="pp-grad">games</span></h1>
            <p>Real software you can use right now. Build a professional CV, use everyday developer tools or take a puzzle break. Free, fast and private: nothing you type is uploaded.</p>
            <div class="ax-hero-links">
                <a href="#create" class="cvb-btn cvb-btn-primary">Build a CV</a>
                <a href="#games" class="cvb-btn">Play a puzzle</a>
            </div>
        </div>
        <div class="ax-hero-art" aria-hidden="true">
            <div class="ax-mini-q">@for($i = 0; $i < 25; $i++)<span class="{{ in_array($i, [1, 8, 10, 17, 24]) ? 'q' : '' }}" style="background: {{ ['#FDE68A','#BFDBFE','#FBCFE8','#BBF7D0','#DDD6FE'][intdiv($i, 5) % 5] }}"></span>@endfor</div>
        </div>
    </header>

    <section class="ax-section" id="create">
        <div class="ax-section-head"><h2>Create</h2><p>Make something you can download and use.</p></div>
        <div class="ax-grid ax-grid-2">
            <a class="ax-card ax-card-feature" href="{{ route('apps.resume-builder') }}">
                <div class="ax-card-art ax-art-cv" aria-hidden="true"><i></i><i></i><i></i><i></i></div>
                <div class="ax-card-body">
                    <span class="ax-tag">Most used</span>
                    <h3>CV Builder</h3>
                    <p>Six professional templates (Modern, Executive, Minimal, Creative, Tech, ATS). Add your photo, pick an accent colour and download a print-ready A4 PDF.</p>
                    <span class="ax-cta">Open CV Builder &rarr;</span>
                </div>
            </a>
            <a class="ax-card ax-card-feature" href="{{ route('apps.qr-medical') }}">
                <div class="ax-card-art ax-art-qr" aria-hidden="true"><span>QR</span></div>
                <div class="ax-card-body">
                    <span class="ax-tag">From DoctorQR</span>
                    <h3>Emergency Medical ID</h3>
                    <p>Create a scannable medical ID card with blood group, allergies and emergency contacts, inspired by the DoctorQR platform.</p>
                    <span class="ax-cta">Make a medical ID &rarr;</span>
                </div>
            </a>
        </div>
    </section>

    <section class="ax-section" id="tools">
        <div class="ax-section-head"><h2>Developer toolkit</h2><p>Quick utilities that run entirely in your browser.</p></div>
        <div class="ax-grid ax-grid-tools">
            @foreach($tools as $id => $t)
                <a class="ax-card ax-tool" href="{{ route('apps.toolkit') }}#{{ $id }}">
                    <span class="ax-tool-icon" aria-hidden="true">{{ $t['icon'] }}</span>
                    <strong>{{ $t['name'] }}</strong>
                </a>
            @endforeach
        </div>
    </section>

    <section class="ax-section" id="games">
        <div class="ax-section-head"><h2>Games &amp; puzzles</h2><p>LinkedIn-style daily puzzles, generated fresh every time, plus a few classics.</p></div>
        <div class="ax-grid ax-grid-3">
            @foreach($games as $slug => $g)
                <a class="ax-card ax-game" href="{{ route('apps.game', $slug) }}">
                    <span class="ax-game-icon" aria-hidden="true">{{ $g['icon'] }}</span>
                    <div>
                        <span class="ax-kicker-sm">{{ $g['kind'] }}</span>
                        <h3>{{ $g['name'] }}</h3>
                        <p>{{ $g['tagline'] }}</p>
                    </div>
                </a>
            @endforeach
            <a class="ax-card ax-game" href="{{ route('apps.cyber-game') }}">
                <span class="ax-game-icon" aria-hidden="true">🛡️</span>
                <div>
                    <span class="ax-kicker-sm">Arcade</span>
                    <h3>Code Breaker: Cyber Defense</h3>
                    <p>Defend the server from incoming exploits in this canvas arcade game.</p>
                </div>
            </a>
        </div>
    </section>
</div>
@endsection
