<footer class="site-footer">
    <div class="container footer-inner">
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                <span class="brand-avatar-frame" style="width: 30px; height: 30px;" aria-hidden="true">
                    <img src="{{ asset('images/profile.jpg') }}" alt="Hasnain Al Ifan" class="brand-avatar-img">
                </span>
                <span style="font-weight: 800; font-size: 1.125rem; color: var(--text-primary);">
                    IFAN<span class="brand-dot">.</span>
                </span>
            </div>
            <p class="footer-copy">
                &copy; {{ date('Y') }} {{ $portfolio['brand']['full_name'] }}. All verified rights reserved.
            </p>
            <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 4px;">
                Engineered with PHP, Laravel &amp; Blade Templates &middot; Dhaka, Bangladesh
                <a href="{{ route('admin.login') }}" class="discreet-portal-trigger" title="Staff Portal" aria-label="Staff Portal">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
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
