<footer class="site-footer">
    <div class="container footer-inner">
        <div>
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                <span class="monogram" style="width: 28px; height: 28px; font-size: 0.75rem;">I.</span>
                <span style="font-weight: 800; font-size: 1.125rem; color: var(--text-primary);">
                    IFAN<span class="brand-dot">.</span>
                </span>
            </div>
            <p class="footer-copy">
                &copy; {{ date('Y') }} {{ $portfolio['brand']['full_name'] }}. All verified rights reserved.
            </p>
            <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 4px;">
                Engineered with PHP, Laravel &amp; Blade Templates &middot; Dhaka, Bangladesh
            </p>
        </div>

        <nav class="footer-links" aria-label="Footer Navigation">
            <a href="#hero">Back to top &uarr;</a>
            <a href="#about">About</a>
            <a href="#experience">Experience</a>
            <a href="#projects">Projects</a>
            <a href="#skills">Skills</a>
            <a href="#education">Education</a>
            <a href="#contact">Contact</a>
        </nav>
    </div>
</footer>
