@auth
{{-- Toast Notification --}}
<div id="adminToast" class="admin-toast" role="alert" aria-live="assertive">
    <div class="admin-toast-content">
        <span class="admin-toast-icon">✓</span>
        <span class="admin-toast-msg" id="adminToastMsg">Success</span>
    </div>
</div>

{{-- MODAL BACKDROP --}}
<div id="adminModalBackdrop" class="admin-modal-backdrop" onclick="closeAllAdminModals()"></div>

{{-- 1. PROFILE INFO MODAL --}}
<div id="profileInfoModal" class="admin-modal" role="dialog" aria-modal="true" aria-labelledby="profileModalTitle">
    <div class="admin-modal-header">
        <h3 id="profileModalTitle" class="admin-modal-title">Edit Profile &amp; Identity</h3>
        <button type="button" class="admin-modal-close" onclick="closeAdminModal('profileInfoModal')">&times;</button>
    </div>
    <form id="profileInfoForm" onsubmit="handleProfileUpdate(event)">
        @csrf
        <div class="admin-modal-body">
            <div class="admin-form-row">
                <div class="admin-form-group">
                    <label class="admin-label">Full Name *</label>
                    <input type="text" name="full_name" class="admin-input" value="{{ $portfolio['brand']['full_name'] }}" required>
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Short Name / Brand Monogram *</label>
                    <input type="text" name="short_name" class="admin-input" value="{{ $portfolio['brand']['short_name'] }}" required placeholder="IFAN.">
                </div>
            </div>

            <div class="admin-form-row">
                <div class="admin-form-group">
                    <label class="admin-label">Professional Designation *</label>
                    <input type="text" name="title" class="admin-input" value="{{ $portfolio['brand']['title'] }}" required>
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Engineering Focus / Sub-Title</label>
                    <input type="text" name="sub_title" class="admin-input" value="{{ $portfolio['brand']['sub_title'] }}">
                </div>
            </div>

            <div class="admin-form-row">
                <div class="admin-form-group">
                    <label class="admin-label">Email *</label>
                    <input type="email" name="email" class="admin-input" value="{{ $portfolio['brand']['email'] }}" required>
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Phone</label>
                    <input type="text" name="phone" class="admin-input" value="{{ $portfolio['brand']['phone'] }}">
                </div>
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Location</label>
                <input type="text" name="location" class="admin-input" value="{{ $portfolio['brand']['location'] }}">
            </div>

            <div class="admin-form-row">
                <div class="admin-form-group">
                    <label class="admin-label">Hero Headline</label>
                    <input type="text" name="hero_headline" class="admin-input" value="{{ $portfolio['brand']['hero_headline'] }}">
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Hero Sub-Headline</label>
                    <input type="text" name="hero_subheadline" class="admin-input" value="{{ $portfolio['brand']['hero_subheadline'] }}">
                </div>
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Short Bio (Introductory Paragraph)</label>
                <textarea name="short_bio" class="admin-textarea" rows="3">{{ $portfolio['brand']['short_bio'] }}</textarea>
            </div>
        </div>
        <div class="admin-modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" onclick="closeAdminModal('profileInfoModal')">Cancel</button>
            <button type="submit" class="btn btn-primary btn-sm">Save Changes</button>
        </div>
    </form>
</div>

