@extends('admin.layouts.admin')

@section('title', 'Technical Skills')
@section('page_title', 'Skills & Technical Proficiencies')

@section('content')
<div class="form-grid-2" style="align-items:flex-start;">
    <!-- Add New Skill Form -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">+ Add Technical Skill</h2>
        </div>

        <form action="{{ route('admin.skills.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Skill Name *</label>
                <input type="text" id="name" name="name" class="form-control" required placeholder="e.g. Docker, Redis, Tailwind CSS">
            </div>

            <div class="form-group">
                <label for="category" class="form-label">Category *</label>
                <select id="category" name="category" class="form-control" required>
                    @foreach($categories as $cat)
                        <option value="{{ $cat }}">{{ $cat }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="proficiency" class="form-label">Proficiency Level: <span id="profVal">85</span>%</label>
                <input type="range" id="proficiency" name="proficiency" min="10" max="100" value="85" class="form-control" style="padding:0; accent-color:var(--accent-blue);" oninput="document.getElementById('profVal').textContent = this.value">
            </div>

            <div class="form-group">
                <label style="display:flex; align-items:center; gap:8px; cursor:pointer;">
                    <input type="checkbox" name="is_featured" value="1" checked style="accent-color:var(--accent-blue);">
                    <span style="font-size:0.88rem;">Display in Featured Skills List</span>
                </label>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%;">Add Skill</button>
        </form>
    </div>

    <!-- Skills List Grouped by Category -->
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Existing Skills ({{ $skills->count() }})</h2>
        </div>

        @if($skills->isEmpty())
            <p style="color:var(--text-muted); font-size:0.88rem;">No skills added yet.</p>
        @else
            @php $grouped = $skills->groupBy('category'); @endphp
            @foreach($grouped as $categoryName => $catSkills)
                <div style="margin-bottom:20px;">
                    <div style="font-size:0.8rem; font-weight:700; text-transform:uppercase; color:var(--accent-blue); margin-bottom:8px; border-bottom:1px solid rgba(255,255,255,0.06); padding-bottom:4px;">
                        {{ $categoryName }} ({{ $catSkills->count() }})
                    </div>
                    <div style="display:flex; flex-wrap:wrap; gap:8px;">
                        @foreach($catSkills as $skill)
                            <div style="display:inline-flex; align-items:center; gap:8px; background:rgba(255,255,255,0.04); border:1px solid var(--border-color); border-radius:var(--radius-sm); padding:4px 10px; font-size:0.82rem;">
                                <span>{{ $skill->name }}</span>
                                <span style="font-size:0.7rem; color:var(--text-muted);">{{ $skill->proficiency }}%</span>
                                <form action="{{ route('admin.skills.destroy', $skill->id) }}" method="POST" onsubmit="return confirm('Delete skill {{ $skill->name }}?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background:none; border:none; color:var(--text-muted); cursor:pointer; padding:0; line-height:1;" title="Delete">
                                        &times;
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection
