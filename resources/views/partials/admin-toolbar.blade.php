@auth
<div class="admin-top-command-bar" id="adminCommandBar">
    <div class="admin-command-container">
        {{-- Left: Monogram and Active Mode --}}
        <div class="admin-bar-left">
            <span class="admin-badge-pulse"></span>
            <span class="admin-bar-logo">IFAN<span style="color:var(--accent-blue);">.</span> ADMIN</span>
            <span class="admin-mode-pill">Visual CMS Active</span>
        </div>

        {{-- Center: 3-Pillar Switcher --}}
        <div class="admin-pillar-tabs">
            <a href="{{ route('admin.dashboard') }}" class="admin-pillar-link">
                <span>⚡ Core Operations</span>
            </a>
            <a href="{{ url('/?admin_mode=cms') }}" class="admin-pillar-link active">
                <span>✦ Content Management</span>
            </a>
            <a href="{{ route('admin.analytics.index') }}" class="admin-pillar-link">
                <span>⚙ System &amp; Insights</span>
            </a>
        </div>

        {{-- Quick Section Jumpers --}}
        <div class="admin-section-jumper">
            <select id="adminSectionJump" class="admin-jump-select" onchange="if(this.value) window.location.hash = this.value;">
                <option value="">Jump to Section &darr;</option>
                <option value="#hero">Hero &amp; Profile</option>
                <option value="#about">About Me</option>
                <option value="#experience">Work Experience</option>
                <option value="#projects">Projects</option>
                <option value="#skills">Technical Skills</option>
                <option value="#education">Education</option>
                <option value="#research">Research &amp; Highlights</option>
                <option value="#blog">Blog Articles</option>
                <option value="#contact">Contact Section</option>
            </select>
        </div>

        {{-- Right: Toggles and Exit --}}
        <div class="admin-bar-right">
            <button type="button" class="admin-btn-action" onclick="openAdminModal('sectionVisibilityModal')" title="Toggle section visibility on/off">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                <span>Sections</span>
            </button>

            <button type="button" class="admin-btn-action" id="toggleVisitorPreviewBtn" onclick="toggleVisitorPreview()" title="Preview exactly as visitors see without logging out">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                <span id="previewBtnText">Visitor View</span>
            </button>

            <form action="{{ route('admin.logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" class="admin-btn-logout" title="Sign out of Admin Portal">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    <span>Exit</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endauth
