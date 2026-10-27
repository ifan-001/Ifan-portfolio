<section class="section section-alt" id="education">
    <div class="container">
        <div class="section-header">
            <span class="section-eyebrow">Academic Background</span>
            <div style="display:flex; align-items:center; justify-content:center; gap:12px; flex-wrap:wrap;">
                <h2 class="section-title" style="margin-bottom:0;">Education</h2>
                @auth
                    <button type="button" class="admin-edit-trigger" onclick="openAddEducationModal()" title="Add education qualification">
                        + Add Education
                    </button>
                @endauth
            </div>
            <p class="section-desc" style="margin-top:16px;">
                Computer Science and Engineering foundations from BUBT, supplemented by foundational science education.
            </p>
        </div>

        <div class="education-wrapper">
            @php
                $bachelor = collect($portfolio['education'])->firstWhere('is_primary', true);
                $secondaryEdu = collect($portfolio['education'])->where('is_primary', false);
            @endphp

            @if($bachelor)
                <div class="bachelor-card">
                    <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
                        <div class="edu-badge-primary">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                                <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                            </svg>
                            <span>University Degree &middot; In Progress</span>
                        </div>
                        @auth
                            @if(!empty($bachelor['id']))
                                <button type="button" class="admin-btn-mini" onclick="openEditEducationModal({{ $bachelor['id'] }}, '{{ addslashes($bachelor['institution']) }}', '{{ addslashes($bachelor['degree']) }}', '{{ addslashes($bachelor['expected_graduation'] ?? $bachelor['passing_year'] ?? '') }}', '{{ addslashes($bachelor['status']) }}', '{{ $bachelor['cgpa'] ?? '' }}', '4.00', '{{ addslashes(implode(', ', $bachelor['coursework'] ?? [])) }}')">
                                    ✎ Edit University Details
                                </button>
                            @endif
                        @endauth
                    </div>

                    <h3 class="edu-title">{{ $bachelor['degree'] }}</h3>
                    <div class="edu-institution">{{ $bachelor['institution'] }}, {{ $bachelor['location'] }}</div>

                    <div class="edu-stats-row">
                        <div class="edu-stat">
                            <span class="edu-stat-label">Academic Status</span>
                            <span class="edu-stat-val">{{ $bachelor['status'] }}</span>
                        </div>
                        <div class="edu-stat">
                            <span class="edu-stat-label">Current CGPA</span>
                            <span class="edu-stat-val">{{ $bachelor['cgpa'] }}</span>
                        </div>
                        <div class="edu-stat">
                            <span class="edu-stat-label">Expected Completion</span>
                            <span class="edu-stat-val">{{ $bachelor['expected_graduation'] }}</span>
                        </div>
                    </div>

                    @if(!empty($bachelor['coursework']))
                        <div class="coursework-title">Key Coursework Completed</div>
                        <div class="coursework-tags">
                            @foreach($bachelor['coursework'] as $course)
                                <span class="coursework-tag">{{ $course }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif

            <div class="secondary-edu-grid">
                @foreach($secondaryEdu as $edu)
                    <div class="secondary-edu-card">
                        <h4 class="secondary-edu-degree">{{ $edu['degree'] }}</h4>
                        <div class="secondary-edu-inst">{{ $edu['institution'] }}</div>
                        <div class="secondary-edu-meta">
                            <span>Group: {{ $edu['group'] ?? $edu['field'] ?? 'Science' }} &middot; GPA {{ $edu['gpa'] ?? $edu['cgpa'] ?? 'N/A' }}</span>
                            <span>{{ $edu['passing_year'] ?? $edu['period'] ?? '' }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
