@php
    $heroImg = ltrim($portfolio['brand']['profile_image'], '/');
    $heroImgVer = file_exists(public_path($heroImg)) ? filemtime(public_path($heroImg)) : time();
    $projectCount = count($portfolio['projects'] ?? []);
    $techCount = collect($portfolio['skill_categories'] ?? [])->sum(fn ($c) => count($c['skills'] ?? []));
@endphp
<section class="section hero-section ph-hero" id="hero">
    <canvas class="ph-net" id="ph-net" aria-hidden="true"></canvas>
    <div class="ph-grid-bg" aria-hidden="true"></div>
    <div class="ph-glow ph-glow-a" aria-hidden="true"></div>
    <div class="ph-glow ph-glow-b" aria-hidden="true"></div>

    <div class="container">
        <div class="hero-grid ph-layout">
            <div class="hero-content ph-content">
                <div class="ph-top reveal">
                    <div class="status-badge ph-status">
                        <span class="status-dot"></span>
                        <span>{{ $portfolio['brand']['title'] }} &middot; {{ $portfolio['experience'][0]['company'] }}</span>
                    </div>
                    @auth
                        <button type="button" class="admin-edit-trigger" onclick="openAdminModal('profileInfoModal')" title="Edit profile details, designation, and bio">✎ Edit Profile</button>
                    @endauth
                </div>

                <p class="ph-hello reveal" style="--reveal-delay:60ms"><span class="ph-mono">&lt;hello world /&gt;</span> I'm</p>
                <h1 class="hero-name ph-name reveal" style="--reveal-delay:120ms">{{ $portfolio['brand']['full_name'] }}</h1>

                <div class="ph-role reveal" style="--reveal-delay:180ms" aria-label="Software Engineer, Laravel backend developer, AI and computer vision enthusiast">
                    <span class="ph-prompt" aria-hidden="true">&gt;_</span>
                    <span class="ph-typed" id="ph-typed" data-words='["Software Engineer","Laravel Backend Developer","SaaS Product Builder","Flutter & Mobile Developer","AI & Computer Vision Explorer"]'>Software Engineer</span><span class="ph-caret" aria-hidden="true"></span>
                </div>

                <div class="hero-headline ph-headline reveal" style="--reveal-delay:240ms">{{ $portfolio['brand']['hero_headline'] }}</div>

                <p class="hero-intro ph-intro reveal" style="--reveal-delay:300ms">{{ $portfolio['brand']['short_bio'] }}</p>

                <div class="hero-actions ph-actions reveal" style="--reveal-delay:360ms">
                    <a href="#projects" class="btn btn-primary ph-btn-primary">
                        <span>Explore my work</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </a>
                    <button type="button" class="btn btn-secondary ph-btn-ghost" data-pw-open="assist">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l1.9 4.6L18.5 9.5l-4.6 1.9L12 16l-1.9-4.6L5.5 9.5l4.6-1.9z"></path></svg>
                        <span>Ask my AI</span>
                    </button>
                    <a href="{{ route('resume.download') }}" class="ph-link" target="_blank" rel="noopener noreferrer">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                        <span>Download CV</span>
                    </a>
                    @auth
                        <button type="button" class="admin-edit-trigger" onclick="openAdminModal('resumeModal')" title="Upload or manage CV PDF">📄 Edit Resume / CV</button>
                    @endauth
                </div>

                <div class="ph-stats reveal" style="--reveal-delay:420ms">
                    <div><strong data-count="{{ $projectCount }}">{{ $projectCount }}</strong><span>Projects shipped</span></div>
                    <div><strong data-count="{{ $techCount }}" data-suffix="+">{{ $techCount }}+</strong><span>Technologies</span></div>
                    <div><strong>BUBT</strong><span>CSE &middot; Final year</span></div>
                </div>

                <div class="social-links ph-socials reveal" style="--reveal-delay:480ms" aria-label="Professional social profiles">
                    <a href="{{ $portfolio['socials']['github']['url'] }}" target="_blank" rel="noopener noreferrer" class="social-link" title="GitHub" aria-label="GitHub profile">
                        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path></svg>
                    </a>
                    <a href="{{ $portfolio['socials']['linkedin']['url'] }}" target="_blank" rel="noopener noreferrer" class="social-link" title="LinkedIn" aria-label="LinkedIn profile">
                        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                    </a>
                    <a href="{{ $portfolio['socials']['kaggle']['url'] }}" target="_blank" rel="noopener noreferrer" class="social-link" title="Kaggle" aria-label="Kaggle profile">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.825 23.859c-.022.092-.117.141-.281.141h-3.139c-.187 0-.351-.082-.492-.246l-4.758-6.188-1.5 1.453v4.664c0 .234-.141.375-.375.375H5.805c-.234 0-.375-.141-.375-.375V.375C5.43.141 5.571 0 5.805 0h2.476c.234 0 .375.141.375.375v14.156L14.227.352c.117-.188.258-.281.422-.281h3.398c.188 0 .281.07.281.211 0 .07-.047.164-.141.281l-6.422 7.734 6.961 15.281c.07.141.094.211.094.281z"/></svg>
                    </a>
                    <a href="{{ $portfolio['socials']['email']['url'] }}" class="social-link" title="Email" aria-label="Send email">
                        <svg width="19" height="19" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    </a>
                    @auth
                        <button type="button" class="admin-edit-trigger" onclick="openAdminModal('socialsModal')" title="Edit social media links">🔗 Edit Socials</button>
                    @endauth
                </div>
            </div>

            <div class="hero-visual ph-visual reveal" style="--reveal-delay:200ms">
                <div class="ph-orbit" aria-hidden="true">
                    <span class="ph-chip" style="--a:0deg">Laravel</span>
                    <span class="ph-chip" style="--a:72deg">PHP</span>
                    <span class="ph-chip" style="--a:144deg">Flutter</span>
                    <span class="ph-chip" style="--a:216deg">AI / CV</span>
                    <span class="ph-chip" style="--a:288deg">MySQL</span>
                </div>
                <div class="portrait-frame ph-frame">
                    <div class="portrait-image-wrapper ph-photo">
                        @auth
                            <button type="button" class="admin-photo-overlay-btn" onclick="openAdminModal('profilePhotoModal')" title="Upload new portrait photo and adjust fit">📷 Change Photo &amp; Fit</button>
                        @endauth
                        <img id="hero-profile-image"
                             src="{{ asset($heroImg) }}?v={{ $heroImgVer }}"
                             alt="{{ $portfolio['brand']['full_name'] }} — {{ $portfolio['brand']['title'] }}"
                             class="portrait-img"
                             loading="eager" fetchpriority="high" decoding="async"
                             width="420" height="520">
                        <div id="hero-fallback-avatar" class="portrait-fallback-avatar" aria-hidden="true"><span>{{ $portfolio['brand']['avatar_fallback'] }}</span></div>
                    </div>
                    <div class="portrait-overlay-badge ph-badge">
                        <span class="ph-badge-dot"></span>
                        <div>
                            <div class="portrait-badge-title">{{ $portfolio['brand']['full_name'] }}</div>
                            <div class="portrait-badge-sub">{{ $portfolio['brand']['title'] }}</div>
                        </div>
                    </div>
                </div>
                <div class="ph-code" aria-hidden="true">
                    <div class="ph-code-bar"><i></i><i></i><i></i><span>engineer.php</span></div>
<pre><span class="k">$ifan</span> = <span class="f">new</span> <span class="c">Engineer</span>([
  <span class="s">'stack'</span> =&gt; [<span class="s">'Laravel'</span>, <span class="s">'Flutter'</span>],
  <span class="s">'focus'</span> =&gt; <span class="s">'clean, real-world SaaS'</span>,
  <span class="s">'learning'</span> =&gt; <span class="s">'AI &amp; vision'</span>,
]);
<span class="k">$ifan</span>-&gt;<span class="f">ship</span>(); <span class="m">// ✓ deployed</span></pre>
                </div>
            </div>
        </div>
    </div>
    <a href="#about" class="ph-scroll" aria-label="Scroll to About section"><span></span></a>
</section>
