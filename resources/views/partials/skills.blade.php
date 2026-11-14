@php
    $catIcons = [
        'backend' => '<polyline points="16 18 22 12 16 6"/><polyline points="8 6 2 12 8 18"/>',
        'mobile' => '<rect x="5" y="2" width="14" height="20" rx="2"/><line x1="12" y1="18" x2="12.01" y2="18"/>',
        'database' => '<ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/>',
        'language' => '<path d="M4 17l6-6-6-6"/><line x1="12" y1="19" x2="20" y2="19"/>',
        'integration' => '<path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/>',
        'tools' => '<path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/>',
        'practice' => '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><path d="M9 12l2 2 4-4"/>',
        'ai' => '<circle cx="12" cy="12" r="3"/><path d="M12 1v4M12 19v4M4.22 4.22l2.83 2.83M16.95 16.95l2.83 2.83M1 12h4M19 12h4M4.22 19.78l2.83-2.83M16.95 7.05l2.83-2.83"/>',
    ];
    $pickIcon = function ($title) use ($catIcons) {
        $t = strtolower($title);
        foreach (['mobile' => 'mobile', 'data' => 'database', 'language' => 'language', 'integration' => 'integration', 'api' => 'integration', 'tool' => 'tools', 'practice' => 'practice', 'ai' => 'ai', 'vision' => 'ai'] as $needle => $key) {
            if (str_contains($t, $needle)) return $catIcons[$key];
        }
        return $catIcons['backend'];
    };
@endphp
<section class="section ps-skills" id="skills">
    <div class="container">
        <div class="pp-section-head reveal">
            <span class="pp-eyebrow"><span class="pp-eyebrow-line"></span>Tech Stack</span>
            <div class="pp-head-row">
                <h2 class="pp-title">Skills &amp; <span class="pp-grad">engineering practice</span></h2>
                @auth
                    <button type="button" class="admin-edit-trigger" onclick="openAddSkillModal()" title="Add a new technical competency">+ Add New Skill</button>
                @endauth
            </div>
            <p class="pp-lead">Tools I use in production at APPIFLY and across academic projects. Hover a group to see where it fits.</p>
        </div>

        <div class="ps-grid">
            @foreach($portfolio['skill_categories'] as $ci => $cat)
                <div class="ps-card reveal" style="--reveal-delay: {{ ($ci % 3) * 70 }}ms">
                    <div class="ps-head">
                        <span class="ps-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $pickIcon($cat['title']) !!}</svg></span>
                        <h3 class="ps-title">{{ $cat['title'] }}</h3>
                        <span class="ps-count">{{ count($cat['skills']) }}</span>
                    </div>
                    <ul class="ps-chips">
                        @foreach($cat['skills'] as $s)
                            @php
                                $ctx = strtolower($s['context'] ?? '');
                                $isPro = str_contains($ctx, 'professional') || str_contains($ctx, 'production') || str_contains($ctx, 'primary');
                            @endphp
                            <li class="ps-chip {{ $isPro ? 'is-pro' : '' }}" @if(!empty($s['context']) && $s['context'] !== $cat['title']) title="{{ $s['context'] }}" @endif>
                                <span>{{ $s['name'] }}</span>
                                @auth
                                    @if(!empty($s['id']))
                                        <button type="button" class="admin-btn-mini" onclick="openEditSkillModal({{ $s['id'] }}, @js($s['name']), @js($cat['title']), {{ $s['proficiency'] ?? 85 }})" title="Edit or delete skill">✎</button>
                                    @endif
                                @endauth
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</section>
