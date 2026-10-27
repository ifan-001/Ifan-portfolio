<section class="section" id="experience">
    <div class="container">
        <div class="section-header">
            <span class="section-eyebrow">Work History</span>
            <div style="display:flex; align-items:center; justify-content:center; gap:12px; flex-wrap:wrap;">
                <h2 class="section-title" style="margin-bottom:0;">Professional Experience</h2>
                @auth
                    <button type="button" class="admin-edit-trigger" onclick="openAddExperienceModal()" title="Add professional work experience">
                        + Add Work Experience
                    </button>
                @endauth
            </div>
            <p class="section-desc" style="margin-top:16px;">
                Continuous tenure at APPIFLY BD LIMITED advancing from software engineering intern to Junior Software Engineer, building real-world web applications and enterprise workflows.
            </p>
        </div>

        <div class="experience-wrapper">
            @foreach($portfolio['experience'] as $exp)
                <div class="company-card">
                    <div class="company-header">
                        <div>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <h3 class="company-name">{{ $exp['company'] }}</h3>
                                @auth
                                    @if(!empty($exp['id']))
                                        <button type="button" class="admin-btn-mini" onclick="openEditExperienceModal({{ $exp['id'] }}, '{{ addslashes($exp['company']) }}', '{{ addslashes($exp['location'] ?? '') }}', '{{ addslashes($exp['roles'][0]['designation'] ?? '') }}', '{{ addslashes($exp['timeline_label']) }}', '{{ addslashes($exp['roles'][0]['type'] ?? 'Full-time') }}', '{{ addslashes($exp['roles'][0]['summary'] ?? '') }}', '{{ addslashes(implode("\n", $exp['highlights'] ?? [])) }}', {{ ($exp['roles'][0]['current'] ?? false) ? 1 : 0 }})">
                                            ✎ Edit
                                        </button>
                                    @endif
                                @endauth
                            </div>
                            <div class="company-loc">{{ $exp['location'] }}</div>
                        </div>
                        <div class="company-timeline-pill">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                <line x1="16" y1="2" x2="16" y2="6"></line>
                                <line x1="8" y1="2" x2="8" y2="6"></line>
                                <line x1="3" y1="10" x2="21" y2="10"></line>
                            </svg>
                            <span>{{ $exp['timeline_label'] }}</span>
                        </div>
                    </div>

                    {{-- Nested Roles Timeline --}}
                    <div class="roles-timeline">
                        @foreach($exp['roles'] as $role)
                            <div class="role-entry {{ $role['current'] ? 'current' : '' }}">
                                <div class="role-dot"></div>
                                <div class="role-header">
                                    <div class="role-title">
                                        {{ $role['designation'] }}
                                        @if($role['current'])
                                            <span class="role-title-badge">Current Role</span>
                                        @endif
                                    </div>
                                    <div class="role-period">{{ $role['period'] }}</div>
                                </div>
                                <p class="role-summary">{{ $role['summary'] }}</p>
                            </div>
                        @endforeach
                    </div>

                    {{-- Verified Key Highlights --}}
                    <div class="experience-highlights-title">Key Contributions & Engineering Responsibilities</div>
                    <ul class="highlights-list">
                        @foreach($exp['highlights'] as $highlight)
                            <li class="highlight-item">{{ $highlight }}</li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</section>
