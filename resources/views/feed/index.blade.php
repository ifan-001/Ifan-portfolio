@extends('layouts.app')

@section('title', 'Engineering Updates & Activity Feed | Hasnain Al Ifan')
@section('meta_description', 'Live professional milestones, industrial insights, and project updates from Hasnain Al Ifan.')

@section('content')
<div class="feed-page-wrapper" style="min-height:100vh; padding:100px 20px 60px 20px; max-width:860px; margin:0 auto;">
    
    {{-- Top Back Nav & Header --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; flex-wrap:wrap; gap:14px;">
        <a href="{{ route('home') }}" class="btn btn-secondary btn-sm" style="display:inline-flex; align-items:center; gap:8px; border-radius:24px; padding:8px 16px; font-weight:600; text-decoration:none;">
            <span>&larr;</span>
            <span>Back to Portfolio</span>
        </a>

        <div style="display:flex; align-items:center; gap:8px;">
            <span style="display:inline-block; width:8px; height:8px; border-radius:50%; background:#10B981; animation:pulse 2s infinite;"></span>
            <span style="font-size:0.85rem; color:var(--text-secondary); font-weight:500;">Live Feed Updates</span>
        </div>
    </div>

    {{-- Author Spotlight Card --}}
    <div class="feed-author-card" style="background:var(--bg-card); border:1px solid var(--border-card); border-radius:16px; padding:24px; margin-bottom:30px; display:flex; align-items:center; gap:20px; flex-wrap:wrap; box-shadow:0 10px 25px -5px rgba(0,0,0,0.1);">
        <img src="/images/profile.jpg" alt="Hasnain Al Ifan" style="width:72px; height:72px; border-radius:50%; object-fit:cover; border:3px solid var(--accent-blue); flex-shrink:0;">
        <div style="flex:1; min-width:260px;">
            <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px; flex-wrap:wrap;">
                <h1 style="font-size:1.4rem; font-weight:800; color:var(--text-primary); margin:0;">Hasnain Al Ifan</h1>
                <span style="color:#3B82F6; font-size:1.1rem;" title="Verified Software Engineer">✓</span>
                <span class="status-badge-pulse" style="font-size:0.75rem; background:rgba(59,130,246,0.15); color:var(--accent-blue); padding:3px 10px; border-radius:12px; font-weight:600;">
                    Software Engineer &amp; Architect
                </span>
            </div>
            <p style="color:var(--text-secondary); font-size:0.9rem; margin:0; line-height:1.5;">
                Sharing real-time engineering milestones, distributed system architecture, industrial innovations, and project breakthroughs.
            </p>
        </div>
    </div>

    {{-- Category Filters --}}
    <div class="feed-filter-tabs" style="display:flex; gap:8px; margin-bottom:28px; overflow-x:auto; padding-bottom:6px;">
        <a href="{{ route('feed.index', ['category' => 'all']) }}" 
           class="feed-tab-btn {{ ($category ?? 'all') === 'all' ? 'active' : '' }}">
            All Updates ({{ $counts['all'] }})
        </a>
        <a href="{{ route('feed.index', ['category' => 'professional']) }}" 
           class="feed-tab-btn {{ ($category ?? '') === 'professional' ? 'active' : '' }}">
            👔 Professional ({{ $counts['professional'] }})
        </a>
        <a href="{{ route('feed.index', ['category' => 'industrial']) }}" 
           class="feed-tab-btn {{ ($category ?? '') === 'industrial' ? 'active' : '' }}">
            🏭 Industrial ({{ $counts['industrial'] }})
        </a>
        <a href="{{ route('feed.index', ['category' => 'project']) }}" 
           class="feed-tab-btn {{ ($category ?? '') === 'project' ? 'active' : '' }}">
            🚀 Projects ({{ $counts['project'] }})
        </a>
        <a href="{{ route('feed.index', ['category' => 'social']) }}" 
           class="feed-tab-btn {{ ($category ?? '') === 'social' ? 'active' : '' }}">
            👥 Social ({{ $counts['social'] }})
        </a>
        <a href="{{ route('feed.index', ['category' => 'research']) }}" 
           class="feed-tab-btn {{ ($category ?? '') === 'research' ? 'active' : '' }}">
            🔬 Research ({{ $counts['research'] }})
        </a>
    </div>

    {{-- Posts Stream --}}
    <div class="feed-stream" style="display:flex; flex-direction:column; gap:24px;">
        @forelse($posts as $post)
            <article class="feed-post-card" id="post-{{ $post->id }}" style="background:var(--bg-card); border:1px solid var(--border-card); border-radius:16px; padding:24px; box-shadow:0 4px 20px -2px rgba(0,0,0,0.06); transition:transform 0.2s, box-shadow 0.2s;">
                
                {{-- Post Header --}}
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:16px;">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <img src="/images/profile.jpg" alt="Author" style="width:48px; height:48px; border-radius:50%; object-fit:cover; border:2px solid var(--accent-blue);">
                        <div>
                            <div style="font-weight:700; color:var(--text-primary); font-size:1rem; display:flex; align-items:center; gap:6px;">
                                <span>{{ $post->author_name }}</span>
                                <span style="color:#3B82F6; font-size:0.85rem;" title="Verified Profile">✓</span>
                            </div>
                            <div style="font-size:0.8rem; color:var(--text-secondary); display:flex; align-items:center; gap:8px; margin-top:2px;">
                                <span style="background:rgba(255,255,255,0.06); color:{{ $post->category_color }}; border:1px solid {{ $post->category_color }}; padding:1px 8px; border-radius:6px; font-weight:600; font-size:0.75rem;">
                                    {{ $post->category_label }}
                                </span>
                                <span>&bull;</span>
                                <time datetime="{{ $post->published_at ? $post->published_at->toIso8601String() : '' }}">
                                    {{ $post->published_at ? $post->published_at->diffForHumans() : 'Recently' }}
                                </time>
                                @if($post->is_pinned)
                                    <span>&bull;</span>
                                    <span style="color:#F59E0B; font-weight:600; display:inline-flex; align-items:center; gap:3px;">
                                        <span>📌</span> Pinned
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Share button --}}
                    <button type="button" onclick="copyPostLink('{{ url('/feed#post-' . $post->id) }}')" 
                            class="feed-share-btn" title="Share or Copy Link" style="background:none; border:none; color:var(--text-secondary); cursor:pointer; padding:6px; border-radius:50%;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg>
                    </button>
                </div>

                {{-- Headline --}}
                @if($post->title)
                    <h2 style="font-size:1.2rem; font-weight:700; color:var(--text-primary); margin:0 0 12px 0; line-height:1.4;">
                        {{ $post->title }}
                    </h2>
                @endif

                {{-- Post Text Content --}}
                <div style="font-size:1rem; line-height:1.7; color:var(--text-primary); margin-bottom:18px; white-space:pre-wrap; word-break:break-word;">
{{ $post->content }}
                </div>

                {{-- Media Attachment --}}
                @if($post->media_type === 'image' && $post->media_path)
                    <div style="margin-bottom:18px; border-radius:12px; overflow:hidden; border:1px solid var(--border-card); background:#000; display:flex; justify-content:center; max-height:480px;">
                        <img src="{{ $post->media_path }}" alt="Post image" loading="lazy" style="max-width:100%; max-height:480px; object-fit:contain;">
                    </div>
                @elseif($post->media_type === 'video')
                    <div style="margin-bottom:18px; border-radius:12px; overflow:hidden; border:1px solid var(--border-card); background:#000;">
                        @if($post->media_path)
                            <video controls preload="metadata" style="width:100%; max-height:480px; display:block;">
                                <source src="{{ $post->media_path }}">
                                Your browser does not support the video tag.
                            </video>
                        @elseif($post->video_url)
                            <div style="padding:16px; background:rgba(0,0,0,0.2); display:flex; align-items:center; justify-content:space-between; gap:12px;">
                                <span style="color:var(--text-secondary); font-size:0.9rem;">Attached Video Resource:</span>
                                <a href="{{ $post->video_url }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm" style="display:inline-flex; align-items:center; gap:6px;">
                                    <span>▶ Watch Video</span>
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Interaction Bar --}}
                <div style="display:flex; justify-content:space-between; align-items:center; padding-top:14px; border-top:1px solid var(--border-card); font-size:0.9rem;">
                    <button type="button" class="feed-like-btn" onclick="likePost({{ $post->id }})" id="like-btn-{{ $post->id }}"
                            style="background:none; border:none; cursor:pointer; display:inline-flex; align-items:center; gap:8px; color:var(--text-secondary); font-weight:600; padding:6px 12px; border-radius:20px; transition:all 0.15s;">
                        <span class="like-icon" style="font-size:1.1rem; transition:transform 0.15s;">🤍</span>
                        <span id="like-count-{{ $post->id }}">{{ $post->likes_count }}</span>
                        <span>Likes</span>
                    </button>

                    <div style="color:var(--text-secondary); font-size:0.8rem;">
                        <span>Hasnain Official Post</span>
                    </div>
                </div>
            </article>
        @empty
            <div style="text-align:center; padding:60px 20px; background:var(--bg-card); border-radius:16px; border:1px dashed var(--border-card);">
                <div style="font-size:2.5rem; margin-bottom:12px;">📢</div>
                <h3 style="color:var(--text-primary); font-size:1.15rem; font-weight:700; margin:0 0 6px 0;">No updates in this category yet</h3>
                <p style="color:var(--text-secondary); font-size:0.9rem; margin:0 0 16px 0;">Check back soon or explore other categories.</p>
                <a href="{{ route('feed.index') }}" class="btn btn-secondary btn-sm">View All Updates</a>
            </div>
        @endforelse

        {{-- Pagination --}}
        @if($posts->hasPages())
            <div style="margin-top:24px; display:flex; justify-content:center;">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</div>

