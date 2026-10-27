<section class="section" id="skills">
    <div class="container">
        <div class="section-header">
            <span class="section-eyebrow">Technical Competencies</span>
            <h2 class="section-title">Skills &amp; Engineering Practices</h2>
            <p class="section-desc">
                Practical abilities gained through real software development in production environments, academic projects, and continuous hands-on learning.
            </p>
        </div>

        <div class="skills-grid">
            @foreach($portfolio['skill_categories'] as $cat)
                <div class="skill-category-card">
                    <h3 class="skill-cat-title">{{ $cat['title'] }}</h3>
                    <div class="skill-cat-desc">{{ $cat['description'] }}</div>

                    <ul class="skill-list">
                        @foreach($cat['skills'] as $s)
                            @php
                                $isPro = str_contains(strtolower($s['context']), 'professional') || str_contains(strtolower($s['context']), 'production') || str_contains(strtolower($s['context']), 'primary');
                            @endphp
                            <li class="skill-item">
                                <span class="skill-name">{{ $s['name'] }}</span>
                                <span class="skill-context {{ $isPro ? 'pro' : '' }}">{{ $s['context'] }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</section>
