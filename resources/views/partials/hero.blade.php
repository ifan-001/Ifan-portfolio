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
                        <span>{{ $portfolio['texts']['hero_status'] ?? ($portfolio['brand']['title'] . ' · ' . ($portfolio['experience'][0]['company'] ?? '')) }}</span>
                    </div>
                    @auth
                        <button type="button" class="admin-edit-trigger" onclick="openAdminModal('profileInfoModal')" title="Edit profile details, designation, and bio">✎ Edit Profile</button>
                    @endauth
                </div>

                <p class="ph-hello reveal" style="--reveal-delay:60ms"><span class="ph-mono">&lt;hello world /&gt;</span> I'm</p>
                <h1 class="hero-name ph-name reveal" style="--reveal-delay:120ms">{{ $portfolio['brand']['full_name'] }}</h1>

                <div class="ph-role reveal" style="--reveal-delay:180ms" aria-label="Software Engineer, Laravel backend developer, AI and computer vision enthusiast">
                    <span class="ph-prompt" aria-hidden="true">&gt;_</span>
                    <span class="ph-typed" id="ph-typed" data-words="{{ json_encode(array_values(array_filter(array_map('trim', explode(',', $portfolio['texts']['hero_typed'] ?? 'Software Engineer'))))) }}">{{ trim(explode(',', $portfolio['texts']['hero_typed'] ?? 'Software Engineer')[0]) }}</span><span class="ph-caret" aria-hidden="true"></span>
                </div>

                <div class="hero-headline ph-headline reveal" style="--reveal-delay:240ms">{{ $portfolio['brand']['hero_headline'] }}</div>

                <p class="hero-intro ph-intro reveal" style="--reveal-delay:300ms">{{ $portfolio['brand']['short_bio'] }}</p>

                <div class="hero-actions ph-actions reveal" style="--reveal-delay:360ms">
                    <a href="#projects" class="btn btn-primary ph-btn-primary">
                        <span>Explore my work</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </a>
                    <button type="button" class="btn btn-secondary ph-btn-ghost" data-pw-open="live">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3l1.9 4.6L18.5 9.5l-4.6 1.9L12 16l-1.9-4.6L5.5 9.5l4.6-1.9z"></path></svg>
                        <span>Chat with me</span>
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
                    @include('partials.social-icons')
                    @auth
                        <a class="admin-edit-trigger" href="{{ route('admin.site-content', ['tab' => 'socials']) }}" title="Add, edit or remove social links">🔗 Edit socials</a>
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
