@extends('layouts.app')

@section('title', 'Feed: Project Updates & Engineering Notes | Hasnain Al Ifan')
@section('meta_description', 'Project milestones, work-in-progress snapshots and engineering notes from Hasnain Al Ifan. Like and comment on any post.')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/feed.css') }}?v={{ filemtime(public_path('css/feed.css')) }}">
@endpush

@php
    $avatar = asset(ltrim($portfolio['brand']['profile_image'], '/'));
    $cats = ['all' => ['All', '✦'], 'project' => ['Projects', '🚀'], 'professional' => ['Professional', '💼'], 'industrial' => ['Industry', '🏭'], 'research' => ['Research', '🔬'], 'social' => ['Life', '👥']];
@endphp

@section('content')
<div class="fd-page">
    <aside class="fd-side">
        <a href="{{ route('home') }}" class="fd-back">&larr; Back to Portfolio</a>
        <div class="fd-profile">
            <div class="fd-cover" aria-hidden="true"></div>
            <img src="{{ $avatar }}" alt="{{ $portfolio['brand']['full_name'] }}" class="fd-avatar">
            <h1>{{ $portfolio['brand']['full_name'] }} <span class="fd-verified" title="Verified">✓</span></h1>
            <p class="fd-role">{{ $portfolio['brand']['title'] }}</p>
            <p class="fd-bio">Project milestones, work-in-progress snapshots and engineering notes, straight from my desk.</p>
            <div class="fd-stats">
                <div><strong>{{ $counts['all'] }}</strong><span>Posts</span></div>
                <div><strong>{{ $counts['project'] }}</strong><span>Projects</span></div>
            </div>
            <div class="fd-actions">
                <a href="{{ route('home') }}#projects" class="fd-btn fd-btn-primary">View portfolio</a>
                <button type="button" class="fd-btn" data-pw-open="live">Message</button>
            </div>
        </div>
        @auth
            <a href="{{ route('admin.feed.index') }}" class="fd-btn fd-btn-primary fd-new">+ Write a post</a>
        @endauth
    </aside>

    <main class="fd-main">
        <nav class="fd-filters" aria-label="Filter posts">
            @foreach($cats as $key => [$label, $icon])
                @if($key === 'all' || ($counts[$key] ?? 0) > 0)
                    <a href="{{ route('feed.index', ['category' => $key]) }}" class="fd-filter {{ ($category ?? 'all') === $key ? 'active' : '' }}"><span aria-hidden="true">{{ $icon }}</span> {{ $label }} <em>{{ $counts[$key] ?? 0 }}</em></a>
                @endif
            @endforeach
        </nav>

        <div class="fd-stream">
            @forelse($posts as $post)
                <article class="fd-post" id="post-{{ $post->id }}">
                    <header class="fd-post-head">
                        <img src="{{ $avatar }}" alt="" class="fd-post-avatar">
                        <div class="fd-post-who">
                            <strong>{{ $post->author_name }} <span class="fd-verified">✓</span></strong>
                            <span>
                                <em class="fd-cat" style="--c: {{ $post->category_color }}">{{ $post->category_label }}</em>
                                · <time datetime="{{ optional($post->published_at)->toIso8601String() }}">{{ $post->published_at ? $post->published_at->diffForHumans() : 'Recently' }}</time>
                                @if($post->is_pinned) · <b class="fd-pin">📌 Pinned</b>@endif
                            </span>
                        </div>
                        <button type="button" class="fd-icon-btn" onclick="copyPostLink('{{ url('/feed#post-' . $post->id) }}', this)" title="Copy link" aria-label="Copy link to this post">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"/><circle cx="6" cy="12" r="3"/><circle cx="18" cy="19" r="3"/><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"/><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"/></svg>
                        </button>
                    </header>

                    @if($post->title)<h2 class="fd-title">{{ $post->title }}</h2>@endif
                    <div class="fd-text" data-clamp>{{ $post->content }}</div>
                    <button type="button" class="fd-more" hidden>See more</button>

                    @if($post->media_type === 'image' && $post->media_path)
                        <figure class="fd-media"><img src="{{ $post->media_path }}" alt="{{ $post->title ?: 'Post image' }}" loading="lazy"></figure>
                    @elseif($post->media_type === 'video' && $post->media_path)
                        <figure class="fd-media"><video controls preload="metadata" playsinline><source src="{{ $post->media_path }}"></video></figure>
                    @elseif($post->media_type === 'video' && $post->video_url)
                        <a class="fd-video-link" href="{{ $post->video_url }}" target="_blank" rel="noopener noreferrer">▶ Watch the video</a>
                    @endif

                    <div class="fd-counts">
                        <span><b class="fd-heart-mini">❤</b> <span id="like-count-{{ $post->id }}">{{ $post->likes_count }}</span></span>
                        <button type="button" onclick="toggleComments({{ $post->id }})"><span id="comments-count-{{ $post->id }}">{{ $post->comments->count() }}</span> comments</button>
                    </div>

                    <div class="fd-bar">
                        <button type="button" class="feed-like-btn fd-act" onclick="likePost({{ $post->id }})" id="like-btn-{{ $post->id }}">
                            <span class="like-icon">♡</span> Like
                        </button>
                        <button type="button" class="fd-act" onclick="toggleComments({{ $post->id }})">💬 Comment</button>
                        <button type="button" class="fd-act" onclick="copyPostLink('{{ url('/feed#post-' . $post->id) }}', this)">↗ Share</button>
                    </div>

                    <div class="feed-comments-section fd-comments" id="comments-box-{{ $post->id }}" @if($post->comments->isEmpty()) hidden @endif>
                        <div class="comments-list" id="comments-list-{{ $post->id }}">
                            @forelse($post->comments as $c)
                                <div class="comment-item fd-comment">
                                    @if($c->is_admin)
                                        <img src="{{ $avatar }}" alt="" class="fd-c-avatar">
                                    @else
                                        <span class="fd-c-avatar fd-c-initial">{{ strtoupper(mb_substr($c->user_name, 0, 1)) }}</span>
                                    @endif
                                    <div class="fd-c-bubble">
                                        <div class="fd-c-top"><strong>{{ $c->user_name }}</strong>@if($c->is_admin)<em>Author</em>@endif<time>{{ $c->created_at->diffForHumans() }}</time></div>
                                        <p>{{ $c->comment }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="no-comments-msg">No comments yet. Be the first to share your thoughts!</p>
                            @endforelse
                        </div>

                        <form class="comment-form fd-c-form" onsubmit="submitComment(event, {{ $post->id }})" action="{{ route('feed.comment', $post->id) }}" method="POST">
                            @csrf
                            @guest
                                <div class="fd-c-who">
                                    <input type="text" name="user_name" required minlength="2" maxlength="100" placeholder="Your name">
                                    <input type="email" name="user_email" maxlength="150" placeholder="Email (optional)">
                                </div>
                            @else
                                <input type="hidden" name="user_name" value="{{ $portfolio['brand']['full_name'] }}">
                            @endguest
                            <div class="fd-c-input">
                                <input type="text" name="comment" required minlength="2" maxlength="2000" placeholder="Write a comment..." autocomplete="off">
                                <button type="submit" aria-label="Post comment">Post</button>
                            </div>
                        </form>
                    </div>
                </article>
            @empty
                <div class="fd-empty">
                    <div>📢</div>
                    <h3>No posts in this category yet</h3>
                    <p>Check back soon or explore other categories.</p>
                    <a href="{{ route('feed.index') }}" class="fd-btn">View all posts</a>
                </div>
            @endforelse

            @if($posts->hasPages())
                <div class="fd-pages">{{ $posts->links() }}</div>
            @endif
        </div>
    </main>
</div>
@endsection

@push('scripts')
<script>
(function () {
    var csrf = '{{ csrf_token() }}';
    var liked = [];
    try { liked = JSON.parse(localStorage.getItem('portfolio_liked_posts') || '[]'); } catch (e) {}
    liked.forEach(function (id) { markLiked(document.getElementById('like-btn-' + id)); });

    // "See more" for long posts
    document.querySelectorAll('[data-clamp]').forEach(function (el) {
        if (el.scrollHeight > el.clientHeight + 4) {
            var btn = el.nextElementSibling;
            btn.hidden = false;
            btn.addEventListener('click', function () { el.classList.add('open'); btn.hidden = true; });
        } else {
            el.classList.add('open');
        }
    });

    function markLiked(btn) {
        if (!btn) return;
        btn.classList.add('liked');
        btn.querySelector('.like-icon').textContent = '♥';
    }

    window.likePost = function (id) {
        if (liked.indexOf(id) !== -1) return;
        var btn = document.getElementById('like-btn-' + id);
        markLiked(btn);
        fetch('/feed/' + id + '/like', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' } })
            .then(function (r) { return r.json(); })
            .then(function (d) {
                if (d.status === 'success') {
                    document.getElementById('like-count-' + id).textContent = d.likes_count;
                    liked.push(id);
                    try { localStorage.setItem('portfolio_liked_posts', JSON.stringify(liked)); } catch (e) {}
                }
            }).catch(function () {});
    };

    window.copyPostLink = function (url, btn) {
        var done = function () { if (btn) { var t = btn.innerHTML; btn.textContent = 'Copied ✓'; setTimeout(function () { btn.innerHTML = t; }, 1200); } };
        if (navigator.share && window.innerWidth < 700) { navigator.share({ url: url }).catch(function () {}); return; }
        if (navigator.clipboard) navigator.clipboard.writeText(url).then(done); else prompt('Copy this link:', url);
    };

    window.toggleComments = function (postId) {
        var box = document.getElementById('comments-box-' + postId);
        if (!box) return;
        box.hidden = !box.hidden;
        if (!box.hidden) { var i = box.querySelector('input[name="comment"]'); if (i) i.focus(); }
    };

    var esc = function (v) { var d = document.createElement('div'); d.textContent = String(v == null ? '' : v); return d.innerHTML; };

    window.submitComment = function (e, postId) {
        e.preventDefault();
        var form = e.target, btn = form.querySelector('button[type="submit"]'), input = form.querySelector('input[name="comment"]');
        if (!input.value.trim()) return;
        btn.disabled = true;
        fetch(form.action, { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }, body: new FormData(form) })
            .then(function (r) {
                if (r.status === 429) throw new Error('Too many comments. Please wait a minute.');
                if (r.status === 422) throw new Error('Please enter your name and a comment (2+ characters).');
                return r.json();
            })
            .then(function (d) {
                if (d.status !== 'success') return;
                var list = document.getElementById('comments-list-' + postId);
                var empty = list.querySelector('.no-comments-msg'); if (empty) empty.remove();
                document.getElementById('comments-count-' + postId).textContent = d.comments_count;
                var c = d.comment, item = document.createElement('div');
                item.className = 'comment-item fd-comment fd-new-comment';
                var initial = esc(String(c.user_name || '?').charAt(0).toUpperCase());
                item.innerHTML = (c.is_admin ? '<img src="{{ $avatar }}" alt="" class="fd-c-avatar">' : '<span class="fd-c-avatar fd-c-initial">' + initial + '</span>') +
                    '<div class="fd-c-bubble"><div class="fd-c-top"><strong>' + esc(c.user_name) + '</strong>' + (c.is_admin ? '<em>Author</em>' : '') + '<time>' + esc(c.created_at) + '</time></div><p>' + esc(c.comment) + '</p></div>';
                list.appendChild(item);
                input.value = '';
            })
            .catch(function (err) { alert(err.message || 'Could not post the comment.'); })
            .finally(function () { btn.disabled = false; });
    };
})();
</script>
@endpush