{{-- 2. PROFILE PHOTO & AUTOMATIC IMAGE FITTING MODAL --}}
<div id="profilePhotoModal" class="admin-modal" role="dialog" aria-modal="true" aria-labelledby="photoModalTitle">
    <div class="admin-modal-header">
        <h3 id="photoModalTitle" class="admin-modal-title">Profile Picture &amp; Automatic Fitting</h3>
        <button type="button" class="admin-modal-close" onclick="closeAdminModal('profilePhotoModal')">&times;</button>
    </div>
    <form id="profilePhotoForm" onsubmit="handlePhotoUpload(event)">
        @csrf
        <div class="admin-modal-body">
            <p style="font-size:0.85rem; color:var(--text-secondary); margin-bottom:16px;">
                Upload a portrait photo. The system maintains the original aspect ratio with automatic cropping, responsive scaling, and instant live preview.
            </p>

            <div class="admin-form-group">
                <label class="admin-label">Select Photo (JPEG, PNG, WEBP &middot; Max 5MB)</label>
                <input type="file" id="photoFileInput" name="profile_image" accept="image/*" class="admin-file-input" onchange="previewSelectedPhoto(this)" required>
            </div>

            {{-- Live Fit Dual Preview --}}
            <div class="photo-preview-grid">
                <div>
                    <div class="preview-heading">Portrait Frame Preview (4:5 Hero Card)</div>
                    <div class="photo-preview-box portrait-box">
                        <img id="livePreviewPortrait" src="{{ asset($portfolio['brand']['profile_image']) }}?v={{ time() }}" alt="Preview" class="preview-img-fit">
                    </div>
                </div>

                <div>
                    <div class="preview-heading">Brand Monogram Preview (1:1 Circle)</div>
                    <div class="photo-preview-box circle-box">
                        <img id="livePreviewCircle" src="{{ asset($portfolio['brand']['profile_image']) }}?v={{ time() }}" alt="Preview" class="preview-img-fit">
                    </div>
                </div>
            </div>

            <div class="admin-form-group" style="margin-top:16px;">
                <label class="admin-label">Vertical Position Alignment</label>
                <select id="photoPositionSelect" class="admin-select" onchange="adjustPreviewPosition(this.value)">
                    <option value="center top" selected>Center Top (Recommended for portraits - head remains visible)</option>
                    <option value="center 20%">Center 20%</option>
                    <option value="center center">Center Center</option>
                    <option value="center bottom">Center Bottom</option>
                </select>
                <span style="font-size:0.75rem; color:var(--text-muted); display:block; margin-top:4px;">
                    Ensures your face and eyes are centered nicely regardless of screen width.
                </span>
            </div>
        </div>
        <div class="admin-modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" onclick="closeAdminModal('profilePhotoModal')">Cancel</button>
            <button type="submit" id="photoSaveBtn" class="btn btn-primary btn-sm">Save &amp; Apply Photo</button>
        </div>
    </form>
</div>

{{-- 3. ABOUT ME BIO PARAGRAPHS MODAL --}}
<div id="aboutBioModal" class="admin-modal admin-modal-lg" role="dialog" aria-modal="true" aria-labelledby="aboutModalTitle">
    <div class="admin-modal-header">
        <h3 id="aboutModalTitle" class="admin-modal-title">Edit About Me Bio Paragraphs</h3>
        <button type="button" class="admin-modal-close" onclick="closeAdminModal('aboutBioModal')">&times;</button>
    </div>
    <form id="aboutBioForm" onsubmit="handleAboutBioSave(event)">
        @csrf
        <div class="admin-modal-body">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:14px; flex-wrap:wrap; gap:10px;">
                <p style="font-size:0.85rem; color:var(--text-secondary); margin:0;">
                    Manage bio paragraphs. You can edit existing text, add new paragraphs, or remove paragraphs.
                </p>
                <button type="button" class="btn btn-secondary btn-xs" onclick="restoreAuthenticBio()" style="color:#F59E0B; border-color:rgba(245,158,11,0.4);" title="Restore original authentic 4 paragraphs from config">
                    ↺ Restore Authentic Bio
                </button>
            </div>

            <div id="bioParagraphsContainer" class="bio-paragraphs-list">
                @foreach($portfolio['brand']['detailed_bio'] as $index => $paragraph)
                    <div class="bio-para-card" data-index="{{ $index }}">
                        <div class="bio-para-header">
                            <span class="bio-para-num">Paragraph {{ $index + 1 }}</span>
                            <button type="button" class="bio-para-del-btn" onclick="removeBioParagraph(this)" title="Delete paragraph">&times; Delete</button>
                        </div>
                        <textarea name="paragraphs[]" class="admin-textarea" rows="3" required>{{ $paragraph }}</textarea>
                    </div>
                @endforeach
            </div>

            <button type="button" class="btn btn-secondary btn-sm" onclick="addBioParagraph()" style="margin-top:12px; width:100%; border-style:dashed;">
                + Add New Paragraph
            </button>
        </div>
        <div class="admin-modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" onclick="closeAdminModal('aboutBioModal')">Cancel</button>
            <button type="submit" class="btn btn-primary btn-sm">Save Bio Paragraphs</button>
        </div>
    </form>
</div>

