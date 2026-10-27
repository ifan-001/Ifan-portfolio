@extends('admin.layouts.admin')

@section('title', 'Work Experience')
@section('page_title', 'Work History & Roles')

@section('content')
<div class="form-grid-2" style="align-items:flex-start;">
    <!-- Add Experience Form -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">+ Add Work Experience</h2>
        </div>

        <form action="{{ route('admin.experience.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="company" class="form-label">Company / Organization *</label>
                <input type="text" id="company" name="company" class="form-control" required placeholder="APPIFLY BD LIMITED">
            </div>

            <div class="form-group">
                <label for="designation" class="form-label">Job Title / Designation *</label>
                <input type="text" id="designation" name="designation" class="form-control" required placeholder="Junior Software Engineer">
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label for="period" class="form-label">Period / Duration *</label>
                    <input type="text" id="period" name="period" class="form-control" required placeholder="Present or 20 June 2026 — Present">
                </div>

                <div class="form-group">
                    <label for="type" class="form-label">Employment Type *</label>
                    <select id="type" name="type" class="form-control" required>
                        <option value="Full-time">Full-time</option>
                        <option value="Internship">Internship</option>
                        <option value="Part-time">Part-time</option>
                        <option value="Contract">Contract</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="location" class="form-label">Location</label>
                <input type="text" id="location" name="location" class="form-control" placeholder="Dhaka, Bangladesh">
            </div>

            <div class="form-group">
                <label for="website" class="form-label">Company Website URL</label>
                <input type="url" id="website" name="website" class="form-control" placeholder="https://appiflybd.com">
            </div>

            <div class="form-group">
                <label for="summary" class="form-label">Role Overview Summary</label>
                <textarea id="summary" name="summary" class="form-control" rows="2" placeholder="Summary of daily responsibilities and key systems worked on."></textarea>
            </div>

            <div class="form-group">
                <label for="highlights_text" class="form-label">Key Highlights / Achievements (One per line)</label>
                <textarea id="highlights_text" name="highlights_text" class="form-control" rows="4" placeholder="Independently developed DoctorQR platform&#10;Implemented Agora RTC video calling&#10;Integrated Alpha SMS gateway"></textarea>
            </div>

            <div class="form-group">
                <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                    <input type="checkbox" name="is_current" value="1" checked style="accent-color:var(--accent-blue);">
                    <span style="font-weight:600;">Currently Working in This Role</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;">Add Work Experience</button>
        </form>
    </div>

    <!-- Existing Experience List -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Experience Records ({{ $experiences->count() }})</h2>
        </div>

        @if($experiences->isEmpty())
            <p style="color:var(--text-muted); font-size:0.88rem;">No work experience entries recorded yet.</p>
        @else
            @foreach($experiences as $exp)
                <div style="padding:16px; background:rgba(255,255,255,0.02); border:1px solid var(--border-color); border-radius:var(--radius-md); margin-bottom:16px;">
                    <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:12px;">
                        <div>
                            <div style="font-weight:700; font-size:1rem; color:var(--text-primary);">{{ $exp->designation }}</div>
                            <div style="font-size:0.88rem; color:var(--accent-blue); font-weight:600;">{{ $exp->company }}</div>
                            <div style="font-size:0.75rem; color:var(--text-muted); margin-top:2px;">
                                {{ $exp->period }} &middot; {{ $exp->type }} &middot; {{ $exp->location }}
                            </div>
                        </div>

                        <form action="{{ route('admin.experience.destroy', $exp->id) }}" method="POST" onsubmit="return confirm('Delete experience at {{ $exp->company }}?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>

                    @if(!empty($exp->highlights))
                        <ul style="margin-top:12px; padding-left:18px; font-size:0.82rem; color:var(--text-secondary);">
                            @foreach($exp->highlights as $hl)
                                <li>{{ $hl }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
