<div class="highlights-bar" aria-label="Key Career Facts">
    @foreach($portfolio['highlights'] as $highlight)
        <div class="highlight-box">
            <span class="highlight-box-label">{{ $highlight['label'] }}</span>
            <span class="highlight-box-val">{{ $highlight['value'] }}</span>
            <span class="highlight-box-sub">{{ $highlight['detail'] }}</span>
        </div>
    @endforeach
</div>
