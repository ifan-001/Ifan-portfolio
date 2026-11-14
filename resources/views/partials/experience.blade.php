@php $t = $portfolio['texts'] ?? []; @endphp
<section class="section pe-exp" id="experience">
    <div class="container">
        <div class="pp-section-head reveal">
            <span class="pp-eyebrow"><span class="pp-eyebrow-line"></span>Experience</span>
            <div class="pp-head-row">
                <h2 class="pp-title">{{ $t['experience_title'] ?? 'Where I work' }}</h2>
                @auth
                    <button type="button" class="admin-edit-trigger" onclick="openAddExperienceModal()" title="Add professional work experience">+ Add experience</button>
                @endauth
            </div>
            <p class="pp-lead">{{ $t['experience_lead'] ?? '' }}</p>
        </div>

        <div class="pe-timeline">
            @foreach($portfolio['experience'] as $exp)
                <article class="pe-company reveal">
                    <div class="pe-rail" aria-hidden="true"><span class="pe-node"></span></div>
                    <div class="pe-card">
                        <header class="pe-head">
                            <div class="pe-logo" aria-hidden="true">{{ \Illuminate\Support\Str::of($exp['company'])->substr(0, 2)->upper() }}</div>
                            <div class="pe-head-text">
                                <h3>{{ $exp['company'] }}</h3>
                                <span>{{ $exp['location'] }}@if(!empty($exp['website'])) · <a href="{{ $exp['website'] }}" target="_blank" rel="noopener noreferrer">{{ preg_replace('#^https?://(www\.)?#', '', rtrim($exp['website'], '/')) }}</a>@endif</span>
                            </div>
                            <span class="pe-period">{{ $exp['timeline_label'] }}</span>
                            @auth
                                @if(!empty($exp['id']))
                                    <button type="button" class="admin-btn-mini" onclick="openEditExperienceModal({{ $exp['id'] }}, @js($exp['company']), @js($exp['location'] ?? ''), @js($exp['roles'][0]['designation'] ?? ''), @js($exp['timeline_label']), @js($exp['roles'][0]['type'] ?? 'Full-time'), @js($exp['roles'][0]['summary'] ?? ''), @js(implode("\n", $exp['highlights'] ?? [])), {{ ($exp['roles'][0]['current'] ?? false) ? 1 : 0 }})">✎ Edit</button>
                                @endif
                            @endauth
                        </header>

                        @foreach($exp['roles'] as $role)
                            <div class="pe-role">
                                <div class="pe-role-top">
                                    <strong>{{ $role['designation'] }}</strong>
                                    @if($role['current'])<span class="pe-now"><i></i>Current</span>@endif
                                    @if(!empty($role['type']))<span class="pe-type">{{ $role['type'] }}</span>@endif
                                </div>
                                @if(!empty($role['summary']))<p>{{ $role['summary'] }}</p>@endif
                            </div>
                        @endforeach

                        @if(!empty($exp['highlights']))
                            <ul class="pe-wins">
                                @foreach($exp['highlights'] as $h)
                                    <li><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"/></svg><span>{{ $h }}</span></li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
