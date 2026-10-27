@extends('admin.layouts.admin')

@section('title', 'Education')
@section('page_title', 'Academic Background & Degrees')

@section('content')
<div class="form-grid-2" style="align-items:flex-start;">
    <!-- Add Education Form -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">+ Add Academic Degree</h2>
        </div>

        <form action="{{ route('admin.education.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="institution" class="form-label">Institution Name *</label>
                <input type="text" id="institution" name="institution" class="form-control" required placeholder="Bangladesh University of Business and Technology (BUBT)">
            </div>

            <div class="form-group">
                <label for="degree" class="form-label">Degree Title *</label>
                <input type="text" id="degree" name="degree" class="form-control" required placeholder="Bachelor of Science in Computer Science and Engineering">
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label for="field" class="form-label">Field / Group</label>
                    <input type="text" id="field" name="field" class="form-control" placeholder="Computer Science & Engineering">
                </div>

                <div class="form-group">
                    <label for="period" class="form-label">Passing Year / Expected *</label>
                    <input type="text" id="period" name="period" class="form-control" required placeholder="December 2026 or 2021">
                </div>
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label for="cgpa" class="form-label">CGPA / GPA</label>
                    <input type="text" id="cgpa" name="cgpa" class="form-control" placeholder="2.86">
                </div>

                <div class="form-group">
                    <label for="scale" class="form-label">Scale</label>
                    <input type="text" id="scale" name="scale" class="form-control" value="4.00" placeholder="4.00">
                </div>
            </div>

            <div class="form-group">
                <label for="status" class="form-label">Academic Status *</label>
                <input type="text" id="status" name="status" class="form-control" required value="Final Year" placeholder="Final-year Student (8th Semester) or Completed">
            </div>

            <div class="form-group">
                <label for="coursework_text" class="form-label">Coursework / Achievements (Comma-separated)</label>
                <textarea id="coursework_text" name="coursework_text" class="form-control" rows="3" placeholder="Data Structures, Algorithms, Database Systems, Computer Networks, Software Engineering"></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;">Add Education Record</button>
        </form>
    </div>

    <!-- Existing Education List -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Education Records ({{ $educations->count() }})</h2>
        </div>

        @if($educations->isEmpty())
            <p style="color:var(--text-muted); font-size:0.88rem;">No academic records found.</p>
        @else
            @foreach($educations as $edu)
                <div style="padding:16px; background:rgba(255,255,255,0.02); border:1px solid var(--border-color); border-radius:var(--radius-md); margin-bottom:16px;">
                    <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:12px;">
                        <div>
                            <div style="font-weight:700; font-size:0.95rem; color:var(--text-primary);">{{ $edu->degree }}</div>
                            <div style="font-size:0.85rem; color:var(--accent-blue); font-weight:600;">{{ $edu->institution }}</div>
                            <div style="font-size:0.75rem; color:var(--text-muted); margin-top:2px;">
                                {{ $edu->status }} &middot; CGPA: {{ $edu->cgpa }} / {{ $edu->scale }} &middot; {{ $edu->period }}
                            </div>
                        </div>

                        <form action="{{ route('admin.education.destroy', $edu->id) }}" method="POST" onsubmit="return confirm('Delete education record?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </div>

                    @if(!empty($edu->achievements))
                        <div style="margin-top:10px; display:flex; flex-wrap:wrap; gap:6px;">
                            @foreach($edu->achievements as $c)
                                <span style="font-size:0.7rem; background:rgba(255,255,255,0.05); padding:2px 8px; border-radius:4px; color:var(--text-secondary);">{{ $c }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
