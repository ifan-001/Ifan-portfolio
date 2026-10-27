<header class="site-header" id="site-header">
    <div class="container nav-wrapper">
        <a href="#hero" class="brand-logo" aria-label="Hasnain Al Ifan - Home">
            <span class="monogram" aria-hidden="true">I.</span>
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
            @if(!empty($portfolio['resume']['is_available']))
                <a href="{{ route('resume.download') }}" class="btn btn-sm btn-secondary" target="_blank" rel="noopener">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="7 10 12 15 17 10"></polyline>
                        <line x1="12" y1="15" x2="12" y2="3"></line>
                    </svg>
                    <span>Resume</span>
                </a>
            @else
                <button type="button" class="btn btn-sm btn-secondary btn-disabled" title="{{ $portfolio['resume']['status_note'] }}" aria-label="Resume available upon request">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                    </svg>
                    <span>Resume</span>
                </button>
            @endif

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
            @if(!empty($portfolio['resume']['is_available']))
                <a href="{{ route('resume.download') }}" class="btn btn-secondary" style="width: 100%;">
                    Download Resume
                </a>
            @else
                <button type="button" class="btn btn-secondary btn-disabled" style="width: 100%;">
                    Resume (Under Final Review)
                </button>
            @endif
            <a href="#contact" class="btn btn-primary" style="width: 100%;">
                Let's Connect
            </a>
        </div>
    </div>
</header>
