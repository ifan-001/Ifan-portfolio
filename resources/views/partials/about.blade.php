@php $t = $portfolio['texts'] ?? []; $b = $portfolio['brand']; @endphp
<section class="section pa-about" id="about">
    <div class="container">
        <div class="pp-section-head reveal">
            <span class="pp-eyebrow"><span class="pp-eyebrow-line"></span>About</span>
            <div class="pp-head-row">
                <h2 class="pp-title">{{ $t['about_title'] ?? 'About me' }}</h2>
                @auth
                    <button type="button" class="admin-edit-trigger" onclick="openAdminModal('aboutBioModal')" title="Edit, add, or delete bio paragraphs">✎ Edit bio</button>
                    <a class="admin-edit-trigger" href="{{ route('admin.site-content', ['tab' => 'about']) }}">✎ Edit cards</a>
                @endauth
            </div>
            <p class="pp-lead">{{ $t['about_lead'] ?? '' }}</p>
        </div>

        <div class="pa-grid">
            <div class="pa-story reveal">
                @foreach($b['detailed_bio'] as $i => $paragraph)
                    <p class="{{ $i === 0 ? 'pa-first' : '' }}">{{ $paragraph }}</p>
                @endforeach
                <dl class="pa-facts">
                    <div><dt>Based in</dt><dd>{{ $b['location'] }}</dd></div>
                    <div><dt>Role</dt><dd>{{ $b['title'] }}</dd></div>
                    @if(!empty($portfolio['experience'][0]['company']))<div><dt>Company</dt><dd>{{ $portfolio['experience'][0]['company'] }}</dd></div>@endif
                    @if(!empty($portfolio['education'][0]['institution']))<div><dt>Study</dt><dd>{{ \Illuminate\Support\Str::before($portfolio['education'][0]['institution'], ' (') }}</dd></div>@endif
                </dl>
            </div>

            <div class="pa-focus">
                @foreach($portfolio['focus_areas'] as $i => $focus)
                    <article class="pa-card reveal" style="--reveal-delay: {{ $i * 80 }}ms">
                        <span class="pa-num">{{ $focus['number'] }}</span>
                        <h3>{{ $focus['title'] }}</h3>
                        <p>{{ $focus['description'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </div>
</section>
