<section class="section hero-section" id="hero">
    <div class="container">
        <div class="hero-grid">
            <div class="hero-content">
                <div class="status-badge">
                    <span class="status-dot"></span>
                    <span>{{ $portfolio['brand']['title'] }} &middot; {{ $portfolio['experience'][0]['company'] }}</span>
                </div>

                <h1 class="hero-name">{{ $portfolio['brand']['full_name'] }}</h1>

                <div class="hero-headline">
                    {{ $portfolio['brand']['hero_headline'] }}
                </div>

                <div class="hero-subheadline">
                    {{ $portfolio['brand']['hero_subheadline'] }}
                </div>

                <p class="hero-intro">
                    {{ $portfolio['brand']['short_bio'] }}
                </p>

                <div class="hero-meta-list">
                    <div class="hero-meta-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                            <circle cx="12" cy="10" r="3"></circle>
                        </svg>
                        <span>{{ $portfolio['brand']['location'] }}</span>
                    </div>
                    <div class="hero-meta-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                        <span>{{ $portfolio['experience'][0]['company'] }}</span>
                    </div>
                    <div class="hero-meta-item">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                        </svg>
                        <span>BUBT &middot; Final Year (8th Sem)</span>
                    </div>
                </div>

                <div class="hero-actions">
                    <a href="#projects" class="btn btn-primary">
                        <span>Explore My Projects</span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>
                    <a href="#contact" class="btn btn-secondary">
                        <span>Let's Connect</span>
                    </a>
                </div>

                <div class="social-links" aria-label="Professional Social Profiles">
                    {{-- GitHub --}}
                    <a href="{{ $portfolio['socials']['github']['url'] }}" target="_blank" rel="noopener noreferrer" class="social-link" title="GitHub: {{ $portfolio['socials']['github']['handle'] }}" aria-label="GitHub Profile">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path>
                        </svg>
                    </a>

                    {{-- LinkedIn --}}
                    <a href="{{ $portfolio['socials']['linkedin']['url'] }}" target="_blank" rel="noopener noreferrer" class="social-link" title="LinkedIn: {{ $portfolio['socials']['linkedin']['handle'] }}" aria-label="LinkedIn Profile">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path>
                            <rect x="2" y="9" width="4" height="12"></rect>
                            <circle cx="4" cy="4" r="2"></circle>
                        </svg>
                    </a>

                    {{-- Kaggle --}}
                    <a href="{{ $portfolio['socials']['kaggle']['url'] }}" target="_blank" rel="noopener noreferrer" class="social-link" title="Kaggle: {{ $portfolio['socials']['kaggle']['handle'] }}" aria-label="Kaggle Profile">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                            <path d="M18.825 23.859c-.022.092-.117.141-.281.141h-3.139c-.187 0-.351-.082-.492-.246l-4.758-6.188-1.5 1.453v4.664c0 .234-.141.375-.375.375H5.805c-.234 0-.375-.141-.375-.375V.375C5.43.141 5.571 0 5.805 0h2.476c.234 0 .375.141.375.375v14.156L14.227.352c.117-.188.258-.281.422-.281h3.398c.188 0 .281.07.281.211 0 .07-.047.164-.141.281l-6.422 7.734 6.961 15.281c.07.141.094.211.094.281z"/>
                        </svg>
                    </a>

                    {{-- Email --}}
                    <a href="{{ $portfolio['socials']['email']['url'] }}" class="social-link" title="Email: {{ $portfolio['socials']['email']['handle'] }}" aria-label="Send Direct Email">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                            <polyline points="22,6 12,13 2,6"></polyline>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Portrait Visual Representation --}}
            <div class="hero-visual">
                <div class="portrait-frame">
                    <div class="portrait-image-wrapper">
                        <img id="hero-profile-image"
                             src="{{ asset('images/profile.jpg') }}" 
                             alt="Hasnain Al Ifan - Junior Software Engineer" 
                             class="portrait-img"
                             loading="eager"
                             width="360"
                             height="450">
                        <div id="hero-fallback-avatar" class="portrait-fallback-avatar" aria-hidden="true">
                            <span>{{ $portfolio['brand']['avatar_fallback'] }}</span>
                        </div>
                    </div>

                    <div class="portrait-overlay-badge">
                        <div>
                            <div class="portrait-badge-title">{{ $portfolio['brand']['full_name'] }}</div>
                            <div class="portrait-badge-sub">Junior Software Engineer</div>
                        </div>
                        <span class="brand-avatar-frame" style="width: 34px; height: 34px;" aria-hidden="true">
                            <img src="{{ asset('images/profile.jpg') }}" alt="Hasnain Al Ifan" class="brand-avatar-img">
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
