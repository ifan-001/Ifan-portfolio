@php
    $projects = collect($portfolio['projects'])->values();
    $catLabels = ['professional' => 'Professional', 'web' => 'Web', 'mobile' => 'Mobile', 'desktop' => 'Desktop', 'ai' => 'AI & Vision'];
    $counts = $projects->groupBy('category')->map->count();
    $icons = [
        'qr' => '<rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><path d="M14 14h3v3h-3zM20 14v.01M14 20h.01M17 20h4v-3"/>',
        'news' => '<path d="M4 4h13a2 2 0 0 1 2 2v13a1 1 0 0 0 2 0V8"/><path d="M4 4v15a2 2 0 0 0 2 2h14"/><path d="M8 8h7M8 12h7M8 16h4"/>',
        'users' => '<path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/>',
        'shield' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/>',
        'code' => '<polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>',
        'brain' => '<path d="M9.5 2A2.5 2.5 0 0 1 12 4.5v15a2.5 2.5 0 0 1-4.96.44 2.5 2.5 0 0 1-2.96-3.08 3 3 0 0 1-.34-5.58 2.5 2.5 0 0 1 1.32-4.24A2.5 2.5 0 0 1 9.5 2z"/><path d="M14.5 2A2.5 2.5 0 0 0 12 4.5v15a2.5 2.5 0 0 0 4.96.44 2.5 2.5 0 0 0 2.96-3.08 3 3 0 0 0 .34-5.58 2.5 2.5 0 0 0-1.32-4.24A2.5 2.5 0 0 0 14.5 2z"/>',
        'cart' => '<circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>',
        'phone' => '<rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/>',
        'building' => '<rect x="4" y="2" width="16" height="20" rx="2"/><path d="M9 22v-4h6v4M8 6h.01M16 6h.01M12 6h.01M12 10h.01M12 14h.01M16 10h.01M16 14h.01M8 10h.01M8 14h.01"/>',
    ];
