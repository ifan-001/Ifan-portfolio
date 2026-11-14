@extends('layouts.app')

@section('title', $meta['name'] . ' — Free Puzzle Game | Hasnain Al Ifan')
@section('meta_description', $meta['tagline'])

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/apps.css') }}?v={{ filemtime(public_path('css/apps.css')) }}">
@endpush

@section('content')
<div class="ax-page ax-game-page">
    <a href="{{ route('apps.index') }}#games" class="ax-back">&larr; All games</a>
    <div class="ax-game-layout">
        <div class="ax-game-main">
            <header class="ax-game-head">
                <div>
                    <span class="ax-kicker">{{ $meta['kind'] }}</span>
                    <h1>{{ $meta['name'] }}</h1>
                </div>
                <div class="ax-game-bar">
                    <span class="ax-timer" id="game-timer" aria-label="Timer">0:00</span>
                    <button type="button" class="cvb-btn" id="game-undo" hidden>Undo</button>
                    <button type="button" class="cvb-btn cvb-btn-primary" id="game-new">New game</button>
                </div>
            </header>
            <div class="ax-board-wrap">
                <div id="game-root" class="ax-game-root" data-game="{{ $game }}"></div>
            </div>
            <p class="ax-status" id="game-status" role="status" aria-live="polite"></p>
        </div>
        <aside class="ax-rules">
            <h2>How to play</h2>
            <ul>
                @foreach($meta['rules'] as $rule)
                    <li>{{ $rule }}</li>
                @endforeach
            </ul>
            <h3>More games</h3>
            <div class="ax-more">
                @foreach($games as $slug => $g)
                    @if((string) $slug !== (string) $game)
                        <a href="{{ route('apps.game', $slug) }}"><span>{{ $g['icon'] }}</span>{{ $g['name'] }}</a>
                    @endif
                @endforeach
                <a href="{{ route('apps.cyber-game') }}"><span>🛡️</span>Cyber Defense</a>
            </div>
        </aside>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/games.js') }}?v={{ filemtime(public_path('js/games.js')) }}"></script>
@endpush
