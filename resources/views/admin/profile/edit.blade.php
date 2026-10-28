@extends('admin.layouts.admin')

@section('title', 'Profile & Brand')
@section('page_title', 'Personal Brand & Profile Configuration')

@section('content')
<div class="card">
    <div class="card-header">
        <h2 class="card-title">Edit Identity, Bios &amp; Contact Details</h2>
        <span style="font-size:0.8rem; color:var(--text-muted);">Changes instantly update the public portfolio website</span>
    </div>

    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Photo Avatar Section -->
        <div style="display:flex; align-items:center; gap:24px; padding:20px; background:rgba(255,255,255,0.02); border:1px solid var(--border-color); border-radius:var(--radius-md); margin-bottom:24px;">
            <div style="width:84px; height:84px; border-radius:50%; overflow:hidden; border:2px solid var(--accent-blue); flex-shrink:0;">
                <img id="avatarPreview" src="{{ asset($profile->profile_image) }}" alt="{{ $profile->full_name }}" style="width:100%; height:100%; object-fit:cover;">
            </div>
            <div>
                <label for="profile_image" class="form-label" style="font-size:0.95rem; font-weight:700;">Replace Portrait Photo</label>
                <p style="font-size:0.82rem; color:var(--text-muted); margin-bottom:10px;">
                    Used in Navbar brand box, Hero portrait overlay, and OpenGraph social preview tags. Supports JPEG, PNG, WEBP, AVIF (Max 20MB).
                </p>
                <input type="file" id="profile_image" name="profile_image" accept="image/*" class="form-control" style="padding:6px 12px; font-size:0.82rem;" onchange="previewImage(this)">
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label for="full_name" class="form-label">Full Name *</label>
                <input type="text" id="full_name" name="full_name" class="form-control" value="{{ old('full_name', $profile->full_name) }}" required>
            </div>

            <div class="form-group">
                <label for="short_name" class="form-label">Brand Monogram / Short Name *</label>
                <input type="text" id="short_name" name="short_name" class="form-control" value="{{ old('short_name', $profile->short_name) }}" required placeholder="IFAN.">
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label for="title" class="form-label">Professional Designation *</label>
                <input type="text" id="title" name="title" class="form-control" value="{{ old('title', $profile->title) }}" required placeholder="Junior Software Engineer">
            </div>

            <div class="form-group">
                <label for="sub_title" class="form-label">Sub-Title / Engineering Focus</label>
                <input type="text" id="sub_title" name="sub_title" class="form-control" value="{{ old('sub_title', $profile->sub_title) }}" placeholder="Web Application Development | Backend Engineering">
            </div>
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label for="email" class="form-label">Primary Public Email *</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $profile->email) }}" required>
            </div>

            <div class="form-group">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" id="phone" name="phone" class="form-control" value="{{ old('phone', $profile->phone) }}" placeholder="+8801608564447">
            </div>
        </div>

        <div class="form-group">
            <label for="location" class="form-label">Base Location</label>
            <input type="text" id="location" name="location" class="form-control" value="{{ old('location', $profile->location) }}" placeholder="Mirpur-2, Dhaka, Bangladesh">
        </div>

        <div class="form-grid-2">
            <div class="form-group">
                <label for="hero_headline" class="form-label">Hero Headline</label>
                <input type="text" id="hero_headline" name="hero_headline" class="form-control" value="{{ old('hero_headline', $profile->hero_headline) }}">
            </div>

            <div class="form-group">
                <label for="hero_subheadline" class="form-label">Hero Subheadline</label>
                <input type="text" id="hero_subheadline" name="hero_subheadline" class="form-control" value="{{ old('hero_subheadline', $profile->hero_subheadline) }}">
            </div>
        </div>

        <div class="form-group">
            <label for="short_bio" class="form-label">Short Bio (Hero section summary)</label>
            <textarea id="short_bio" name="short_bio" class="form-control" rows="3">{{ old('short_bio', $profile->short_bio) }}</textarea>
        </div>

        <div class="form-group">
            <label for="detailed_bio_text" class="form-label">Detailed Bio (About section paragraphs — separate with empty line)</label>
            <textarea id="detailed_bio_text" name="detailed_bio_text" class="form-control" rows="6">{{ old('detailed_bio_text', is_array($profile->detailed_bio) ? implode("\n\n", $profile->detailed_bio) : $profile->detailed_bio) }}</textarea>
        </div>

        <div class="form-group">
            <label for="positioning" class="form-label">Strategic Career Positioning Statement</label>
            <textarea id="positioning" name="positioning" class="form-control" rows="2">{{ old('positioning', $profile->positioning) }}</textarea>
        </div>

        <div style="display:flex; justify-content:flex-end; gap:12px; margin-top:20px;">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Save Profile Changes</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatarPreview').src = e.target.result;
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endpush
