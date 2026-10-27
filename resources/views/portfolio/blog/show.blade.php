@extends('layouts.app')

@section('content')
<article class="section" style="padding-top:140px; min-height:80vh;">
    <div class="container" style="max-width:820px;">
        <!-- Breadcrumb & Back -->
        <div style="margin-bottom:28px;">
            <a href="{{ route('blog.index') }}" style="color:var(--text-muted); font-size:0.88rem; text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                &larr; Back to all articles
            </a>
        </div>

        <!-- Header -->
        <header style="margin-bottom:40px;">
            @if(!empty($post->tags))
                <div style="display:flex; flex-wrap:wrap; gap:8px; margin-bottom:16px;">
                    @foreach($post->tags as $tag)
                        <span style="font-size:0.75rem; font-weight:700; text-transform:uppercase; color:var(--accent-blue); background:rgba(59, 130, 246, 0.12); padding:3px 10px; border-radius:4px;">{{ $tag }}</span>
                    @endforeach
                </div>
            @endif

            <h1 style="font-size:2.5rem; font-weight:800; color:var(--text-primary); line-height:1.25; letter-spacing:-0.02em; margin-bottom:20px;">
                {{ $post->title }}
            </h1>

            <!-- Author & Metadata -->
            <div style="display:flex; align-items:center; gap:14px; padding-bottom:24px; border-bottom:1px solid var(--border-color);">
                <div style="width:44px; height:44px; border-radius:50%; overflow:hidden; border:2px solid var(--accent-blue);">
                    <img src="{{ asset('images/profile.jpg') }}" alt="Hasnain Al Ifan" style="width:100%; height:100%; object-fit:cover;">
                </div>
                <div>
                    <div style="font-weight:700; font-size:0.95rem; color:var(--text-primary);">{{ $portfolio['brand']['full_name'] }}</div>
                    <div style="font-size:0.8rem; color:var(--text-muted);">
                        Published on {{ $post->published_at ? $post->published_at->format('F d, Y') : 'Recently' }} &middot; {{ $post->read_time }} min read &middot; {{ number_format($post->views_count) }} views
                    </div>
                </div>
            </div>
        </header>

        <!-- Article Content Body -->
        <div class="article-content" style="font-size:1.05rem; line-height:1.8; color:#E2E8F0; margin-bottom:60px;">
            {!! nl2br(e($post->content)) !!}
        </div>

        <!-- Author Bio Footer Box -->
        <div style="padding:28px; background:var(--bg-card); border:1px solid var(--border-color); border-radius:var(--radius-lg); display:flex; gap:20px; align-items:center; margin-bottom:48px;">
            <div style="width:64px; height:64px; border-radius:50%; overflow:hidden; border:2px solid var(--accent-blue); flex-shrink:0;">
                <img src="{{ asset('images/profile.jpg') }}" alt="Hasnain Al Ifan" style="width:100%; height:100%; object-fit:cover;">
            </div>
            <div>
                <div style="font-weight:700; font-size:1.1rem; color:var(--text-primary);">Written by {{ $portfolio['brand']['full_name'] }}</div>
                <p style="font-size:0.85rem; color:var(--text-secondary); margin-top:4px; line-height:1.5;">
                    {{ $portfolio['brand']['title'] }} at APPIFLY BD LIMITED. Specializing in Laravel web applications, backend architecture, and practical engineering solutions.
                </p>
                <div style="margin-top:10px;">
                    <a href="{{ route('home') }}#contact" style="font-size:0.82rem; color:var(--accent-blue); font-weight:600; text-decoration:none;">Connect or discuss this article &rarr;</a>
                </div>
            </div>
        </div>

        @if($relatedPosts->isNotEmpty())
            <!-- Related Articles -->
            <div style="border-top:1px solid var(--border-color); padding-top:40px;">
                <h3 style="font-size:1.2rem; font-weight:700; margin-bottom:20px;">More Engineering Notes</h3>
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:20px;">
                    @foreach($relatedPosts as $rel)
                        <div style="padding:16px; background:var(--bg-card); border:1px solid var(--border-color); border-radius:var(--radius-md);">
                            <h4 style="font-size:0.95rem; font-weight:700; margin-bottom:8px;">
                                <a href="{{ route('blog.show', $rel->slug) }}" style="color:var(--text-primary); text-decoration:none;">
                                    {{ $rel->title }}
                                </a>
                            </h4>
                            <div style="font-size:0.75rem; color:var(--text-muted);">{{ $rel->read_time }} min read</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</article>
@endsection
