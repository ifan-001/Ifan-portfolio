<section class="section section-alt" id="about">
    <div class="container">
        <div class="section-header">
            <span class="section-eyebrow">Background & Engineering Focus</span>
            <h2 class="section-title">About Me</h2>
            <p class="section-desc">
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
