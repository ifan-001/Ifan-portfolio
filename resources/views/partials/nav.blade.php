@php
    $isHome = request()->routeIs('home');
    $homePrefix = $isHome ? '' : route('home');
@endphp
<header class="site-header" id="site-header">
    <div class="container nav-wrapper">
        <a href="{{ $homePrefix }}#hero" class="brand-logo" aria-label="Hasnain Al Ifan - Home">
            <span class="brand-avatar-frame" aria-hidden="true">
                <img src="{{ asset(ltrim($portfolio['brand']['profile_image'] ?? '/images/profile.jpg', '/')) }}" alt="Hasnain Al Ifan" class="brand-avatar-img">
            </span>
            <span>IFAN<span class="brand-dot">.</span></span>
        </a>

        <nav class="nav-links" aria-label="Main Navigation">
            <a href="{{ $homePrefix }}#hero" class="nav-link {{ $isHome ? 'active' : '' }}">Home</a>
            <a href="{{ $homePrefix }}#about" class="nav-link">About</a>
            <a href="{{ $homePrefix }}#experience" class="nav-link">Experience</a>
            <a href="{{ $homePrefix }}#projects" class="nav-link">Projects</a>
            <a href="{{ $homePrefix }}#skills" class="nav-link">Skills</a>
            <a href="{{ $homePrefix }}#education" class="nav-link">Education</a>
            <a href="{{ $homePrefix }}#research" class="nav-link">Research</a>
            <a href="{{ $homePrefix }}#contact" class="nav-link">Contact</a>
        </nav>

        <div class="nav-actions">
            {{-- Dedicated Header Button for Posts & Updates Feed --}}
            <a href="{{ route('feed.index') }}" class="nav-feed-pill {{ request()->routeIs('feed.*') ? 'active' : '' }}" title="View Professional Feed & Project Updates">
                <span class="feed-pulse-dot" aria-hidden="true"></span>
                <span>Feed</span>
            </a>

            <a href="{{ route('resume.download') }}" class="btn btn-sm btn-secondary" target="_blank" rel="noopener noreferrer" title="Open Hasnain Al Ifan Resume (PDF)">
                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
                <span>Resume</span>
            </a>

            <a href="{{ $homePrefix }}#contact" class="btn btn-sm btn-primary">
                Let's Talk
            </a>

            {{-- Theme Switcher (Dark & Bright Theme) --}}
            <button type="button" class="theme-toggle-btn" id="theme-toggle-btn" onclick="togglePortfolioTheme()" aria-label="Toggle Bright / Dark theme" title="Switch to Bright Theme">
                <svg class="theme-icon-sun" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="5"></circle>
                    <line x1="12" y1="1" x2="12" y2="3"></line>
                    <line x1="12" y1="21" x2="12" y2="23"></line>
                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
                    <line x1="1" y1="12" x2="3" y2="12"></line>
                    <line x1="21" y1="12" x2="23" y2="12"></line>
                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
                </svg>
                <svg class="theme-icon-moon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none;" aria-hidden="true">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
                </svg>
            </button>

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
        <a href="{{ route('feed.index') }}" class="mobile-nav-link {{ request()->routeIs('feed.*') ? 'active' : '' }}" style="display:flex; align-items:center; justify-content:space-between; background:rgba(59,130,246,0.08); border-radius:8px; padding:10px 14px; margin-bottom:8px;">
            <span style="display:flex; align-items:center; gap:8px; font-weight:600; color:var(--text-primary);">
                <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#3B82F6;"></span>
                <span>Feed / Updates</span>
            </span>
            <span style="font-size:0.7rem; background:var(--accent-blue); color:#fff; padding:2px 8px; border-radius:10px; font-weight:600;">NEW</span>
        </a>

        <a href="{{ $homePrefix }}#hero" class="mobile-nav-link {{ $isHome ? 'active' : '' }}">Home</a>
        <a href="{{ $homePrefix }}#about" class="mobile-nav-link">About</a>
        <a href="{{ $homePrefix }}#experience" class="mobile-nav-link">Experience</a>
        <a href="{{ $homePrefix }}#projects" class="mobile-nav-link">Projects</a>
        <a href="{{ $homePrefix }}#skills" class="mobile-nav-link">Skills</a>
        <a href="{{ $homePrefix }}#education" class="mobile-nav-link">Education</a>
        <a href="{{ $homePrefix }}#research" class="mobile-nav-link">Research</a>
        <a href="{{ $homePrefix }}#contact" class="mobile-nav-link">Contact</a>

        <div class="mobile-nav-actions">
            {{-- Mobile Theme Toggle --}}
            <button type="button" class="theme-toggle-btn theme-toggle-btn-mobile" onclick="togglePortfolioTheme()" aria-label="Toggle Bright / Dark theme">
                <svg class="theme-icon-sun" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                <svg class="theme-icon-moon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                <span class="theme-toggle-label">Switch to Bright Theme</span>
            </button>

            <a href="{{ route('resume.download') }}" target="_blank" rel="noopener noreferrer" class="btn btn-secondary" style="width: 100%;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                    <polyline points="14 2 14 8 20 8"></polyline>
                    <line x1="16" y1="13" x2="8" y2="13"></line>
                    <line x1="16" y1="17" x2="8" y2="17"></line>
                </svg>
                <span>Open Resume (PDF)</span>
            </a>
            <a href="{{ $homePrefix }}#contact" class="btn btn-primary" style="width: 100%;">
                Let's Connect
            </a>
        </div>
    </div>
</header>
