<section class="section section-alt" id="education">
    <div class="container">
        <div class="section-header">
            <span class="section-eyebrow">Academic Background</span>
            <h2 class="section-title">Education</h2>
            <p class="section-desc">
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
                    <div class="edu-badge-primary">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                            <path d="M6 12v5c3 3 9 3 12 0v-5"></path>
                        </svg>
                        <span>University Degree &middot; In Progress</span>
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
