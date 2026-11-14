@extends('layouts.app')

@section('title', 'Free Developer Toolkit: JSON, Password, QR, Base64 & more | Hasnain Al Ifan')
@section('meta_description', 'Fast, private browser tools: JSON formatter, password generator, QR code maker, word counter, Base64 and URL encoder, colour contrast checker and timestamp converter.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/apps.css') }}?v={{ filemtime(public_path('css/apps.css')) }}">
@endpush

@section('content')
<div class="ax-page">
    <a href="{{ route('apps.index') }}" class="ax-back">&larr; Apps &amp; Tools</a>
    <header class="ax-hero-mini">
        <span class="ax-kicker">Developer Toolkit</span>
        <h1>Everyday tools, <span class="pp-grad">zero uploads</span></h1>
        <p>Everything runs in your browser. Nothing you paste here leaves your device.</p>
    </header>

    <div class="tk-layout">
        <nav class="tk-tabs" role="tablist" aria-label="Tools">
            @foreach($tools as $id => $t)
                <button type="button" role="tab" class="tk-tab {{ $loop->first ? 'active' : '' }}" data-tool="{{ $id }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}"><span>{{ $t['icon'] }}</span>{{ $t['name'] }}</button>
            @endforeach
        </nav>

        <div class="tk-panels">
            <section class="tk-panel active" data-panel="json">
                <h2>JSON formatter &amp; validator</h2>
                <textarea id="tk-json-in" rows="10" spellcheck="false" placeholder='{"paste": "your JSON here"}'></textarea>
                <div class="tk-row">
                    <button type="button" class="cvb-btn cvb-btn-primary" data-json="format">Format</button>
                    <button type="button" class="cvb-btn" data-json="minify">Minify</button>
                    <button type="button" class="cvb-btn" data-copy="tk-json-out">Copy result</button>
                    <span class="tk-msg" id="tk-json-msg"></span>
                </div>
                <pre class="tk-out" id="tk-json-out"></pre>
            </section>

            <section class="tk-panel" data-panel="password">
                <h2>Password generator</h2>
                <div class="tk-pass"><output id="tk-pass-out">—</output><button type="button" class="cvb-btn" data-copy="tk-pass-out">Copy</button></div>
                <div class="tk-meter"><span id="tk-pass-meter"></span></div>
                <label class="tk-range">Length <input type="range" id="tk-pass-len" min="8" max="64" value="20"> <b id="tk-pass-len-v">20</b></label>
                <div class="tk-checks">
                    <label><input type="checkbox" id="tk-p-upper" checked> A–Z</label>
                    <label><input type="checkbox" id="tk-p-lower" checked> a–z</label>
                    <label><input type="checkbox" id="tk-p-num" checked> 0–9</label>
                    <label><input type="checkbox" id="tk-p-sym" checked> Symbols</label>
                </div>
                <button type="button" class="cvb-btn cvb-btn-primary" id="tk-pass-gen">Generate</button>
                <p class="tk-hint">Uses the browser's cryptographic random generator.</p>
            </section>

            <section class="tk-panel" data-panel="qr">
                <h2>QR code maker</h2>
                <textarea id="tk-qr-in" rows="3" placeholder="Paste a link, text, Wi-Fi details...">{{ url('/') }}</textarea>
                <div class="tk-row">
                    <label class="tk-range">Size <input type="range" id="tk-qr-size" min="128" max="512" step="32" value="256"></label>
                    <button type="button" class="cvb-btn cvb-btn-primary" id="tk-qr-dl">Download PNG</button>
                </div>
                <div class="tk-qr" id="tk-qr-out"></div>
            </section>

            <section class="tk-panel" data-panel="words">
                <h2>Word &amp; character counter</h2>
                <textarea id="tk-words-in" rows="10" placeholder="Paste or type text (Bangla works too)..."></textarea>
                <div class="tk-stats" id="tk-words-stats"></div>
            </section>

            <section class="tk-panel" data-panel="encode">
                <h2>Base64 &amp; URL encoder</h2>
                <textarea id="tk-enc-in" rows="6" placeholder="Text to encode or decode"></textarea>
                <div class="tk-row">
                    <button type="button" class="cvb-btn cvb-btn-primary" data-enc="b64e">Base64 encode</button>
                    <button type="button" class="cvb-btn" data-enc="b64d">Base64 decode</button>
                    <button type="button" class="cvb-btn" data-enc="urle">URL encode</button>
                    <button type="button" class="cvb-btn" data-enc="urld">URL decode</button>
                    <button type="button" class="cvb-btn" data-copy="tk-enc-out">Copy</button>
                </div>
                <pre class="tk-out" id="tk-enc-out"></pre>
            </section>

            <section class="tk-panel" data-panel="contrast">
                <h2>Colour contrast checker (WCAG)</h2>
                <div class="tk-row">
                    <label class="tk-color">Text <input type="color" id="tk-fg" value="#F5F7FA"><input type="text" id="tk-fg-t" value="#F5F7FA" maxlength="7"></label>
                    <label class="tk-color">Background <input type="color" id="tk-bg" value="#0B1120"><input type="text" id="tk-bg-t" value="#0B1120" maxlength="7"></label>
                </div>
                <div class="tk-contrast-demo" id="tk-demo"><strong>Readable text?</strong> The quick brown fox jumps over the lazy dog.</div>
                <div class="tk-stats" id="tk-contrast-stats"></div>
            </section>

            <section class="tk-panel" data-panel="time">
                <h2>Unix timestamp converter</h2>
                <div class="tk-row"><label class="tk-field">Timestamp (seconds or ms)<input type="text" id="tk-ts" inputmode="numeric"></label><button type="button" class="cvb-btn" id="tk-ts-now">Now</button></div>
                <div class="tk-stats" id="tk-ts-out"></div>
                <div class="tk-row"><label class="tk-field">Date &amp; time<input type="datetime-local" id="tk-dt"></label></div>
                <div class="tk-stats" id="tk-dt-out"></div>
            </section>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/qrcode.min.js') }}"></script>
    <script src="{{ asset('js/toolkit.js') }}?v={{ filemtime(public_path('js/toolkit.js')) }}"></script>
@endpush
