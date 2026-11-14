@extends('layouts.app')

@section('title', 'Image Resizer & Compressor (Free, Private) | Hasnain Al Ifan')
@section('meta_description', 'Make photos smaller for email, forms and websites. Resize, compress and convert to JPG, PNG or WebP right in your browser. Nothing is uploaded.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/apps.css') }}?v={{ filemtime(public_path('css/apps.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/doc-apps.css') }}?v={{ filemtime(public_path('css/doc-apps.css')) }}">
@endpush

@section('content')
<div class="ax-page">
    <a href="{{ route('apps.index') }}" class="ax-back">&larr; Apps &amp; Tools</a>
    <header class="ax-hero-mini">
        <span class="ax-kicker">Image tool</span>
        <h1>Resize &amp; compress photos</h1>
        <p>Perfect for job applications, forms with size limits, email and websites. Your photo never leaves your device.</p>
    </header>

    <div class="im-wrap">
        <label class="im-drop" id="im-drop">
            <input type="file" id="im-file" accept="image/*">
            <span class="im-drop-icon">🖼️</span>
            <strong>Choose a photo</strong>
            <small>or drag it here · JPG, PNG, WebP</small>
        </label>

        <div class="im-work" id="im-work" hidden>
            <div class="im-preview"><img id="im-img" alt="Preview"></div>
            <div class="im-controls">
                <div class="im-presets">
                    <span>Quick size</span>
                    <button type="button" data-w="1920">Large (1920px)</button>
                    <button type="button" data-w="1280">Web (1280px)</button>
                    <button type="button" data-w="800">Email (800px)</button>
                    <button type="button" data-w="400">Profile (400px)</button>
                </div>
                <label>Width <b id="im-w-v"></b><input type="range" id="im-w" min="100" max="4000" step="10"></label>
                <label>Quality <b id="im-q-v">80%</b><input type="range" id="im-q" min="30" max="100" value="80"></label>
                <label>Format
                    <select id="im-fmt"><option value="image/jpeg">JPG (smallest for photos)</option><option value="image/webp">WebP (modern, small)</option><option value="image/png">PNG (no quality loss)</option></select>
                </label>
                <div class="tk-stats">
                    <div><strong id="im-orig">–</strong><span>Original</span></div>
                    <div><strong id="im-new">–</strong><span>New size</span></div>
                    <div><strong id="im-dim">–</strong><span>Dimensions</span></div>
                </div>
                <div class="tk-row">
                    <button type="button" class="cvb-btn cvb-btn-primary" id="im-dl">Download</button>
                    <button type="button" class="cvb-btn" id="im-reset">Choose another</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var file = document.getElementById('im-file'), drop = document.getElementById('im-drop'), work = document.getElementById('im-work');
    var img = new Image(), preview = document.getElementById('im-img'), w = document.getElementById('im-w'), q = document.getElementById('im-q'), fmt = document.getElementById('im-fmt');
    var name = 'image', origSize = 0, blob = null, timer;
    var kb = function (b) { return b > 1048576 ? (b / 1048576).toFixed(2) + ' MB' : Math.round(b / 1024) + ' KB'; };
    function load(f) {
        if (!f || !/^image\//.test(f.type)) return;
        name = f.name.replace(/\.[^.]+$/, ''); origSize = f.size;
        var r = new FileReader();
        r.onload = function () { img.onload = function () { w.max = img.width; w.value = Math.min(img.width, 1280); drop.hidden = true; work.hidden = false; document.getElementById('im-orig').textContent = kb(origSize); run(); }; img.src = r.result; };
        r.readAsDataURL(f);
    }
    file.addEventListener('change', function () { load(file.files[0]); });
    ['dragover', 'dragenter'].forEach(function (ev) { drop.addEventListener(ev, function (e) { e.preventDefault(); drop.classList.add('over'); }); });
    ['dragleave', 'drop'].forEach(function (ev) { drop.addEventListener(ev, function (e) { e.preventDefault(); drop.classList.remove('over'); }); });
    drop.addEventListener('drop', function (e) { load(e.dataTransfer.files[0]); });
    function run() {
        clearTimeout(timer);
        timer = setTimeout(function () {
            var width = Math.min(+w.value, img.width), height = Math.round(img.height * width / img.width);
            document.getElementById('im-w-v').textContent = width + 'px';
            document.getElementById('im-q-v').textContent = q.value + '%';
            var c = document.createElement('canvas'); c.width = width; c.height = height;
            var ctx = c.getContext('2d');
            if (fmt.value === 'image/jpeg') { ctx.fillStyle = '#fff'; ctx.fillRect(0, 0, width, height); }
            ctx.imageSmoothingQuality = 'high'; ctx.drawImage(img, 0, 0, width, height);
            c.toBlob(function (b) {
                blob = b; preview.src = URL.createObjectURL(b);
                document.getElementById('im-new').textContent = kb(b.size) + (origSize ? ' (' + Math.round(100 - b.size / origSize * 100) + '% smaller)' : '');
                document.getElementById('im-dim').textContent = width + ' × ' + height;
            }, fmt.value, +q.value / 100);
        }, 120);
    }
    [w, q].forEach(function (el) { el.addEventListener('input', run); });
    fmt.addEventListener('change', run);
    document.querySelectorAll('[data-w]').forEach(function (b) { b.addEventListener('click', function () { w.value = Math.min(+b.dataset.w, img.width); run(); }); });
    document.getElementById('im-dl').addEventListener('click', function () {
        if (!blob) return;
        var ext = { 'image/jpeg': 'jpg', 'image/webp': 'webp', 'image/png': 'png' }[fmt.value];
        var a = document.createElement('a'); a.href = URL.createObjectURL(blob); a.download = name + '-resized.' + ext; a.click();
    });
    document.getElementById('im-reset').addEventListener('click', function () { file.value = ''; work.hidden = true; drop.hidden = false; });
})();
</script>
@endpush
