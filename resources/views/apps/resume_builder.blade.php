@extends('layouts.app')

@section('title', 'Free CV Builder with Professional Templates | Hasnain Al Ifan')
@section('meta_description', 'Build a professional CV in minutes. Pick a template, fill in your details, add a photo and download a print-ready PDF. Free, private, nothing is uploaded.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/cv-builder.css') }}?v={{ filemtime(public_path('css/cv-builder.css')) }}">
@endpush

@section('content')
<div class="cvb">
    <header class="cvb-top no-print">
        <a href="{{ route('apps.index') }}" class="cvb-back">&larr; Apps &amp; Tools</a>
        <div class="cvb-heading">
            <h1>CV Builder</h1>
            <p>Pick a template, fill in your details, download a print-ready PDF. Everything stays in your browser.</p>
        </div>
        <div class="cvb-top-actions">
            <button type="button" class="cvb-btn" id="cvb-sample">Load sample</button>
            <button type="button" class="cvb-btn" id="cvb-reset">Clear</button>
            <button type="button" class="cvb-btn cvb-btn-primary" id="cvb-download">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Download PDF
            </button>
        </div>
    </header>

    <section class="cvb-templates no-print" aria-label="Choose a template">
        <div class="cvb-templates-row" id="cvb-templates"></div>
        <label class="cvb-accent">Accent <input type="color" id="cvb-accent" value="#2563EB"></label>
    </section>

    <div class="cvb-main">
        <form class="cvb-form no-print" id="cvb-form" autocomplete="off" onsubmit="return false">
            <fieldset>
                <legend>Personal details</legend>
                <div class="cvb-photo">
                    <div class="cvb-photo-preview" id="cvb-photo-preview" aria-hidden="true">+</div>
                    <div>
                        <label class="cvb-btn cvb-file">Upload photo<input type="file" id="cvb-photo" accept="image/*"></label>
                        <button type="button" class="cvb-link" id="cvb-photo-remove">Remove</button>
                        <small>Square or portrait photos fit best. It is cropped automatically.</small>
                    </div>
                </div>
                <div class="cvb-grid">
                    <label>Full name<input data-field="name" placeholder="e.g. Ayesha Rahman"></label>
                    <label>Job title<input data-field="title" placeholder="e.g. Software Engineer"></label>
                    <label>Email<input data-field="email" type="email" placeholder="you@email.com"></label>
                    <label>Phone<input data-field="phone" placeholder="+880 1XXX-XXXXXX"></label>
                    <label>Location<input data-field="location" placeholder="Dhaka, Bangladesh"></label>
                    <label>Website / Portfolio<input data-field="website" placeholder="yourname.dev"></label>
                    <label>LinkedIn<input data-field="linkedin" placeholder="linkedin.com/in/you"></label>
                    <label>GitHub<input data-field="github" placeholder="github.com/you"></label>
                </div>
                <label>Professional summary<textarea data-field="summary" rows="4" placeholder="2–3 sentences about what you do and what you are looking for."></textarea></label>
            </fieldset>

            <fieldset data-list="experience">
                <legend>Experience <button type="button" class="cvb-add" data-add="experience">+ Add</button></legend>
                <div class="cvb-items"></div>
            </fieldset>

            <fieldset data-list="projects">
                <legend>Projects <button type="button" class="cvb-add" data-add="projects">+ Add</button></legend>
                <div class="cvb-items"></div>
            </fieldset>

            <fieldset data-list="education">
                <legend>Education <button type="button" class="cvb-add" data-add="education">+ Add</button></legend>
                <div class="cvb-items"></div>
            </fieldset>

            <fieldset>
                <legend>Skills &amp; more</legend>
                <label>Skills <small>(comma separated)</small><textarea data-field="skills" rows="2" placeholder="Laravel, PHP, MySQL, JavaScript"></textarea></label>
                <label>Languages <small>(comma separated)</small><input data-field="languages" placeholder="Bengali (Native), English (Fluent)"></label>
                <label>Certifications &amp; awards <small>(one per line)</small><textarea data-field="certifications" rows="3"></textarea></label>
            </fieldset>
            <p class="cvb-note">Saved automatically in this browser only. Nothing is sent to a server.</p>
        </form>

        <div class="cvb-preview-wrap">
            <div class="cvb-preview-scale" id="cvb-scale">
                <article class="cv-paper" id="cv-paper" aria-label="CV preview"></article>
            </div>
        </div>
    </div>

    <div class="cvb-print-tip no-print" id="cvb-print-tip" hidden>
        In the print window choose <strong>Save as PDF</strong>, paper <strong>A4</strong>, margins <strong>None</strong> and turn on <strong>Background graphics</strong>.
        <button type="button" class="cvb-link" onclick="this.parentElement.hidden=true">Got it</button>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/cv-builder.js') }}?v={{ filemtime(public_path('js/cv-builder.js')) }}"></script>
@endpush