{{-- 4. SKILL MODAL (ADD / EDIT) --}}
<div id="skillModal" class="admin-modal" role="dialog" aria-modal="true" aria-labelledby="skillModalTitle">
    <div class="admin-modal-header">
        <h3 id="skillModalTitle" class="admin-modal-title">Add / Edit Skill</h3>
        <button type="button" class="admin-modal-close" onclick="closeAdminModal('skillModal')">&times;</button>
    </div>
    <form id="skillForm" onsubmit="handleSkillSave(event)">
        @csrf
        <input type="hidden" id="skillId" name="skill_id" value="">
        <input type="hidden" id="skillMethod" name="_method" value="POST">
        <div class="admin-modal-body">
            <div class="admin-form-group">
                <label class="admin-label">Skill Name *</label>
                <input type="text" id="skillName" name="name" class="admin-input" required placeholder="e.g. Laravel &amp; PHP">
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Category *</label>
                <select id="skillCategory" name="category" class="admin-select" required>
                    <option value="Backend &amp; Web Development">Backend &amp; Web Development</option>
                    <option value="Mobile Development">Mobile Development</option>
                    <option value="Databases &amp; Storage">Databases &amp; Storage</option>
                    <option value="Programming Languages">Programming Languages</option>
                    <option value="Integrations &amp; External APIs">Integrations &amp; External APIs</option>
                    <option value="Tools &amp; Development Environment">Tools &amp; Development Environment</option>
                    <option value="Engineering Practices">Engineering Practices</option>
                    <option value="AI &amp; Computer Vision">AI &amp; Computer Vision</option>
                </select>
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Proficiency Level (<span id="profVal">85</span>%)</label>
                <input type="range" id="skillProficiency" name="proficiency" min="1" max="100" value="85" class="admin-range" oninput="document.getElementById('profVal').textContent = this.value;">
            </div>

            <div class="admin-form-group">
                <label class="admin-checkbox-label">
                    <input type="checkbox" id="skillFeatured" name="is_featured" value="1" checked>
                    <span>Feature this skill in portfolio competencies</span>
                </label>
            </div>
        </div>
        <div class="admin-modal-footer">
            <button type="button" id="skillDeleteBtn" class="btn btn-danger btn-sm" onclick="handleSkillDelete()" style="display:none; margin-right:auto;">Delete Skill</button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="closeAdminModal('skillModal')">Cancel</button>
            <button type="submit" class="btn btn-primary btn-sm">Save Skill</button>
        </div>
    </form>
</div>

{{-- 5. PROJECT MODAL (ADD / EDIT) --}}
<div id="projectModal" class="admin-modal admin-modal-lg" role="dialog" aria-modal="true" aria-labelledby="projectModalTitle">
    <div class="admin-modal-header">
        <h3 id="projectModalTitle" class="admin-modal-title">Add / Edit Project</h3>
        <button type="button" class="admin-modal-close" onclick="closeAdminModal('projectModal')">&times;</button>
    </div>
    <form id="projectForm" onsubmit="handleProjectSave(event)" enctype="multipart/form-data">
        @csrf
        <input type="hidden" id="projectId" name="project_id" value="">
        <input type="hidden" id="projectMethod" name="_method" value="POST">
        <div class="admin-modal-body">
            <div class="admin-form-row">
                <div class="admin-form-group">
                    <label class="admin-label">Project Title *</label>
                    <input type="text" id="projectTitle" name="title" class="admin-input" required placeholder="DoctorQR">
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">URL Slug</label>
                    <input type="text" id="projectSlug" name="slug" class="admin-input" placeholder="doctor-qr">
                </div>
            </div>

            <div class="admin-form-row">
                <div class="admin-form-group">
                    <label class="admin-label">Category *</label>
                    <select id="projectCategory" name="category" class="admin-select" required>
                        <option value="professional">Professional / Production SaaS</option>
                        <option value="web">Web Application</option>
                        <option value="mobile">Mobile Application</option>
                        <option value="desktop">Desktop Software</option>
                        <option value="ai">AI &amp; Computer Vision</option>
                    </select>
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Role / Tagline</label>
                    <input type="text" id="projectTagline" name="tagline" class="admin-input" placeholder="e.g. Lead Developer &middot; Full Lifecycle">
                </div>
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Summary *</label>
                <textarea id="projectSummary" name="summary" class="admin-textarea" rows="2" required placeholder="Brief high-level summary of the system"></textarea>
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Detailed Description / Contributions</label>
                <textarea id="projectDescription" name="description" class="admin-textarea" rows="3" placeholder="Key technical challenges solved, workflows implemented, and modules engineered..."></textarea>
            </div>

            <div class="admin-form-row">
                <div class="admin-form-group">
                    <label class="admin-label">Technologies (Comma-separated)</label>
                    <input type="text" id="projectTech" name="technologies_text" class="admin-input" placeholder="Laravel, MySQL, REST API, Tailwind CSS">
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Live Practice / Demo URL</label>
                    <input type="url" id="projectLiveUrl" name="live_url" class="admin-input" placeholder="https://doctorqr.net">
                </div>
            </div>

            <div class="admin-form-row">
                <div class="admin-form-group">
                    <label class="admin-label">GitHub Repository URL</label>
                    <input type="url" id="projectGithubUrl" name="github_url" class="admin-input" placeholder="https://github.com/...">
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Thumbnail Image</label>
                    <input type="file" id="projectThumbFile" name="thumbnail_image" accept="image/*" class="admin-file-input">
                </div>
            </div>

            <div class="admin-form-group">
                <label class="admin-checkbox-label">
                    <input type="checkbox" id="projectFeatured" name="is_featured" value="1">
                    <span>Feature this as Flagship / Primary Project</span>
                </label>
            </div>
        </div>
        <div class="admin-modal-footer">
            <button type="button" id="projectDeleteBtn" class="btn btn-danger btn-sm" onclick="handleProjectDelete()" style="display:none; margin-right:auto;">Delete Project</button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="closeAdminModal('projectModal')">Cancel</button>
            <button type="submit" class="btn btn-primary btn-sm">Save Project</button>
        </div>
    </form>
</div>

{{-- 6. EXPERIENCE MODAL (ADD / EDIT) --}}
<div id="experienceModal" class="admin-modal admin-modal-lg" role="dialog" aria-modal="true" aria-labelledby="expModalTitle">
    <div class="admin-modal-header">
        <h3 id="expModalTitle" class="admin-modal-title">Add / Edit Experience</h3>
        <button type="button" class="admin-modal-close" onclick="closeAdminModal('experienceModal')">&times;</button>
    </div>
    <form id="experienceForm" onsubmit="handleExperienceSave(event)">
        @csrf
        <input type="hidden" id="expId" name="exp_id" value="">
        <input type="hidden" id="expMethod" name="_method" value="POST">
        <div class="admin-modal-body">
            <div class="admin-form-row">
                <div class="admin-form-group">
                    <label class="admin-label">Company Name *</label>
                    <input type="text" id="expCompany" name="company" class="admin-input" required placeholder="APPIFLY BD LIMITED">
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Location</label>
                    <input type="text" id="expLocation" name="location" class="admin-input" placeholder="Dhaka, Bangladesh">
                </div>
            </div>

            <div class="admin-form-row">
                <div class="admin-form-group">
                    <label class="admin-label">Designation *</label>
                    <input type="text" id="expDesignation" name="designation" class="admin-input" required placeholder="Junior Software Engineer">
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Period *</label>
                    <input type="text" id="expPeriod" name="period" class="admin-input" required placeholder="Nov 2025 – Present">
                </div>
            </div>

            <div class="admin-form-row">
                <div class="admin-form-group">
                    <label class="admin-label">Employment Type *</label>
                    <input type="text" id="expType" name="type" class="admin-input" required placeholder="Full-time">
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Timeline Label</label>
                    <input type="text" id="expTimelineLabel" name="timeline_label" class="admin-input" placeholder="Nov 2025 – Present">
                </div>
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Role Summary</label>
                <textarea id="expSummary" name="summary" class="admin-textarea" rows="2"></textarea>
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Key Highlights &amp; Contributions (One per line)</label>
                <textarea id="expHighlights" name="highlights_text" class="admin-textarea" rows="3" placeholder="Engineered DoctorQR platform...&#10;Integrated SSLCommerz payments..."></textarea>
            </div>

            <div class="admin-form-group">
                <label class="admin-checkbox-label">
                    <input type="checkbox" id="expIsCurrent" name="is_current" value="1" checked>
                    <span>This is my current employer</span>
                </label>
            </div>
        </div>
        <div class="admin-modal-footer">
            <button type="button" id="expDeleteBtn" class="btn btn-danger btn-sm" onclick="handleExperienceDelete()" style="display:none; margin-right:auto;">Delete Record</button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="closeAdminModal('experienceModal')">Cancel</button>
            <button type="submit" class="btn btn-primary btn-sm">Save Experience</button>
        </div>
    </form>
</div>

{{-- 7. EDUCATION MODAL (ADD / EDIT) --}}
<div id="educationModal" class="admin-modal" role="dialog" aria-modal="true" aria-labelledby="eduModalTitle">
    <div class="admin-modal-header">
        <h3 id="eduModalTitle" class="admin-modal-title">Add / Edit Education</h3>
        <button type="button" class="admin-modal-close" onclick="closeAdminModal('educationModal')">&times;</button>
    </div>
    <form id="educationForm" onsubmit="handleEducationSave(event)">
        @csrf
        <input type="hidden" id="eduId" name="edu_id" value="">
        <input type="hidden" id="eduMethod" name="_method" value="POST">
        <div class="admin-modal-body">
            <div class="admin-form-group">
                <label class="admin-label">Institution *</label>
                <input type="text" id="eduInstitution" name="institution" class="admin-input" required placeholder="Bangladesh University of Business and Technology (BUBT)">
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Degree *</label>
                <input type="text" id="eduDegree" name="degree" class="admin-input" required placeholder="B.Sc. in Computer Science and Engineering">
            </div>

            <div class="admin-form-row">
                <div class="admin-form-group">
                    <label class="admin-label">Period / Graduation Year *</label>
                    <input type="text" id="eduPeriod" name="period" class="admin-input" required placeholder="2022 – 2026 (Expected)">
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Status *</label>
                    <input type="text" id="eduStatus" name="status" class="admin-input" required placeholder="Final Year Student (8th Semester)">
                </div>
            </div>

            <div class="admin-form-row">
                <div class="admin-form-group">
                    <label class="admin-label">CGPA / GPA</label>
                    <input type="text" id="eduCgpa" name="cgpa" class="admin-input" placeholder="3.50">
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Scale</label>
                    <input type="text" id="eduScale" name="scale" class="admin-input" placeholder="4.00">
                </div>
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Key Coursework (Comma-separated)</label>
                <input type="text" id="eduCoursework" name="coursework_text" class="admin-input" placeholder="Data Structures, Algorithms, Database Systems, Web Engineering">
            </div>
        </div>
        <div class="admin-modal-footer">
            <button type="button" id="eduDeleteBtn" class="btn btn-danger btn-sm" onclick="handleEducationDelete()" style="display:none; margin-right:auto;">Delete Record</button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="closeAdminModal('educationModal')">Cancel</button>
            <button type="submit" class="btn btn-primary btn-sm">Save Education</button>
        </div>
    </form>
</div>

{{-- 8. RESEARCH & HIGHLIGHTS MODAL --}}
<div id="researchModal" class="admin-modal" role="dialog" aria-modal="true" aria-labelledby="resModalTitle">
    <div class="admin-modal-header">
        <h3 id="resModalTitle" class="admin-modal-title">Add Research Topic / Highlight</h3>
        <button type="button" class="admin-modal-close" onclick="closeAdminModal('researchModal')">&times;</button>
    </div>
    <form id="researchForm" onsubmit="handleResearchSave(event)">
        @csrf
        <input type="hidden" id="resId" name="res_id" value="">
        <div class="admin-modal-body">
            <div class="admin-form-group">
                <label class="admin-label">Item Type *</label>
                <select id="resTypeChoice" class="admin-select" onchange="toggleResearchFields(this.value)">
                    <option value="research">Research Interest / Capstone Topic</option>
                    <option value="achievement">Career / Academic Highlight</option>
                </select>
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Title *</label>
                <input type="text" id="resTitle" name="title" class="admin-input" required placeholder="Deep Learning for Biomedical Image Analysis">
            </div>

            <div id="researchFieldsBlock">
                <div class="admin-form-row">
                    <div class="admin-form-group">
                        <label class="admin-label">Domain *</label>
                        <input type="text" id="resDomain" name="domain" class="admin-input" value="Computer Vision" placeholder="Computer Vision">
                    </div>
                    <div class="admin-form-group">
                        <label class="admin-label">Status *</label>
                        <input type="text" id="resStatus" name="status" class="admin-input" value="Capstone Project / Active Exploration" placeholder="Active Exploration">
                    </div>
                </div>

                <div class="admin-form-group">
                    <label class="admin-label">Summary *</label>
                    <textarea id="resSummary" name="summary" class="admin-textarea" rows="2" placeholder="Investigation of deep convolutional networks..."></textarea>
                </div>
            </div>

            <div id="achievementFieldsBlock" style="display:none;">
                <div class="admin-form-group">
                    <label class="admin-label">Category *</label>
                    <input type="text" id="achCategory" name="category" class="admin-input" value="Production Impact" placeholder="Production Impact">
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Description</label>
                    <textarea id="achDesc" name="description" class="admin-textarea" rows="2" placeholder="Details of this achievement..."></textarea>
                </div>
            </div>
        </div>
        <div class="admin-modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" onclick="closeAdminModal('researchModal')">Cancel</button>
            <button type="submit" class="btn btn-primary btn-sm">Save Item</button>
        </div>
    </form>
