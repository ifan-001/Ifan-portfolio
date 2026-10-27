@if(!empty($portfolio['blog_posts']) && count($portfolio['blog_posts']) > 0)
<section class="section section-alt" id="blog">
    <div class="container">
        <div class="section-header" style="display:flex; justify-content:space-between; align-items:flex-end; flex-wrap:wrap; gap:16px;">
            <div>
                <span class="section-eyebrow">Technical Writings</span>
                <h2 class="section-title">Engineering Articles</h2>
                <p class="section-desc">Practical architectural walkthroughs, deep dives, and code solutions.</p>
            </div>
            <a href="{{ route('blog.index') }}" class="btn btn-secondary btn-sm">View All Articles &rarr;</a>
        </div>

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:24px; margin-top:32px;">
            @foreach($portfolio['blog_posts'] as $post)
                <article style="background:var(--bg-primary); border:1px solid var(--border-subtle); border-radius:var(--radius-lg); padding:24px; display:flex; flex-direction:column; justify-content:space-between; transition:all var(--transition-fast);">
                    <div>
                        @if(!empty($post->tags))
                            <div style="display:flex; flex-wrap:wrap; gap:6px; margin-bottom:12px;">
                                @foreach(array_slice($post->tags, 0, 2) as $t)
                                    <span style="font-size:0.7rem; font-weight:700; text-transform:uppercase; color:var(--accent-blue); background:rgba(59, 130, 246, 0.1); padding:2px 6px; border-radius:4px;">{{ $t }}</span>
                                @endforeach
                            </div>
                        @endif

                        <h3 style="font-size:1.15rem; font-weight:700; color:var(--text-primary); margin-bottom:8px; line-height:1.4;">
                            <a href="{{ route('blog.show', $post->slug) }}" style="color:var(--text-primary); text-decoration:none;">
                                {{ $post->title }}
                            </a>
                        </h3>

                        <p style="font-size:0.875rem; color:var(--text-light); line-height:1.6; margin-bottom:16px;">
                            {{ $post->excerpt }}
                        </p>
                    </div>

                    <div style="display:flex; align-items:center; justify-content:space-between; font-size:0.78rem; color:var(--text-muted); border-top:1px solid rgba(255,255,255,0.05); padding-top:12px;">
                        <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : '' }} &middot; {{ $post->read_time }} min read</span>
                        <a href="{{ route('blog.show', $post->slug) }}" style="color:var(--accent-blue); font-weight:600; text-decoration:none;">Read &rarr;</a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif
