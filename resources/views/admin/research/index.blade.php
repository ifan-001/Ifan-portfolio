@extends('admin.layouts.admin')

@section('title', 'Research & Highlights')
@section('page_title', 'Research Interests & Verified Highlights')

@section('content')
<div class="form-grid-2" style="align-items:flex-start; margin-bottom:28px;">
    <!-- Add Research Topic Form -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">+ Add Research Topic / Paper</h2>
        </div>

        <form action="{{ route('admin.research.item.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="title" class="form-label">Research Title *</label>
                <input type="text" id="title" name="title" class="form-control" required placeholder="Cell Nuclei Segmentation with Deep Learning">
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label for="type" class="form-label">Type *</label>
                    <select id="type" name="type" class="form-control" required>
                        <option value="Research Topic">Research Topic</option>
                        <option value="Publication">Publication</option>
                        <option value="Pre-print">Pre-print</option>
                        <option value="Academic Capstone">Academic Capstone</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="status" class="form-label">Status *</label>
                    <input type="text" id="status" name="status" class="form-control" value="Active Investigation" required>
                </div>
            </div>

            <div class="form-group">
                <label for="domain" class="form-label">Domain / Field *</label>
                <input type="text" id="domain" name="domain" class="form-control" value="Deep Learning & Biomedical Imaging" required>
            </div>

            <div class="form-group">
                <label for="summary" class="form-label">Research Summary *</label>
                <textarea id="summary" name="summary" class="form-control" rows="3" required placeholder="Methodology, models used, and core findings."></textarea>
            </div>

            <div class="form-group">
                <label for="technologies_text" class="form-label">Technologies (Comma-separated)</label>
                <input type="text" id="technologies_text" name="technologies_text" class="form-control" placeholder="Python, TensorFlow, OpenCV, Keras">
            </div>

            <div class="form-group">
                <label for="link" class="form-label">Paper / Project Link</label>
                <input type="url" id="link" name="link" class="form-control" placeholder="https://arxiv.org/...">
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;">Save Research Topic</button>
        </form>
    </div>

    <!-- Existing Research Items List -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Research Topics ({{ $researchItems->count() }})</h2>
        </div>

        @if($researchItems->isEmpty())
            <p style="color:var(--text-muted); font-size:0.88rem;">No research topics listed yet.</p>
        @else
            @foreach($researchItems as $item)
                <div style="padding:14px; background:rgba(255,255,255,0.02); border:1px solid var(--border-color); border-radius:var(--radius-md); margin-bottom:12px;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:10px;">
                        <div>
                            <div style="font-weight:700; font-size:0.95rem;">{{ $item->title }}</div>
                            <div style="font-size:0.75rem; color:var(--accent-blue); margin-top:2px;">{{ $item->type }} &middot; {{ $item->domain }}</div>
                            <p style="font-size:0.82rem; color:var(--text-secondary); margin-top:6px;">{{ $item->summary }}</p>
                        </div>
                        <form action="{{ route('admin.research.item.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete this research item?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">&times;</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>

<!-- Highlights & Achievements Row -->
<div class="form-grid-2" style="align-items:flex-start;">
    <!-- Add Highlight Form -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">+ Add Career Highlight / Milestone</h2>
        </div>

        <form action="{{ route('admin.research.achievement.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="ach_title" class="form-label">Headline / Value *</label>
                <input type="text" id="ach_title" name="title" class="form-control" required placeholder="DoctorQR Platform or B.Sc. CSE Final Year">
            </div>

            <div class="form-grid-2">
                <div class="form-group">
                    <label for="ach_category" class="form-label">Label / Category *</label>
                    <input type="text" id="ach_category" name="category" class="form-control" required placeholder="Flagship Delivery or Academic Standing">
                </div>

                <div class="form-group">
                    <label for="ach_year" class="form-label">Year</label>
                    <input type="text" id="ach_year" name="year" class="form-control" value="{{ date('Y') }}">
                </div>
            </div>

            <div class="form-group">
                <label for="ach_desc" class="form-label">Supporting Detail / Evidence</label>
                <textarea id="ach_desc" name="description" class="form-control" rows="2" placeholder="Brief verifiable context or milestone description."></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;">Add Milestone</button>
        </form>
    </div>

    <!-- Highlights List -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Existing Highlights ({{ $achievements->count() }})</h2>
        </div>

        @if($achievements->isEmpty())
            <p style="color:var(--text-muted); font-size:0.88rem;">No highlights added yet.</p>
        @else
            @foreach($achievements as $ach)
                <div style="padding:12px; background:rgba(255,255,255,0.02); border:1px solid var(--border-color); border-radius:var(--radius-md); margin-bottom:10px; display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <div style="font-size:0.75rem; text-transform:uppercase; color:var(--text-muted); font-weight:700;">{{ $ach->category }}</div>
                        <div style="font-weight:700; font-size:0.95rem; color:var(--text-primary);">{{ $ach->title }}</div>
                        <div style="font-size:0.8rem; color:var(--text-secondary);">{{ $ach->description }}</div>
                    </div>
                    <form action="{{ route('admin.research.achievement.destroy', $ach->id) }}" method="POST" onsubmit="return confirm('Delete milestone?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">&times;</button>
                    </form>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
