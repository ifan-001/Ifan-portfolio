@extends('layouts.app')

@section('title', 'Free Apps, Tools & Games | Hasnain Al Ifan')
@section('meta_description', 'Free tools anyone can use: CV builder, cover letter, invoice maker, photo resizer, QR codes and puzzle games like Queens, Tango and Zip. No sign-up, nothing uploaded.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/apps.css') }}?v={{ filemtime(public_path('css/apps.css')) }}">
@endpush

@php
    $makers = [
        ['route' => 'apps.resume-builder', 'icon' => '📄', 'name' => 'CV Builder', 'desc' => 'Pick a design, type your details, download a PDF.', 'tag' => 'Most popular', 'color' => '#3B82F6'],
        ['route' => 'apps.cover-letter', 'icon' => '✉️', 'name' => 'Cover Letter', 'desc' => 'Answer a few questions, get a ready letter.', 'tag' => 'New', 'color' => '#8B5CF6'],
        ['route' => 'apps.invoice', 'icon' => '🧾', 'name' => 'Invoice Maker', 'desc' => 'Bill a client in BDT or USD with auto totals.', 'tag' => 'New', 'color' => '#10B981'],
        ['route' => 'apps.qr-medical', 'icon' => '🩺', 'name' => 'Medical ID Card', 'desc' => 'Emergency card with a scannable QR code.', 'tag' => null, 'color' => '#EF4444'],
    ];
    $quick = [
        ['url' => route('apps.image-tool'), 'icon' => '🖼️', 'name' => 'Photo resizer', 'desc' => 'Make photos smaller'],
        ['url' => route('apps.toolkit') . '#qr', 'icon' => '▦', 'name' => 'QR code maker', 'desc' => 'Link or text to QR'],
        ['url' => route('apps.toolkit') . '#password', 'icon' => '🔐', 'name' => 'Password maker', 'desc' => 'Strong, random passwords'],
        ['url' => route('apps.toolkit') . '#words', 'icon' => '¶', 'name' => 'Word counter', 'desc' => 'Words and reading time'],
        ['url' => route('apps.toolkit') . '#json', 'icon' => '{ }', 'name' => 'JSON formatter', 'desc' => 'For developers'],
        ['url' => route('apps.toolkit') . '#contrast', 'icon' => '◐', 'name' => 'Colour checker', 'desc' => 'Is text readable?'],
    ];
@endphp

@section('content')
<div class="ax-page ax-simple">
    <a href="{{ route('home') }}" class="ax-back">&larr; Back to portfolio</a>

    <header class="ax-hero">
        <div class="ax-hero-copy">
            <span class="ax-kicker">Free · No sign-up · Private</span>
            <h1>Handy tools, <span class="pp-grad">ready in one click</span></h1>
            <p>Make a CV, write a cover letter, send an invoice, shrink a photo, or take a quick puzzle break. Everything works in your browser and nothing you type is uploaded.</p>
            <nav class="ax-jump" aria-label="Jump to">
                <a href="#create">📄 Make a document</a>
                <a href="#quick">⚡ Quick tools</a>
                <a href="#games">🎮 Games</a>
            </nav>
        </div>
        <div class="ax-hero-art" aria-hidden="true">
            <div class="ax-mini-q">@for($i = 0; $i < 25; $i++)<span class="{{ in_array($i, [1, 8, 10, 17, 24]) ? 'q' : '' }}" style="background: {{ ['#FDE68A','#BFDBFE','#FBCFE8','#BBF7D0','#DDD6FE'][intdiv($i, 5) % 5] }}"></span>@endfor</div>
        </div>
    </header>

    <section class="ax-section" id="create">
        <div class="ax-section-head"><h2>Make a document</h2><p>Fill a simple form, see it live, download a PDF.</p></div>
        <div class="ax-grid ax-grid-4">
            @foreach($makers as $m)
                <a class="ax-card ax-maker" href="{{ route($m['route']) }}" style="--c: {{ $m['color'] }}">
                    <span class="ax-maker-icon" aria-hidden="true">{{ $m['icon'] }}</span>
                    @if($m['tag'])<span class="ax-tag">{{ $m['tag'] }}</span>@endif
                    <h3>{{ $m['name'] }}</h3>
                    <p>{{ $m['desc'] }}</p>
                    <span class="ax-cta">Start →</span>
                </a>
            @endforeach
        </div>
    </section>

    <section class="ax-section" id="quick">
        <div class="ax-section-head"><h2>Quick tools</h2><p>Small jobs, done in seconds.</p></div>
        <div class="ax-grid ax-grid-tools">
            @foreach($quick as $q)
                <a class="ax-card ax-tool" href="{{ $q['url'] }}">
                    <span class="ax-tool-icon" aria-hidden="true">{{ $q['icon'] }}</span>
                    <strong>{{ $q['name'] }}</strong>
                    <small>{{ $q['desc'] }}</small>
                </a>
            @endforeach
        </div>
    </section>

    <section class="ax-section" id="games">
        <div class="ax-section-head"><h2>Games &amp; puzzles</h2><p>LinkedIn-style daily puzzles, a new one every time.</p></div>
        <div class="ax-grid ax-grid-3">
            @foreach($games as $slug => $g)
                <a class="ax-card ax-game" href="{{ route('apps.game', $slug) }}">
                    <span class="ax-game-icon" aria-hidden="true">{{ $g['icon'] }}</span>
                    <div>
                        <h3>{{ $g['name'] }}</h3>
                        <p>{{ $g['tagline'] }}</p>
                    </div>
                </a>
            @endforeach
            <a class="ax-card ax-game" href="{{ route('apps.cyber-game') }}">
                <span class="ax-game-icon" aria-hidden="true">🛡️</span>
                <div>
                    <h3>Cyber Defense</h3>
                    <p>Arcade game: stop the attacks before they reach the server.</p>
                </div>
            </a>
        </div>
    </section>
</div>
@endsection
