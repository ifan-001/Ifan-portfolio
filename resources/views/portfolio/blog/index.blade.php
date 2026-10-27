@extends('layouts.app')

@section('content')
<section class="section" style="padding-top:140px; min-height:80vh;">
    <div class="container">
        <div class="section-header" style="text-align:left; max-width:800px; margin-bottom:48px;">
            <span class="section-eyebrow">Technical Writings &amp; Engineering Notes</span>
            <h1 class="section-title" style="font-size:2.5rem; margin-top:8px;">Engineering Blog</h1>
            <p class="section-desc" style="max-width:640px;">
                Reflections, deep dives, and practical engineering solutions developed while building web applications, real-time video workflows, and AI research projects.
            </p>
        </div>

        @if($posts->isEmpty())
            <div style="padding:60px 24px; text-align:center; background:rgba(255,255,255,0.02); border:1px solid var(--border-color); border-radius:var(--radius-lg);">
                <p style="color:var(--text-muted); font-size:1.1rem;">New engineering articles are currently being written. Check back soon!</p>
                <a href="{{ route('home') }}" class="btn btn-secondary" style="margin-top:20px;">&larr; Return to Home</a>
            </div>
        @else
            <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(320px, 1fr)); gap:28px;">
                @foreach($posts as $post)
                    <article style="background:var(--bg-card); border:1px solid var(--border-color); border-radius:var(--radius-lg); padding:28px; display:flex; flex-direction:column; justify-content:space-between; transition:var(--transition); position:relative;">
                        <div>
                            @if(!empty($post->tags))
                                <div style="display:flex; flex-wrap:wrap; gap:6px; margin-bottom:14px;">
                                    @foreach(array_slice($post->tags, 0, 3) as $t)
                                        <span style="font-size:0.72rem; font-weight:600; text-transform:uppercase; color:var(--accent-blue); background:rgba(59, 130, 246, 0.1); padding:2px 8px; border-radius:4px;">{{ $t }}</span>
                                    @endforeach
                                </div>
                            @endif

                            <h2 style="font-size:1.25rem; font-weight:700; color:var(--text-primary); line-height:1.4; margin-bottom:12px;">
                                <a href="{{ route('blog.show', $post->slug) }}" style="color:var(--text-primary); text-decoration:none;">
                                    {{ $post->title }}
                                </a>
                            </h2>

                            <p style="font-size:0.9rem; color:var(--text-secondary); line-height:1.6; margin-bottom:20px;">
                                {{ $post->excerpt }}
                            </p>
                        </div>

                        <div style="display:flex; align-items:center; justify-content:space-between; font-size:0.8rem; color:var(--text-muted); border-top:1px solid rgba(255,255,255,0.06); padding-top:16px;">
                            <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : '' }} &middot; {{ $post->read_time }} min read</span>
                            <a href="{{ route('blog.show', $post->slug) }}" style="color:var(--accent-blue); font-weight:600; text-decoration:none;">Read article &rarr;</a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div style="margin-top:40px;">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</section>
@endsection