</div>

{{-- 9. BLOG POST MODAL --}}
<div id="blogModal" class="admin-modal admin-modal-lg" role="dialog" aria-modal="true" aria-labelledby="blogModalTitle">
    <div class="admin-modal-header">
        <h3 id="blogModalTitle" class="admin-modal-title">Write / Edit Blog Article</h3>
        <button type="button" class="admin-modal-close" onclick="closeAdminModal('blogModal')">&times;</button>
    </div>
    <form id="blogForm" onsubmit="handleBlogSave(event)">
        @csrf
        <input type="hidden" id="blogId" name="blog_id" value="">
        <input type="hidden" id="blogMethod" name="_method" value="POST">
        <div class="admin-modal-body">
            <div class="admin-form-row">
                <div class="admin-form-group">
                    <label class="admin-label">Article Title *</label>
                    <input type="text" id="blogTitle" name="title" class="admin-input" required placeholder="Architecting DoctorQR: Lessons in Production Web Development">
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Slug</label>
                    <input type="text" id="blogSlug" name="slug" class="admin-input" placeholder="architecting-doctorqr">
                </div>
            </div>

            <div class="admin-form-row">
                <div class="admin-form-group">
                    <label class="admin-label">Tags (Comma-separated)</label>
                    <input type="text" id="blogTags" name="tags_text" class="admin-input" placeholder="Laravel, Architecture, Engineering">
                </div>
                <div class="admin-form-group">
                    <label class="admin-label">Status *</label>
                    <select id="blogStatus" name="status" class="admin-select">
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                    </select>
                </div>
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Excerpt / Teaser</label>
                <textarea id="blogExcerpt" name="excerpt" class="admin-textarea" rows="2" placeholder="Brief summary for social and listing cards..."></textarea>
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Content (Markdown / Text) *</label>
                <textarea id="blogContent" name="content" class="admin-textarea" rows="6" required placeholder="Full technical article content..."></textarea>
            </div>
        </div>
        <div class="admin-modal-footer">
            <button type="button" id="blogDeleteBtn" class="btn btn-danger btn-sm" onclick="handleBlogDelete()" style="display:none; margin-right:auto;">Delete Post</button>
            <button type="button" class="btn btn-secondary btn-sm" onclick="closeAdminModal('blogModal')">Cancel</button>
            <button type="submit" class="btn btn-primary btn-sm">Save Article</button>
        </div>
    </form>
</div>

{{-- 10. RESUME / CV MODAL --}}
<div id="resumeModal" class="admin-modal" role="dialog" aria-modal="true" aria-labelledby="resumeModalTitle">
    <div class="admin-modal-header">
        <h3 id="resumeModalTitle" class="admin-modal-title">Manage Resume &amp; CV</h3>
        <button type="button" class="admin-modal-close" onclick="closeAdminModal('resumeModal')">&times;</button>
    </div>
    <form id="resumeForm" onsubmit="handleResumeSave(event)" enctype="multipart/form-data">
        @csrf
        <div class="admin-modal-body">
            <p style="font-size:0.85rem; color:var(--text-secondary); margin-bottom:16px;">
                Upload your updated PDF resume. The public "Download CV" button serves this PDF directly in inline preview mode.
            </p>

            <div class="admin-form-group">
                <label class="admin-label">Upload New PDF (Max 10MB)</label>
                <input type="file" name="resume_pdf" accept="application/pdf" class="admin-file-input">
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Status Note / Subtitle</label>
                <input type="text" name="resume_status_note" class="admin-input" value="{{ $portfolio['resume']['status_note'] }}">
            </div>

            <div class="admin-form-group">
                <label class="admin-checkbox-label">
                    <input type="checkbox" name="resume_available" value="1" {{ $portfolio['resume']['is_available'] ? 'checked' : '' }}>
                    <span>CV download is active and accessible on portfolio</span>
                </label>
            </div>
        </div>
        <div class="admin-modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" onclick="closeAdminModal('resumeModal')">Cancel</button>
            <button type="submit" class="btn btn-primary btn-sm">Save Resume Settings</button>
        </div>
    </form>
