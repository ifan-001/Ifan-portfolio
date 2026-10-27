<section class="section" id="skills">
    <div class="container">
        <div class="section-header">
            <span class="section-eyebrow">Technical Competencies</span>
            <div style="display:flex; align-items:center; justify-content:flex-start; gap:12px; flex-wrap:wrap;">
                <h2 class="section-title" style="margin-bottom:0;">Skills &amp; Engineering Practices</h2>
                @auth
                    <button type="button" class="admin-edit-trigger" onclick="openAddSkillModal()" title="Add a new technical competency">
                        + Add New Skill
                    </button>
                @endauth
            </div>
            <p class="section-desc" style="margin-top:16px;">
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
                                <div style="display:flex; align-items:center; gap:6px;">
                                    <span class="skill-context {{ $isPro ? 'pro' : '' }}">{{ $s['context'] }}</span>
                                    @auth
                                        @if(!empty($s['id']))
                                            <button type="button" class="admin-btn-mini" onclick="openEditSkillModal({{ $s['id'] }}, '{{ addslashes($s['name']) }}', '{{ addslashes($cat['title']) }}', {{ $s['proficiency'] ?? 85 }})" title="Edit or delete skill">✎</button>
                                        @endif
                                    @endauth
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
    </div>
</section>
