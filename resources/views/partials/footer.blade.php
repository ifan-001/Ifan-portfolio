<footer class="site-footer">
    <div class="container footer-inner">
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                <span class="brand-avatar-frame" style="width: 30px; height: 30px;" aria-hidden="true">
                    <img src="{{ asset('images/profile.jpg') }}" alt="Hasnain Al Ifan" class="brand-avatar-img">
                </span>
                <span style="font-weight: 800; font-size: 1.125rem; color: var(--text-primary);">
                    IFAN<a href="{{ route('admin.login') }}" class="secret-brand-dot" aria-label="Brand Mark">.</a>
                </span>
            </div>
            <p class="footer-copy">
                &copy; {{ date('Y') }} {{ $portfolio['brand']['full_name'] }}. All verified rights reserved.
            </p>
            <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 4px;">
                Engineered with PHP, Laravel &amp; Blade Templates &middot; Dhaka, Bangladesh
                <a href="{{ route('admin.login') }}" class="secret-terminal-trigger" aria-label="Terminal Prompt">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polyline points="4 17 10 11 4 5"></polyline>
                        <line x1="12" y1="19" x2="20" y2="19"></line>
                    </svg>
                </a>
            </p>
        </div>

        <nav class="footer-links" aria-label="Footer Navigation">
            <a href="#hero">Back to top &uarr;</a>
            <a href="#about">About</a>
            <a href="#experience">Experience</a>
            <a href="#projects">Projects</a>
            <a href="#skills">Skills</a>
            <a href="#education">Education</a>
            <a href="{{ route('blog.index') }}">Blog</a>
            <a href="#contact">Contact</a>
        </nav>
    </div>
</footer>
