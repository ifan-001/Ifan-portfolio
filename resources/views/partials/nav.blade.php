<header class="site-header" id="site-header">
    <div class="container nav-wrapper">
        <a href="#hero" class="brand-logo" aria-label="Hasnain Al Ifan - Home">
            <span class="brand-avatar-frame" aria-hidden="true">
                <img src="{{ asset(ltrim($portfolio['brand']['profile_image'], '/')) }}" alt="Hasnain Al Ifan" class="brand-avatar-img">
            </span>
            <span>IFAN<span class="brand-dot">.</span></span>
        </a>

        <nav class="nav-links" aria-label="Main Navigation">
            <a href="#hero" class="nav-link active">Home</a>
            <a href="#about" class="nav-link">About</a>
            <a href="#experience" class="nav-link">Experience</a>
            <a href="#projects" class="nav-link">Projects</a>
            <a href="#skills" class="nav-link">Skills</a>
            <a href="#education" class="nav-link">Education</a>
            <a href="#research" class="nav-link">Research</a>
            <a href="#contact" class="nav-link">Contact</a>
        </nav>

        <div class="nav-actions">
            <a href="{{ route('resume.download') }}" class="btn btn-sm btn-secondary" target="_blank" rel="noopener noreferrer" title="Open Hasnain Al Ifan Resume (PDF)">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
                <span>Resume</span>
            </a>

            <a href="#contact" class="btn btn-sm btn-primary">
                Let's Talk
            </a>

            <button id="menu-toggle" class="menu-toggle" aria-label="Toggle Navigation Menu" aria-expanded="false" aria-controls="mobile-drawer">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Navigation Drawer --}}
    <div id="mobile-drawer" class="mobile-nav-drawer" role="dialog" aria-modal="true" aria-label="Mobile Navigation">
        <a href="#hero" class="mobile-nav-link active">Home</a>
        <a href="#about" class="mobile-nav-link">About</a>
        <a href="#experience" class="mobile-nav-link">Experience</a>
        <a href="#projects" class="mobile-nav-link">Projects</a>
        <a href="#skills" class="mobile-nav-link">Skills</a>
        <a href="#education" class="mobile-nav-link">Education</a>
        <a href="#research" class="mobile-nav-link">Research</a>
        <a href="#contact" class="mobile-nav-link">Contact</a>

        <div class="mobile-nav-actions">
            <a href="{{ route('resume.download') }}" target="_blank" rel="noopener noreferrer" class="btn btn-secondary" style="width: 100%;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
                <span>Open Resume (PDF)</span>
            </a>
            <a href="#contact" class="btn btn-primary" style="width: 100%;">
                Let's Connect
            </a>
        </div>
    </div>
</header>
