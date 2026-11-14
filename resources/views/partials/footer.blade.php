@php $t = $portfolio['texts'] ?? []; $homePrefix = request()->routeIs('home') ? '' : route('home'); @endphp
<footer class="site-footer pf-footer">
    <div class="pf-glow" aria-hidden="true"></div>
    <div class="container">
        <div class="pf-cta reveal">
            <div>
                <h2>Have an idea or a role in mind?</h2>
                <p>I'm always happy to talk about software, products and new opportunities.</p>
            </div>
            <div class="pf-cta-actions">
                <button type="button" class="btn btn-primary" data-pw-open="live">Chat with me</button>
                <a href="{{ $homePrefix }}#contact" class="btn btn-secondary">Send a message</a>
            </div>
        </div>

        <div class="footer-inner pf-grid">
            <div class="pf-brand">
                <div class="pf-logo">
                    <span class="brand-avatar-frame" style="width: 38px; height: 38px;" aria-hidden="true">
                        <img src="{{ asset(ltrim($portfolio['brand']['profile_image'], '/')) }}" alt="" class="brand-avatar-img">
                    </span>
                    <span class="pf-wordmark">IFAN<a href="{{ route('admin.login') }}" class="secret-brand-dot" aria-label="Brand Mark">.</a></span>
                </div>
                <p class="pf-tagline">{{ $t['footer_tagline'] ?? '' }}</p>
                <div class="pf-socials">@include('partials.social-icons', ['class' => 'social-link pf-social'])</div>
            </div>

            <nav class="pf-col" aria-label="Portfolio">
                <h3>Portfolio</h3>
                <a href="{{ $homePrefix }}#about">About</a>
                <a href="{{ $homePrefix }}#experience">Experience</a>
                <a href="{{ $homePrefix }}#projects">Projects</a>
                <a href="{{ $homePrefix }}#skills">Skills</a>
                <a href="{{ $homePrefix }}#education">Education</a>
            </nav>
            <nav class="pf-col" aria-label="Explore">
                <h3>Explore</h3>
                <a href="{{ route('feed.index') }}">Feed</a>
                <a href="{{ route('apps.index') }}">Apps &amp; tools</a>
                <a href="{{ route('apps.resume-builder') }}">CV builder</a>
                <a href="{{ route('blog.index') }}">Blog</a>
                <a href="{{ route('resume.download') }}" target="_blank" rel="noopener noreferrer">Download CV</a>
            </nav>
            <div class="pf-col">
                <h3>Contact</h3>
                <a href="mailto:{{ $portfolio['brand']['email'] }}">{{ $portfolio['brand']['email'] }}</a>
                <span>{{ $portfolio['brand']['location'] }}</span>
                <span class="pf-status"><i></i> Open to opportunities</span>
            </div>
        </div>

        <div class="pf-bottom">
            <p class="footer-copy">&copy; {{ date('Y') }} {{ $portfolio['brand']['full_name'] }}. All rights reserved.</p>
            <p class="pf-built">
                Built with Laravel, Blade &amp; vanilla JS · Dhaka, Bangladesh
                <a href="{{ route('admin.login') }}" class="secret-terminal-trigger" aria-label="Terminal Prompt">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="4 17 10 11 4 5"></polyline><line x1="12" y1="19" x2="20" y2="19"></line></svg>
                </a>
            </p>
            <a href="#hero" class="pf-top" aria-label="Back to top">↑</a>
        </div>
    </div>
</footer>
