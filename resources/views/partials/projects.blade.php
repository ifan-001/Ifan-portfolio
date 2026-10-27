<section class="section section-alt" id="projects">
    <div class="container">
        <div class="section-header">
            <span class="section-eyebrow">Portfolio Showcase</span>
            <h2 class="section-title">Engineered Projects</h2>
            <p class="section-desc">
                Real software applications ranging from a doctor-focused SaaS platform built in production to university projects across web, mobile, desktop, and computer vision.
            </p>
        </div>

        {{-- Filter Navigation --}}
        <div class="project-filters" role="tablist" aria-label="Project Categories">
            <button class="filter-btn active" data-filter="all" role="tab" aria-selected="true">All Projects (6)</button>
            <button class="filter-btn" data-filter="professional" role="tab" aria-selected="false">Professional (2)</button>
            <button class="filter-btn" data-filter="web" role="tab" aria-selected="false">Web Systems (3)</button>
            <button class="filter-btn" data-filter="mobile" role="tab" aria-selected="false">Mobile (1)</button>
            <button class="filter-btn" data-filter="desktop" role="tab" aria-selected="false">Desktop (1)</button>
            <button class="filter-btn" data-filter="ai" role="tab" aria-selected="false">AI &amp; Vision (1)</button>
        </div>

        {{-- FLAGSHIP PROJECT: DoctorQR --}}
        @php
            $doctorQr = collect($portfolio['projects'])->firstWhere('id', 'doctor-qr');
        @endphp
        @if($doctorQr)
            <div class="flagship-card" data-project-category="professional" id="doctor-qr-showcase">
                <div class="flagship-badge">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                    </svg>
                    <span>Primary Featured Project &middot; Flagship Case Study</span>
                </div>

                <div class="flagship-header">
                    <div>
                        <h3 class="flagship-title">{{ $doctorQr['title'] }}</h3>
                        <div class="flagship-role">{{ $doctorQr['role'] }}</div>
                    </div>

                    <div class="flagship-links">
                        @if($doctorQr['live_url'])
                            <a href="{{ $doctorQr['live_url'] }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-primary">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                                    <polyline points="15 3 21 3 21 9"></polyline>
                                    <line x1="10" y1="14" x2="21" y2="3"></line>
                                </svg>
                                <span>Live Practice Portal</span>
                            </a>
                        @endif

                        @if($doctorQr['repo_url'])
                            <a href="{{ $doctorQr['repo_url'] }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-secondary">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path>
                                </svg>
                                <span>GitHub Repository</span>
                            </a>
                        @endif
                    </div>
                </div>

                <p class="flagship-summary">{{ $doctorQr['summary'] }}</p>

                {{-- Interactive Implemented Workflows Tabs --}}
                <div class="workflow-section">
                    <div class="workflow-header-title">Implemented Application Workflows &amp; Architecture</div>
                    
                    <div class="workflow-nav" role="tablist">
                        @foreach($doctorQr['workflows'] as $idx => $wf)
                            <button class="workflow-tab-btn {{ $idx === 0 ? 'active' : '' }}" 
                                    data-workflow-target="wf-pane-{{ $idx }}"
                                    role="tab"
                                    aria-selected="{{ $idx === 0 ? 'true' : 'false' }}">
                                <span>{{ $wf['name'] }}</span>
                            </button>
                        @endforeach
                    </div>

                    @foreach($doctorQr['workflows'] as $idx => $wf)
                        <div class="workflow-pane {{ $idx === 0 ? 'active' : '' }}" id="wf-pane-{{ $idx }}" role="tabpanel">
                            <h4 class="workflow-pane-title">{{ $wf['name'] }}</h4>
                            <p class="workflow-pane-desc">{{ $wf['desc'] }}</p>

                            {{-- Workflow Specific Demonstrations --}}
                            @if(str_contains(strtolower($wf['name']), 'payment'))
                                <div class="payment-states-box">
                                    <span style="font-size: 0.8125rem; font-weight: 700; color: var(--text-light); margin-right: 4px;">Payment Model:</span>
                                    <span class="payment-state-pill pending">Pending</span>
                                    <span class="payment-state-arrow">&rarr;</span>
                                    <span class="payment-state-pill">Partial Paid</span>
                                    <span class="payment-state-arrow">&rarr;</span>
                                    <span class="payment-state-pill paid">Paid</span>
                                    <span class="payment-state-arrow">|</span>
                                    <span class="payment-state-pill failed">Failed</span>
                                    <span class="payment-state-arrow">|</span>
                                    <span class="payment-state-pill">Refunded</span>
                                </div>
                            @elseif(str_contains(strtolower($wf['name']), 'agora') || str_contains(strtolower($wf['name']), 'video'))
                                <div class="consultation-stepper">
                                    <div class="step-box">
                                        <div class="step-num">Step 1 &middot; Doctor Action</div>
                                        <div class="step-text">Doctor initiates call from patient queue interface</div>
                                    </div>
                                    <div class="step-box">
                                        <div class="step-num">Step 2 &middot; Token &amp; Link</div>
                                        <div class="step-text">Agora RTC token generated &amp; unique room created</div>
                                    </div>
                                    <div class="step-box">
                                        <div class="step-num">Step 3 &middot; SMS Gateway</div>
                                        <div class="step-text">Instant transactional SMS dispatched to patient</div>
                                    </div>
                                    <div class="step-box">
                                        <div class="step-num">Step 4 &middot; Real-time Call</div>
                                        <div class="step-text">WebRTC session with custom floating PIP on scroll</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Key Personal Contributions --}}
                <div style="margin-bottom: 24px;">
                    <div style="font-size: 0.875rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--accent-blue); margin-bottom: 12px;">
                        My Development Contributions (Based on Company Requirements)
                    </div>
                    <ul class="highlights-list">
                        @foreach($doctorQr['contribution_details'] as $item)
                            <li class="highlight-item">{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>

                {{-- Verified Tech Stack --}}
                <div>
                    <div style="font-size: 0.8125rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-muted); margin-bottom: 10px;">
                        Verified Technology Stack
                    </div>
                    <div class="tech-tags">
                        @foreach($doctorQr['tech_stack'] as $tech)
                            <span class="tech-tag tech-tag-verified">{{ $tech }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        {{-- SECONDARY & ACADEMIC PROJECTS GRID --}}
        <div class="projects-grid">
            @foreach($portfolio['projects'] as $project)
                @if($project['id'] === 'doctor-qr')
                    @continue
                @endif
                <div class="project-card" data-project-category="{{ $project['category'] }}">
                    <div>
                        <div class="project-meta-top">
                            <span class="project-cat-badge">{{ $project['category_label'] }}</span>
                            @if(!empty($project['repo_url']))
                                <a href="{{ $project['repo_url'] }}" target="_blank" rel="noopener noreferrer" class="social-link" style="width: 32px; height: 32px;" title="GitHub Code">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path>
                                    </svg>
                                </a>
                            @endif
                        </div>

                        <h3 class="project-card-title">{{ $project['title'] }}</h3>
                        <div class="project-card-role">{{ $project['role'] }}</div>
                        <p class="project-card-summary">{{ $project['summary'] }}</p>

                        @if(!empty($project['key_features']))
                            <ul class="project-card-features">
                                @foreach($project['key_features'] as $feat)
                                    <li>{{ $feat }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <div class="project-card-bottom">
                        <div class="tech-tags">
                            @foreach($project['tech_stack'] as $t)
                                <span class="tech-tag">{{ $t }}</span>
                            @endforeach
                        </div>

                        @if(!empty($project['repo_url']))
                            <div class="project-card-actions">
                                <a href="{{ $project['repo_url'] }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-secondary" style="width: 100%;">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path>
                                    </svg>
                                    <span>View Repository</span>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