@endphp
<section class="section pp-projects" id="projects">
    <div class="container">
        <div class="pp-section-head reveal">
            <span class="pp-eyebrow"><span class="pp-eyebrow-line"></span>Selected Work</span>
            <div class="pp-head-row">
                <h2 class="pp-title">Projects I've <span class="pp-grad">engineered</span></h2>
                @auth
                    <button type="button" class="admin-edit-trigger" onclick="openAddProjectModal()" title="Create and showcase a new software project">+ Add New Project</button>
                @endauth
            </div>
            <p class="pp-lead">Production SaaS, a white-label news platform, Android security tooling and academic research. Open any project for the full case study, screenshots and features.</p>
        </div>

        <div class="pp-filters reveal" role="tablist" aria-label="Project categories">
            <button class="pp-filter active" data-pp-filter="all" role="tab" aria-selected="true">All <span>{{ $projects->count() }}</span></button>
            @foreach($catLabels as $key => $label)
                @if(($counts[$key] ?? 0) > 0)
                    <button class="pp-filter" data-pp-filter="{{ $key }}" role="tab" aria-selected="false">{{ $label }} <span>{{ $counts[$key] }}</span></button>
                @endif
            @endforeach
        </div>

        <div class="pp-grid">
            @foreach($projects as $i => $p)
                @php
                    $s = $p['showcase'] ?? [];
                    $accent = $s['accent'] ?? '#3B82F6';
                    $cover = $p['thumbnail'] ?? ($s['gallery'][0]['src'] ?? null);
                    $isFeatured = $p['featured'] ?? false;
                    $name = $s['name'] ?? $p['title'];
                    $icon = $icons[$s['icon'] ?? 'code'] ?? $icons['code'];
                    $isCompact = !$isFeatured && (str_contains(strtolower($s['status'] ?? ''), 'academic') || str_contains(strtolower($s['status'] ?? ''), 'capstone'));
                    $cardTagline = (!empty($s['tagline']) && !$isCompact) ? $s['tagline'] : \Illuminate\Support\Str::limit($p['summary'] ?? '', 120);
                @endphp
                <article class="pp-card {{ $isFeatured ? 'is-featured' : '' }} {{ $isCompact ? 'is-compact' : '' }} reveal" data-pp-cat="{{ $p['category'] }}" id="project-{{ $p['slug'] }}" style="--pp-accent: {{ $accent }}; --reveal-delay: {{ min($i, 6) * 60 }}ms">
                    <button type="button" class="pp-media" data-pp-open="{{ $p['slug'] }}" aria-label="Open {{ $name }} case study">
                        @if($cover)
                            <span class="pp-browser" aria-hidden="true"><i></i><i></i><i></i></span>
                            <img src="{{ asset(ltrim($cover, '/')) }}" alt="{{ $name }} screenshot" loading="lazy" decoding="async">
                        @else
                            <span class="pp-art" aria-hidden="true">
                                <span class="pp-art-grid"></span>
                                <svg width="54" height="54" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">{!! $icon !!}</svg>
                            </span>
                        @endif
                        <span class="pp-media-cta">View case study <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></span>
                    </button>

                    <div class="pp-body">
                        <div class="pp-meta">
                            <span class="pp-icon"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $icon !!}</svg></span>
                            <span class="pp-type">{{ $s['type'] ?? '' }}</span>
                            @if($isFeatured)<span class="pp-badge">Featured</span>@endif
                        </div>
                        <h3 class="pp-name">{{ $name }}</h3>
                        <p class="pp-tagline">{{ $cardTagline }}</p>

                        @if($isFeatured && !empty($s['stats']))
                            <div class="pp-stats">
                                @foreach(array_slice($s['stats'], 0, 4) as $st)
                                    <div><strong>{{ $st['value'] }}</strong><span>{{ $st['label'] }}</span></div>
                                @endforeach
                            </div>
                        @endif

                        <div class="pp-tags">
                            @foreach(array_slice($s['tech'] ?? ($p['tech_stack'] ?? []), 0, $isFeatured ? 7 : ($isCompact ? 3 : 4)) as $t)
                                <span>{{ $t }}</span>
                            @endforeach
                        </div>

                        <div class="pp-actions">
                            <button type="button" class="pp-btn pp-btn-primary" data-pp-open="{{ $p['slug'] }}">Case study</button>
                            @if(!empty($p['live_url']))
                                <a href="{{ $p['live_url'] }}" target="_blank" rel="noopener noreferrer" class="pp-btn" data-track-project="{{ $p['slug'] }}" data-track-type="demo">Live <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M7 17L17 7M7 7h10v10"/></svg></a>
                            @endif
                            @if(!empty($p['repo_url']))
                                <a href="{{ $p['repo_url'] }}" target="_blank" rel="noopener noreferrer" class="pp-btn" data-track-project="{{ $p['slug'] }}" data-track-type="github">GitHub</a>
                            @endif
                            @if(!empty($s['status']))<span class="pp-status">{{ $s['status'] }}</span>@endif
                            @auth
                                @if(!empty($p['db_id']))
                                    <button type="button" class="admin-edit-trigger" onclick="openEditProjectModal({{ $p['db_id'] }}, @js($p['title']), @js($p['slug']), @js($p['category']), @js($p['role'] ?? ''), @js($p['summary']), '', @js(implode(', ', $p['tech_stack'] ?? [])), @js($p['live_url'] ?? ''), @js($p['repo_url'] ?? ''), {{ $isFeatured ? 1 : 0 }})">✎ Edit</button>
                                @endif
                            @endauth
                        </div>
                    </div>
                </article>

                {{-- Case study content, opened in the modal --}}
                <template id="pp-case-{{ $p['slug'] }}">
                    <div class="pc" style="--pp-accent: {{ $accent }}">
                        <header class="pc-head">
                            <div>
                                <span class="pc-type">{{ $s['type'] ?? '' }}@if(!empty($s['status'])) &middot; {{ $s['status'] }}@endif</span>
                                <h3 class="pc-title">{{ $name }}</h3>
                                @if(!empty($s['tagline']))<p class="pc-tagline">{{ $s['tagline'] }}</p>@endif
                            </div>
                            <div class="pc-links">
                                @if(!empty($p['live_url']))<a class="pp-btn pp-btn-primary" href="{{ $p['live_url'] }}" target="_blank" rel="noopener noreferrer">Visit live site</a>@endif
                                @if(!empty($p['repo_url']))<a class="pp-btn" href="{{ $p['repo_url'] }}" target="_blank" rel="noopener noreferrer">Source code</a>@endif
                            </div>
                        </header>

                        @if(!empty($s['gallery']))
                            <div class="pc-gallery" data-pc-gallery>
                                <figure class="pc-stage">
                                    <span class="pp-browser" aria-hidden="true"><i></i><i></i><i></i></span>
                                    <img src="{{ asset(ltrim($s['gallery'][0]['src'], '/')) }}" alt="{{ $s['gallery'][0]['caption'] }}" data-pc-main>
                                    <figcaption data-pc-caption>{{ $s['gallery'][0]['caption'] }}</figcaption>
                                    <button type="button" class="pc-nav prev" data-pc-step="-1" aria-label="Previous screenshot">&#8249;</button>
                                    <button type="button" class="pc-nav next" data-pc-step="1" aria-label="Next screenshot">&#8250;</button>
                                </figure>
                                <div class="pc-thumbs">
                                    @foreach($s['gallery'] as $gi => $g)
                                        <button type="button" class="{{ $gi === 0 ? 'active' : '' }}" data-pc-thumb="{{ $gi }}" data-src="{{ asset(ltrim($g['src'], '/')) }}" data-caption="{{ $g['caption'] }}" aria-label="{{ $g['caption'] }}">
                                            <img src="{{ asset(ltrim($g['src'], '/')) }}" alt="" loading="lazy">
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="pc-cols">
                            <div>
                                @if(!empty($p['summary']))
                                    <h4>Overview</h4>
                                    <p>{{ $p['summary'] }}</p>
                                @endif
                                @if(!empty($s['problem']))
                                    <h4>The problem it solves</h4>
                                    <p>{{ $s['problem'] }}</p>
                                @endif
                                @if(!empty($s['role']))
                                    <h4>My role</h4>
                                    <p>{{ $s['role'] }}</p>
                                @endif
                            </div>
                            <aside>
                                @if(!empty($s['stats']))
                                    <div class="pc-stats">
                                        @foreach($s['stats'] as $st)
                                            <div><strong>{{ $st['value'] }}</strong><span>{{ $st['label'] }}</span></div>
                                        @endforeach
                                    </div>
                                @endif
                                <h4>Tech stack</h4>
                                <div class="pp-tags">@foreach($s['tech'] ?? [] as $t)<span>{{ $t }}</span>@endforeach</div>
                            </aside>
                        </div>

                        @if(!empty($s['features']))
                            <h4>Key features</h4>
                            <div class="pc-features">
                                @foreach($s['features'] as $fi => $f)
                                    <div class="pc-feature">
                                        <span class="pc-feature-num">{{ str_pad($fi + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                        <div><strong>{{ $f['title'] }}</strong>@if($f['desc'])<p>{{ $f['desc'] }}</p>@endif</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        @if(!empty($s['mobile_gallery']))
                            <h4>On mobile</h4>
                            <div class="pc-phones">
                                @foreach($s['mobile_gallery'] as $m)
                                    <figure class="pc-phone">
                                        <div class="pc-phone-frame"><img src="{{ asset(ltrim($m['src'], '/')) }}" alt="{{ $m['caption'] }}" loading="lazy"></div>
                                        <figcaption>{{ $m['caption'] }}</figcaption>
                                    </figure>
                                @endforeach
                            </div>
                        @endif

                        @if(!empty($s['contribution']))
                            <h4>What I contributed</h4>
                            <ul class="pc-list">@foreach($s['contribution'] as $c)<li>{{ $c }}</li>@endforeach</ul>
                        @endif

                        <div class="pc-ask">
                            <span>Have a question about {{ $name }}?</span>
                            <button type="button" class="pp-btn pp-btn-primary" data-pw-ask="Tell me about {{ $name }}">Ask the AI assistant</button>
                        </div>
                    </div>
                </template>
            @endforeach
        </div>
    </div>

    <div class="pc-modal" id="pc-modal" aria-hidden="true">
        <div class="pc-backdrop" data-pc-close></div>
        <div class="pc-dialog" role="dialog" aria-modal="true" aria-label="Project case study">
            <button type="button" class="pc-close" data-pc-close aria-label="Close case study">&times;</button>
            <div class="pc-content" id="pc-content"></div>
        </div>
    </div>
</section>
