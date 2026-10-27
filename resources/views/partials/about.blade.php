<section class="section section-alt" id="about">
    <div class="container">
        <div class="section-header">
            <span class="section-eyebrow">Background & Engineering Focus</span>
            <div style="display:flex; align-items:center; justify-content:flex-start; gap:12px; flex-wrap:wrap;">
                <h2 class="section-title" style="margin-bottom:0;">About Me</h2>
                @auth
                    <button type="button" class="admin-edit-trigger" onclick="openAdminModal('aboutBioModal')" title="Edit, add, or delete bio paragraphs">
                        ✎ Edit Bio Paragraphs
                    </button>
                @endauth
            </div>
            <p class="section-desc" style="margin-top:16px;">
                Early-career software engineer dedicated to building practical applications, understanding complete system workflows, and writing maintainable code.
            </p>
        </div>

        <div class="about-grid">
            <div class="bio-card">
                @foreach($portfolio['brand']['detailed_bio'] as $paragraph)
                    <p class="bio-paragraph">{{ $paragraph }}</p>
                @endforeach
            </div>

            <div class="focus-cards-grid">
                @foreach($portfolio['focus_areas'] as $focus)
                    <div class="focus-card">
                        <div class="focus-num">{{ $focus['number'] }}</div>
                        <h3 class="focus-title">{{ $focus['title'] }}</h3>
                        <p class="focus-desc">{{ $focus['description'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