</div>

{{-- 11. SOCIAL LINKS MODAL --}}
<div id="socialsModal" class="admin-modal" role="dialog" aria-modal="true" aria-labelledby="socialsModalTitle">
    <div class="admin-modal-header">
        <h3 id="socialsModalTitle" class="admin-modal-title">Manage Social Links</h3>
        <button type="button" class="admin-modal-close" onclick="closeAdminModal('socialsModal')">&times;</button>
    </div>
    <form id="socialsForm" onsubmit="handleSocialsSave(event)">
        @csrf
        <div class="admin-modal-body">
            <div class="admin-form-group">
                <label class="admin-label">GitHub URL</label>
                <input type="url" name="socials[github][url]" class="admin-input" value="{{ $portfolio['socials']['github']['url'] ?? '' }}" required>
            </div>

            <div class="admin-form-group">
                <label class="admin-label">LinkedIn URL</label>
                <input type="url" name="socials[linkedin][url]" class="admin-input" value="{{ $portfolio['socials']['linkedin']['url'] ?? '' }}" required>
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Kaggle URL</label>
                <input type="url" name="socials[kaggle][url]" class="admin-input" value="{{ $portfolio['socials']['kaggle']['url'] ?? '' }}" required>
            </div>

            <div class="admin-form-group">
                <label class="admin-label">Direct Contact Email (mailto:)</label>
                <input type="text" name="socials[email][url]" class="admin-input" value="{{ $portfolio['socials']['email']['url'] ?? '' }}" required>
            </div>
        </div>
        <div class="admin-modal-footer">
            <button type="button" class="btn btn-secondary btn-sm" onclick="closeAdminModal('socialsModal')">Cancel</button>
            <button type="submit" class="btn btn-primary btn-sm">Save Social Links</button>
        </div>
    </form>
</div>

{{-- 12. SECTION VISIBILITY DRAWER --}}
<div id="sectionVisibilityModal" class="admin-modal" role="dialog" aria-modal="true" aria-labelledby="secModalTitle">
    <div class="admin-modal-header">
        <h3 id="secModalTitle" class="admin-modal-title">Portfolio Section Visibility</h3>
        <button type="button" class="admin-modal-close" onclick="closeAdminModal('sectionVisibilityModal')">&times;</button>
    </div>
    <div class="admin-modal-body">
        <p style="font-size:0.85rem; color:var(--text-secondary); margin-bottom:14px;">
            Turn sections on or off instantly. Switches take effect immediately on public visitor pages.
        </p>

        <div class="sec-toggle-list">
            @php
                $sections = [
                    'show_hero' => 'Hero & Profile Intro',
                    'show_about' => 'About Me Section',
                    'show_experience' => 'Professional Work Experience',
                    'show_projects' => 'Engineered Projects Showcase',
                    'show_skills' => 'Technical Skills & Practices',
                    'show_education' => 'Education Foundations',
                    'show_research' => 'Research & Highlights',
                    'show_blog' => 'Engineering Blog Articles',
                    'show_contact' => 'Contact Inquiry Form',
                    'live_chat_enabled' => 'Floating Live Chat Widget',
                ];
            @endphp

            @foreach($sections as $secKey => $secLabel)
                <div class="sec-toggle-row">
                    <span class="sec-toggle-name">{{ $secLabel }}</span>
                    <label class="admin-switch">
                        <input type="checkbox" data-section="{{ $secKey }}" {{ ($portfolio['settings'][$secKey] ?? true) ? 'checked' : '' }} onchange="toggleSectionSetting(this)">
                        <span class="admin-slider"></span>
                    </label>
                </div>
            @endforeach
        </div>
    </div>
    <div class="admin-modal-footer">
        <button type="button" class="btn btn-primary btn-sm" onclick="closeAdminModal('sectionVisibilityModal')">Done</button>
    </div>
</div>
@endauth
