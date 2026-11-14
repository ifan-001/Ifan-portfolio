@php
    $t = $portfolio['texts'] ?? [];
    $bachelor = collect($portfolio['education'])->firstWhere('is_primary', true);
    $secondaryEdu = collect($portfolio['education'])->where('is_primary', false);
    $editArgs = fn ($e) => implode(', ', [
        (int) ($e['id'] ?? 0),
        \Illuminate\Support\Js::from($e['institution'] ?? ''),
        \Illuminate\Support\Js::from($e['degree'] ?? ''),
        \Illuminate\Support\Js::from($e['expected_graduation'] ?? $e['passing_year'] ?? ''),
        \Illuminate\Support\Js::from($e['status'] ?? ''),
        \Illuminate\Support\Js::from(trim(\Illuminate\Support\Str::before((string) ($e['cgpa'] ?? ''), '/'))),
        \Illuminate\Support\Js::from(trim(\Illuminate\Support\Str::after((string) ($e['cgpa'] ?? '/4.00'), '/')) ?: '4.00'),
        \Illuminate\Support\Js::from(implode(', ', $e['coursework'] ?? [])),
    ]);
@endphp
<section class="section section-alt pd-edu" id="education">
    <div class="container">
        <div class="pp-section-head reveal">
            <span class="pp-eyebrow"><span class="pp-eyebrow-line"></span>Education</span>
            <div class="pp-head-row">
                <h2 class="pp-title">{{ $t['education_title'] ?? 'Education' }}</h2>
                @auth
                    <button type="button" class="admin-edit-trigger" onclick="openAddEducationModal()" title="Add education qualification">+ Add education</button>
                @endauth
            </div>
            <p class="pp-lead">{{ $t['education_lead'] ?? '' }}</p>
        </div>

        <div class="pd-grid">
            @if($bachelor)
                <article class="pd-main reveal">
                    <div class="pd-main-top">
                        <span class="pd-badge"><svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 10L12 5 2 10l10 5 10-5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>{{ $bachelor['status'] }}</span>
                        @auth
                            @if(!empty($bachelor['id']))
                                <button type="button" class="admin-btn-mini" onclick="openEditEducationModal({{ $editArgs($bachelor) }})">✎ Edit</button>
                            @endif
                        @endauth
                    </div>
                    <h3>{{ $bachelor['degree'] }}</h3>
                    <p class="pd-inst">{{ $bachelor['institution'] }} · {{ $bachelor['location'] }}</p>
                    <div class="pd-stats">
                        @if(!empty($bachelor['cgpa']))<div><strong>{{ $bachelor['cgpa'] }}</strong><span>CGPA</span></div>@endif
                        @if(!empty($bachelor['expected_graduation']))<div><strong>{{ $bachelor['expected_graduation'] }}</strong><span>Completion</span></div>@endif
                        <div><strong>CSE</strong><span>Major</span></div>
                    </div>
                    @if(!empty($bachelor['coursework']))
                        <div class="pd-course-title">Key coursework</div>
                        <div class="pp-tags">@foreach($bachelor['coursework'] as $course)<span>{{ $course }}</span>@endforeach</div>
                    @endif
                </article>
            @endif

            <div class="pd-side">
                @foreach($secondaryEdu as $i => $edu)
                    <article class="pd-small reveal" style="--reveal-delay: {{ ($i + 1) * 80 }}ms">
                        <div class="pd-year">{{ $edu['passing_year'] ?? $edu['period'] ?? '' }}</div>
                        <div class="pd-small-body">
                            <h4>{{ $edu['degree'] }}</h4>
                            <p>{{ $edu['institution'] }}</p>
                            <span class="pd-gpa">{{ $edu['group'] ?? $edu['field'] ?? 'Science' }} · GPA {{ $edu['gpa'] ?? $edu['cgpa'] ?? 'N/A' }}</span>
                        </div>
                        @auth
                            @if(!empty($edu['id']))
                                <button type="button" class="admin-btn-mini" onclick="openEditEducationModal({{ $editArgs($edu) }})">✎</button>
                            @endif
                        @endauth
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>
