@php
    $t = $portfolio['texts'] ?? [];
    $rIcons = ['<rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6"/><path d="M9 1v3M15 1v3M9 20v3M15 20v3M20 9h3M20 14h3M1 9h3M1 14h3"/>', '<path d="M3 7V5a2 2 0 0 1 2-2h2M17 3h2a2 2 0 0 1 2 2v2M21 17v2a2 2 0 0 1-2 2h-2M7 21H5a2 2 0 0 1-2-2v-2"/><circle cx="12" cy="12" r="3"/>', '<path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><polyline points="9 12 11 14 15 10"/>', '<path d="M2 12h4l3-9 6 18 3-9h4"/>'];
@endphp
<section class="section pr-research" id="research">
    <div class="container">
        <div class="pp-section-head reveal">
            <span class="pp-eyebrow"><span class="pp-eyebrow-line"></span>Research</span>
            <div class="pp-head-row">
                <h2 class="pp-title">{{ $t['research_title'] ?? 'Research interests' }}</h2>
                @auth
                    <button type="button" class="admin-edit-trigger" onclick="openAddResearchModal()" title="Add research topic">+ Add topic</button>
                    <a class="admin-edit-trigger" href="{{ route('admin.research.index') }}">✎ Manage</a>
                @endauth
            </div>
            <p class="pp-lead">{{ $t['research_lead'] ?? ($portfolio['research']['intro'] ?? '') }}</p>
        </div>

        <div class="pr-grid">
            @foreach($portfolio['research']['interests'] ?? [] as $i => $item)
                <article class="pr-card reveal" style="--reveal-delay: {{ ($i % 4) * 70 }}ms">
                    <div class="pr-top">
                        <span class="pr-icon"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $rIcons[$i % count($rIcons)] !!}</svg></span>
                        <span class="pr-idx">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        @auth
                            @if(!empty($item['id']))
                                <button type="button" class="admin-btn-mini danger" onclick="deleteResearchItem({{ (int) $item['id'] }}, @js($item['title']))" title="Delete topic">✖</button>
                            @endif
                        @endauth
                    </div>
                    <h3>{{ $item['title'] }}</h3>
                    <p>{{ $item['description'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>