<style>
.feed-tab-btn {
    display: inline-block;
    padding: 8px 16px;
    border-radius: 20px;
    background: var(--bg-card);
    border: 1px solid var(--border-card);
    color: var(--text-secondary);
    font-size: 0.85rem;
    font-weight: 500;
    text-decoration: none;
    white-space: nowrap;
    transition: all 0.2s;
}
.feed-tab-btn:hover {
    border-color: var(--accent-blue);
    color: var(--text-primary);
}
.feed-tab-btn.active {
    background: var(--accent-blue) !important;
    color: #ffffff !important;
    border-color: var(--accent-blue) !important;
    font-weight: 600;
}
.feed-post-card:hover {
    box-shadow: 0 12px 30px -4px rgba(0,0,0,0.12);
}
.feed-like-btn.liked .like-icon {
    content: '❤️';
    transform: scale(1.2);
}
.feed-like-btn.liked {
    color: #EF4444 !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Check local storage for liked posts
    const likedPosts = JSON.parse(localStorage.getItem('portfolio_liked_posts') || '[]');
    likedPosts.forEach(id => {
        const btn = document.getElementById('like-btn-' + id);
        if (btn) {
            btn.classList.add('liked');
            btn.querySelector('.like-icon').textContent = '❤️';
        }
    });
});

function likePost(id) {
    const btn = document.getElementById('like-btn-' + id);
    const countEl = document.getElementById('like-count-' + id);
    const likedPosts = JSON.parse(localStorage.getItem('portfolio_liked_posts') || '[]');

    if (likedPosts.includes(id)) {
        return; // already liked
    }

    fetch(`/feed/${id}/like`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            countEl.textContent = data.likes_count;
            btn.classList.add('liked');
            btn.querySelector('.like-icon').textContent = '❤️';
            likedPosts.push(id);
            localStorage.setItem('portfolio_liked_posts', JSON.stringify(likedPosts));
        }
    })
    .catch(err => console.error(err));
}

function copyPostLink(url) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(() => {
            alert('Post link copied to clipboard!');
        });
    } else {
        prompt('Copy this link:', url);
    }
}
</script>
@endsection
