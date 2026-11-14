@extends('layouts.app')

@section('title', 'Free Cover Letter Builder | Hasnain Al Ifan')
@section('meta_description', 'Write a professional cover letter in two minutes. Fill a few fields, pick a tone, edit and download as PDF. Free and private.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cv-builder.css') }}?v={{ filemtime(public_path('css/cv-builder.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/doc-apps.css') }}?v={{ filemtime(public_path('css/doc-apps.css')) }}">
@endpush

@section('content')
<div class="cvb">
    <header class="cvb-top no-print">
        <a href="{{ route('apps.index') }}" class="cvb-back">&larr; Apps &amp; Tools</a>
        <div class="cvb-heading">
            <h1>Cover Letter Builder</h1>
            <p>Fill in a few details, choose a tone, then edit the letter freely. Download as PDF when it looks right.</p>
        </div>
        <div class="cvb-top-actions">
            <button type="button" class="cvb-btn" id="cl-sample">Example</button>
            <button type="button" class="cvb-btn cvb-btn-primary" onclick="window.print()">Download PDF</button>
        </div>
    </header>

    <div class="cvb-main">
        <form class="cvb-form no-print" id="cl-form" onsubmit="return false" autocomplete="off">
            <fieldset>
                <legend><span class="dx-step">1</span> About you</legend>
                <div class="cvb-grid">
                    <label>Your name<input data-k="name" placeholder="Ayesha Rahman"></label>
                    <label>Job you want<input data-k="role" placeholder="Software Engineer"></label>
                    <label>Email<input data-k="email" placeholder="you@email.com"></label>
                    <label>Phone<input data-k="phone" placeholder="+880 1XXX-XXXXXX"></label>
                </div>
                <label>Years of experience / level<input data-k="level" placeholder="3 years, or fresh graduate"></label>
                <label>Your top skills <small>(comma separated)</small><input data-k="skills" placeholder="Laravel, REST APIs, MySQL"></label>
                <label>One achievement you are proud of<textarea data-k="win" rows="2" placeholder="Built a billing module used by 1,200 clinics"></textarea></label>
            </fieldset>
            <fieldset>
                <legend><span class="dx-step">2</span> About the job</legend>
                <div class="cvb-grid">
                    <label>Company<input data-k="company" placeholder="TechNova Ltd."></label>
                    <label>Hiring manager <small>(optional)</small><input data-k="manager" placeholder="Ms. Farhana"></label>
                </div>
                <label>Why this company? <small>(optional)</small><input data-k="why" placeholder="your focus on healthcare products"></label>
            </fieldset>
            <fieldset>
                <legend><span class="dx-step">3</span> Tone</legend>
                <div class="dx-pills" id="cl-tone">
                    <button type="button" data-tone="professional" class="active">Professional</button>
                    <button type="button" data-tone="friendly">Friendly</button>
                    <button type="button" data-tone="confident">Confident</button>
                </div>
                <button type="button" class="cvb-btn cvb-btn-primary" id="cl-generate">✨ Write my letter</button>
                <p class="cvb-note">You can edit every word in the preview after it is written.</p>
            </fieldset>
        </form>

        <div class="cvb-preview-wrap dx-preview">
            <article class="cv-paper dx-letter" id="cv-paper">
                <div class="dx-letter-head" id="cl-head"></div>
                <div class="dx-letter-body" id="cl-body" contenteditable="true" spellcheck="true" aria-label="Your cover letter, editable"></div>
            </article>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var KEY = 'cl_state_v1', form = document.getElementById('cl-form'), body = document.getElementById('cl-body'), head = document.getElementById('cl-head');
    var state = {}; try { state = JSON.parse(localStorage.getItem(KEY)) || {}; } catch (e) {}
    state.tone = state.tone || 'professional';
    var esc = function (s) { var d = document.createElement('div'); d.textContent = s || ''; return d.innerHTML; };

    form.querySelectorAll('[data-k]').forEach(function (el) { el.value = state[el.dataset.k] || ''; });
    form.addEventListener('input', function (e) { if (e.target.dataset.k) { state[e.target.dataset.k] = e.target.value; save(); drawHead(); } });
    document.querySelectorAll('#cl-tone button').forEach(function (b) {
        b.classList.toggle('active', b.dataset.tone === state.tone);
        b.addEventListener('click', function () { state.tone = b.dataset.tone; document.querySelectorAll('#cl-tone button').forEach(function (x) { x.classList.toggle('active', x === b); }); save(); });
    });
    body.addEventListener('input', function () { state.letter = body.innerHTML; save(); });

    function save() { try { localStorage.setItem(KEY, JSON.stringify(state)); } catch (e) {} }
    function drawHead() {
        var today = new Date().toLocaleDateString('en-GB', { day: 'numeric', month: 'long', year: 'numeric' });
        head.innerHTML = '<div class="dx-from"><strong>' + (esc(state.name) || 'Your Name') + '</strong><span>' + [state.email, state.phone].filter(Boolean).map(esc).join(' · ') + '</span></div><div class="dx-date">' + today + '</div>' +
            (state.company ? '<div class="dx-to">' + esc(state.manager || 'Hiring Manager') + '<br>' + esc(state.company) + '</div>' : '');
    }
    function write() {
        var s = state, role = s.role || 'the open position', co = s.company || 'your company', skills = (s.skills || '').split(',').map(function (x) { return x.trim(); }).filter(Boolean);
        var skillText = skills.length > 1 ? skills.slice(0, -1).join(', ') + ' and ' + skills[skills.length - 1] : (skills[0] || 'building reliable software');
        var greet = 'Dear ' + (s.manager || 'Hiring Manager') + ',';
        var open = {
            professional: 'I am writing to apply for the ' + role + ' position at ' + co + '. With ' + (s.level || 'hands-on experience') + ' and a strong foundation in ' + skillText + ', I am confident I can contribute to your team from day one.',
            friendly: "I was excited to see the " + role + ' opening at ' + co + '. I bring ' + (s.level || 'practical experience') + ' working with ' + skillText + ", and I'd love the chance to help your team build great things.",
            confident: 'I am the ' + role + ' you are looking for. With ' + (s.level || 'proven experience') + ' in ' + skillText + ', I deliver results quickly and take full ownership of my work.'
        }[s.tone];
        var mid = s.win ? 'One achievement I am particularly proud of: ' + s.win.replace(/\.$/, '') + '. It reflects how I work: understand the real problem, ship a clean solution and measure the result.' : 'In my recent work I have focused on understanding real user problems, shipping clean and tested solutions, and improving them based on feedback.';
        var why = s.why ? 'What draws me to ' + co + ' is ' + s.why.replace(/\.$/, '') + '. I would be glad to bring the same care and energy to your products.' : 'I admire what ' + co + ' is building and would be glad to bring the same care and energy to your products.';
        var close = { professional: 'Thank you for considering my application. I would welcome the opportunity to discuss how I can contribute to ' + co + '.', friendly: "Thanks so much for your time. I'd love to chat about how I can help " + co + '.', confident: 'I look forward to discussing how I can make an immediate impact at ' + co + '.' }[s.tone];
        body.innerHTML = '<p>' + esc(greet) + '</p><p>' + esc(open) + '</p><p>' + esc(mid) + '</p><p>' + esc(why) + '</p><p>' + esc(close) + '</p><p>Sincerely,<br><strong>' + esc(s.name || 'Your Name') + '</strong></p>';
        state.letter = body.innerHTML; save();
    }
    document.getElementById('cl-generate').addEventListener('click', write);
    document.getElementById('cl-sample').addEventListener('click', function () {
        Object.assign(state, { name: 'Ayesha Rahman', role: 'Software Engineer', email: 'ayesha@email.com', phone: '+880 1712-000000', level: '3 years of Laravel experience', skills: 'Laravel, REST APIs, MySQL, Vue.js', win: 'I built a billing module now used by 1,200 clinics', company: 'TechNova Ltd.', manager: 'Ms. Farhana Islam', why: 'your focus on healthcare products that reach thousands of patients' });
        form.querySelectorAll('[data-k]').forEach(function (el) { el.value = state[el.dataset.k] || ''; });
        drawHead(); write();
    });
    drawHead();
    if (state.letter) body.innerHTML = state.letter; else document.getElementById('cl-sample').click();
})();
</script>
@endpush
