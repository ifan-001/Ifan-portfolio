@if(!empty($portfolio['blog_posts']) && count($portfolio['blog_posts']) > 0)
<section class="section section-alt pb-blog" id="blog">
    <div class="container">
        <div class="pp-section-head pb-head reveal">
            <div>
                <span class="pp-eyebrow"><span class="pp-eyebrow-line"></span>Writing</span>
                <div class="pp-head-row">
                    <h2 class="pp-title">Engineering notes</h2>
                    @auth
                        <button type="button" class="admin-edit-trigger" onclick="openAddBlogModal()" title="Write a new post">+ New article</button>
                    @endauth
                </div>
            </div>
            <a href="{{ route('blog.index') }}" class="pp-btn">All articles →</a>
        </div>

        <div class="pb-grid">
            @foreach($portfolio['blog_posts'] as $i => $post)
                <article class="pb-card reveal" style="--reveal-delay: {{ $i * 80 }}ms">
                    <div class="pb-meta">
                        @foreach(array_slice($post->tags ?? [], 0, 2) as $tag)<span>{{ $tag }}</span>@endforeach
                        @auth
                            <button type="button" class="admin-btn-mini" onclick="openEditBlogModal({{ $post->id }}, @js($post->title), @js($post->slug), @js(implode(', ', $post->tags ?? [])), @js($post->status), @js($post->excerpt ?? ''), @js($post->content))">✎</button>
                        @endauth
                    </div>
                    <h3><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h3>
                    <p>{{ $post->excerpt }}</p>
                    <div class="pb-foot">
                        <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : '' }} · {{ $post->read_time }} min read</span>
                        <a href="{{ route('blog.show', $post->slug) }}" aria-label="Read {{ $post->title }}">Read →</a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif
