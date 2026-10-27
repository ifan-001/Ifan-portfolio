@extends('admin.layouts.admin')

@section('title', 'Resume & CV')
@section('page_title', 'Curriculum Vitae Management')

@section('content')
<div class="card" style="margin-bottom:28px;">
    <div class="card-header">
        <h2 class="card-title">Current CV Status &amp; File Details</h2>
        <a href="{{ route('resume.download') }}" target="_blank" class="btn btn-secondary btn-sm">
            <span>Preview in New Tab &rarr;</span>
        </a>
    </div>

    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:20px; margin-bottom:24px;">
        <div style="padding:16px; background:rgba(255,255,255,0.02); border:1px solid var(--border-color); border-radius:var(--radius-md);">
            <div style="font-size:0.75rem; color:var(--text-muted); text-transform:uppercase; font-weight:700;">File Integrity</div>
            <div style="font-size:1.15rem; font-weight:700; color:{{ $fileExists ? 'var(--status-online)' : 'var(--status-offline)' }}; margin-top:4px;">
                {{ $fileExists ? 'Available on Server' : 'Missing File' }}
            </div>
            <div style="font-size:0.8rem; color:var(--text-secondary); margin-top:4px;">{{ $profile->resume_path }}</div>
        </div>

        <div style="padding:16px; background:rgba(255,255,255,0.02); border:1px solid var(--border-color); border-radius:var(--radius-md);">
            <div style="font-size:0.75rem; color:var(--text-muted); text-transform:uppercase; font-weight:700;">Document Size</div>
            <div style="font-size:1.15rem; font-weight:700; margin-top:4px;">{{ $fileSize }}</div>
            <div style="font-size:0.8rem; color:var(--text-secondary); margin-top:4px;">Optimized for fast mobile loading</div>
        </div>

        <div style="padding:16px; background:rgba(255,255,255,0.02); border:1px solid var(--border-color); border-radius:var(--radius-md);">
            <div style="font-size:0.75rem; color:var(--text-muted); text-transform:uppercase; font-weight:700;">Last Modified</div>
            <div style="font-size:1.15rem; font-weight:700; margin-top:4px;">{{ $lastModified }}</div>
            <div style="font-size:0.8rem; color:var(--text-secondary); margin-top:4px;">Server timestamp</div>
        </div>
    </div>

    <form action="{{ route('admin.resume.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group" style="padding:20px; background:rgba(59, 130, 246, 0.05); border:1px dashed rgba(59, 130, 246, 0.3); border-radius:var(--radius-md);">
            <label for="resume_pdf" class="form-label" style="font-weight:700; font-size:0.95rem;">Upload &amp; Replace CV PDF</label>
            <p style="font-size:0.82rem; color:var(--text-secondary); margin-bottom:12px;">
                Selecting a new PDF file will safely replace the active CV document (`Hasnain-Al-Ifan-CV.pdf`). Max size: 10MB.
            </p>
            <input type="file" id="resume_pdf" name="resume_pdf" accept="application/pdf" class="form-control" style="background:var(--bg-navy);">
        </div>

        <div class="form-group" style="margin-top:20px;">
            <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                <input type="checkbox" name="resume_available" value="1" {{ $profile->resume_available ? 'checked' : '' }} style="width:18px; height:18px; accent-color:var(--accent-blue);">
                <span style="font-weight:600; font-size:0.95rem;">Enable Public Download / Preview</span>
            </label>
            <p style="font-size:0.8rem; color:var(--text-muted); margin-left:28px; margin-top:2px;">
                When unchecked, the public Resume button displays an updating notice instead of serving the file.
            </p>
        </div>

        <div class="form-group">
            <label for="resume_status_note" class="form-label">Status Note (Shown to visitors on hover/status)</label>
            <input type="text" id="resume_status_note" name="resume_status_note" class="form-control" value="{{ old('resume_status_note', $profile->resume_status_note) }}" placeholder="Official CV available for preview and download.">
        </div>

        <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:24px;">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Update Resume Settings</button>
        </div>
    </form>
</div>
@endsection
