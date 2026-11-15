@extends('admin.layouts.admin')
@section('page_title', 'Feed posts')

@section('title', 'Posts & Feed Management')

@section('content')
<div style="max-width:850px; margin:0 auto;">
    <div class="content-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px; margin-bottom:20px;">
        <div>
            <h1 style="font-size:1.5rem; font-weight:700; color:var(--text-primary); margin:0 0 4px 0; display:flex; align-items:center; gap:8px;">
                <span>📢 Posts &amp; Content Feed</span>
            </h1>
            <p style="color:var(--text-secondary); font-size:0.875rem; margin:0;">
                Publish Facebook/LinkedIn-style professional, industrial, social, and project updates for your portfolio visitors.
            </p>
        </div>

        <div>
            <a href="{{ route('feed.index') }}" target="_blank" class="btn btn-secondary btn-sm" style="display:inline-flex; align-items:center; gap:6px;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                <span>View Public Feed</span>
            </a>
        </div>
    </div>

    @if(session('status'))
        <div style="background:rgba(16,185,129,0.15); border:1px solid #10B981; color:#10B981; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:0.9rem;">
            {{ session('status') }}
        </div>
    @endif

    @if($errors->any())
        <div id="feed-errors" role="alert" style="background:rgba(239,68,68,0.15); border:1px solid #EF4444; color:#EF4444; padding:12px 16px; border-radius:8px; margin-bottom:20px; font-size:0.9rem;">
            <ul style="margin:0; padding-left:20px;">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Facebook / LinkedIn Style Post Composer Card --}}
    <div style="background:var(--bg-secondary); border:1px solid var(--border-color); border-radius:12px; padding:20px; margin-bottom:30px; box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
            <img src="/images/profile.jpg" alt="Admin" style="width:44px; height:44px; border-radius:50%; object-fit:cover; border:2px solid var(--accent-blue);">
            <div>
                <div style="font-weight:700; color:var(--text-primary); font-size:0.95rem;">Hasnain Al Ifan</div>
                <div style="font-size:0.75rem; color:var(--text-secondary);">Create a new update or milestone for your portfolio feed</div>
            </div>
        </div>

        <form action="{{ route('admin.feed.store') }}" method="POST" enctype="multipart/form-data" id="post-composer-form">
            @csrf

            {{-- Category Pill Selector --}}
            <div style="margin-bottom:14px;">
                <label style="display:block; font-size:0.8rem; font-weight:600; color:var(--text-secondary); text-transform:uppercase; margin-bottom:6px;">
                    Select Post Category:
                </label>
                <div style="display:flex; gap:8px; flex-wrap:wrap;">
                    <label class="feed-cat-label" style="cursor:pointer;">
                        <input type="radio" name="category" value="professional" {{ old('category', 'professional') === 'professional' ? 'checked' : '' }} style="display:none;">
                        <span class="cat-pill">👔 Professional</span>
                    </label>
                    <label class="feed-cat-label" style="cursor:pointer;">
                        <input type="radio" name="category" value="industrial" {{ old('category', 'professional') === 'industrial' ? 'checked' : '' }} style="display:none;">
                        <span class="cat-pill">🏭 Industrial</span>
                    </label>
                    <label class="feed-cat-label" style="cursor:pointer;">
                        <input type="radio" name="category" value="project" {{ old('category', 'professional') === 'project' ? 'checked' : '' }} style="display:none;">
                        <span class="cat-pill">🚀 Project Update</span>
                    </label>
                    <label class="feed-cat-label" style="cursor:pointer;">
                        <input type="radio" name="category" value="social" {{ old('category', 'professional') === 'social' ? 'checked' : '' }} style="display:none;">
                        <span class="cat-pill">👥 Social</span>
                    </label>
                    <label class="feed-cat-label" style="cursor:pointer;">
                        <input type="radio" name="category" value="research" {{ old('category', 'professional') === 'research' ? 'checked' : '' }} style="display:none;">
                        <span class="cat-pill">🔬 Research &amp; Tech</span>
                    </label>
                </div>
            </div>

            {{-- Optional Title --}}
            <div style="margin-bottom:12px;">
                <input type="text" name="title" value="{{ old('title') }}" placeholder="Post Headline / Title (Optional)"
                       style="width:100%; background:var(--bg-primary); border:1px solid var(--border-color); color:var(--text-primary); padding:10px 14px; border-radius:8px; font-size:0.9rem; font-weight:600;">
            </div>

            {{-- Post Content Textarea --}}
            <div style="margin-bottom:14px;">
                <textarea name="content" rows="4" required placeholder="What's happening? Share a project breakthrough, technical learning, or industry insight..."
                          style="width:100%; background:var(--bg-primary); border:1px solid var(--border-color); color:var(--text-primary); padding:14px; border-radius:8px; font-size:0.95rem; line-height:1.5; resize:vertical; font-family:inherit;">{{ old('content') }}</textarea>
            </div>

            {{-- Media Attachment Controls --}}
            <div style="background:rgba(0,0,0,0.15); border:1px dashed var(--border-color); border-radius:8px; padding:14px; margin-bottom:16px;">
                <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px; margin-bottom:10px;">
                    <span style="font-size:0.85rem; font-weight:600; color:var(--text-primary); display:flex; align-items:center; gap:6px;">
                        <span>📎</span>
                        <span>Attach Media (Photo or Video):</span>
                    </span>

                    <div class="media-opts">
                        <label class="media-opt">
                            <input type="radio" name="media_type" value="none" checked onchange="toggleMediaInputs('none')">
                            <span>No Media</span>
                        </label>
                        <label class="media-opt">
                            <input type="radio" name="media_type" value="image" onchange="toggleMediaInputs('image')">
                            <span>📷 Photo</span>
                        </label>
                        <label class="media-opt">
                            <input type="radio" name="media_type" value="video" onchange="toggleMediaInputs('video')">
                            <span>🎥 Video</span>
                        </label>
                    </div>
                </div>

                {{-- File upload input --}}
                <div id="media-file-block" style="display:none; margin-top:10px;">
                    <label style="display:block; font-size:0.8rem; color:var(--text-secondary); margin-bottom:4px;">
                        Choose a photo (JPG, PNG, WEBP) or a video (MP4, WEBM, max 40 MB). Big photos are made smaller automatically.
                    </label>
                    <input type="file" name="media_file" id="media-file-input" accept="image/*,video/mp4,video/webm,video/quicktime"
                           style="width:100%; font-size:0.85rem; color:var(--text-primary);">
                    <div id="media-note" class="media-note" hidden></div>
                    <div id="media-preview" class="media-preview" hidden></div>
                </div>

                {{-- Video URL input (YouTube / external) --}}
                <div id="video-url-block" style="display:none; margin-top:10px;">
                    <label style="display:block; font-size:0.8rem; color:var(--text-secondary); margin-bottom:4px;">
                        Or Paste Video URL (YouTube, Vimeo, or MP4 link):
                    </label>
                    <input type="url" name="video_url" placeholder="https://www.youtube.com/watch?v=... or direct MP4 link"
                           style="width:100%; background:var(--bg-primary); border:1px solid var(--border-color); color:var(--text-primary); padding:8px 12px; border-radius:6px; font-size:0.85rem;">
                </div>
            </div>

            {{-- Bottom Toolbar --}}
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                <label style="display:inline-flex; align-items:center; gap:8px; font-size:0.85rem; color:var(--text-secondary); cursor:pointer;">
                    <input type="checkbox" name="is_pinned" value="1" {{ old('is_pinned') ? 'checked' : '' }} style="accent-color:var(--accent-blue);">
                    <span>📌 Pin this post to the top of the feed</span>
                </label>

                <button type="submit" id="feed-submit" class="btn btn-primary" style="padding:9px 22px; font-weight:600; display:inline-flex; align-items:center; gap:6px;">
                    <span id="feed-submit-label">Publish Post</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                </button>
            </div>
        </form>
    </div>

    {{-- Filter Bar --}}
    <div style="display:flex; gap:8px; margin-bottom:20px; flex-wrap:wrap;">
        <a href="{{ route('admin.feed.index', ['category' => 'all']) }}" class="btn btn-sm {{ ($category ?? 'all') === 'all' ? 'btn-primary' : 'btn-secondary' }}" style="border-radius:20px;">
            All Posts ({{ $counts['all'] }})
        </a>
        <a href="{{ route('admin.feed.index', ['category' => 'professional']) }}" class="btn btn-sm {{ ($category ?? '') === 'professional' ? 'btn-primary' : 'btn-secondary' }}" style="border-radius:20px;">
            👔 Professional ({{ $counts['professional'] }})
        </a>
        <a href="{{ route('admin.feed.index', ['category' => 'industrial']) }}" class="btn btn-sm {{ ($category ?? '') === 'industrial' ? 'btn-primary' : 'btn-secondary' }}" style="border-radius:20px;">
            🏭 Industrial ({{ $counts['industrial'] }})
        </a>
        <a href="{{ route('admin.feed.index', ['category' => 'project']) }}" class="btn btn-sm {{ ($category ?? '') === 'project' ? 'btn-primary' : 'btn-secondary' }}" style="border-radius:20px;">
            🚀 Projects ({{ $counts['project'] }})
        </a>
        <a href="{{ route('admin.feed.index', ['category' => 'social']) }}" class="btn btn-sm {{ ($category ?? '') === 'social' ? 'btn-primary' : 'btn-secondary' }}" style="border-radius:20px;">
            👥 Social ({{ $counts['social'] }})
        </a>
        <a href="{{ route('admin.feed.index', ['category' => 'research']) }}" class="btn btn-sm {{ ($category ?? '') === 'research' ? 'btn-primary' : 'btn-secondary' }}" style="border-radius:20px;">
            🔬 Research ({{ $counts['research'] }})
        </a>
    </div>

    {{-- Posts Stream --}}
    <div style="display:flex; flex-direction:column; gap:20px;">
        @forelse($posts as $post)
            <div id="post-{{ $post->id }}" class="feed-card {{ $post->is_pinned ? 'is-pinned' : '' }}">
                {{-- Author Header --}}
                <div class="feed-card-head">
                    <div class="feed-card-author">
                        <img src="/images/profile.jpg" alt="Author" style="width:42px; height:42px; border-radius:50%; object-fit:cover; border:2px solid var(--accent-blue);">
                        <div>
                            <div style="font-weight:700; color:var(--text-primary); font-size:0.95rem; display:flex; align-items:center; gap:6px;">
                                <span>{{ $post->author_name }}</span>
                                <span style="color:#3B82F6; font-size:0.8rem;" title="Verified Engineer">✓</span>
                            </div>
                            <div class="feed-card-meta">
                                <span style="background:rgba(255,255,255,0.06); color:{{ $post->category_color }}; border:1px solid {{ $post->category_color }}; padding:1px 6px; border-radius:4px; font-weight:600;">
                                    {{ strtoupper($post->category) }}
                                </span>
                                <span>&bull;</span>
                                <span>{{ $post->published_at ? $post->published_at->diffForHumans() : 'Draft' }}</span>
                                @if($post->is_pinned)
                                    <span>&bull;</span>
                                    <span style="color:#F59E0B; font-weight:600;">📌 Pinned</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="feed-card-actions">
                        <form action="{{ route('admin.feed.pin', $post->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="feed-act {{ $post->is_pinned ? 'is-on' : '' }}" title="{{ $post->is_pinned ? 'Unpin this post' : 'Pin this post to the top of the feed' }}">
                                <span aria-hidden="true">📌</span><span>{{ $post->is_pinned ? 'Unpin' : 'Pin' }}</span>
                            </button>
                        </form>

                        <form action="{{ route('admin.feed.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Delete this post? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="feed-act feed-act-danger" title="Delete this post">
                                <span aria-hidden="true">🗑️</span><span>Delete</span>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Post Headline / Title --}}
                @if($post->title)
                    <h3 style="font-size:1.1rem; font-weight:700; color:var(--text-primary); margin:0 0 10px 0;">
                        {{ $post->title }}
                    </h3>
                @endif

                {{-- Post Body --}}
                <div style="font-size:0.95rem; line-height:1.6; color:var(--text-primary); margin-bottom:14px; white-space:pre-wrap;">
{{ $post->content }}
                </div>

                {{-- Media Attachment --}}
                @if($post->media_type === 'image' && $post->media_path)
                    <div style="margin-bottom:14px; border-radius:8px; overflow:hidden; border:1px solid var(--border-color); max-height:400px; display:flex; justify-content:center; background:#000;">
                        <img src="{{ $post->media_path }}" alt="Post attachment" style="max-width:100%; max-height:400px; object-fit:contain;">
                    </div>
                @elseif($post->media_type === 'video')
                    <div style="margin-bottom:14px; border-radius:8px; overflow:hidden; border:1px solid var(--border-color); background:#000;">
                        @if($post->media_path)
                            <video controls style="width:100%; max-height:400px;">
                                <source src="{{ $post->media_path }}">
                                Your browser does not support HTML5 video.
                            </video>
                        @elseif($post->video_url)
                            <div style="padding:14px; background:rgba(0,0,0,0.2);">
                                <a href="{{ $post->video_url }}" target="_blank" style="color:var(--accent-blue); text-decoration:none; display:inline-flex; align-items:center; gap:6px;">
                                    <span>▶ Watch Video:</span>
                                    <span>{{ $post->video_url }}</span>
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Post Footer / Stats --}}
                <div style="display:flex; justify-content:space-between; align-items:center; padding-top:12px; border-top:1px solid var(--border-color); font-size:0.85rem; color:var(--text-secondary); margin-bottom:12px;">
                    <div style="display:flex; gap:16px;">
                        <span>❤️ {{ $post->likes_count }} Likes</span>
                        <span>💬 {{ $post->comments->count() }} Comments</span>
                    </div>

                    <div>
                        <a href="{{ route('feed.index') }}" target="_blank" style="color:var(--accent-blue); text-decoration:none; font-size:0.8rem;">
                            View live on portfolio &rarr;
                        </a>
                    </div>
                </div>

                {{-- Comments Moderation List --}}
                @if($post->comments->isNotEmpty())
                    <div style="background:rgba(0,0,0,0.2); border-radius:8px; padding:12px; margin-top:8px;">
                        <div style="font-size:0.75rem; text-transform:uppercase; font-weight:700; color:var(--text-secondary); margin-bottom:8px;">
                            Comments ({{ $post->comments->count() }}):
                        </div>
                        <div style="display:flex; flex-direction:column; gap:8px;">
                            @foreach($post->comments as $comment)
                                <div style="display:flex; justify-content:space-between; align-items:flex-start; background:var(--bg-primary); padding:8px 12px; border-radius:6px; font-size:0.825rem;">
                                    <div style="flex:1;">
                                        <div style="display:flex; align-items:center; gap:6px; margin-bottom:2px;">
                                            <strong style="color:var(--text-primary);">{{ $comment->user_name }}</strong>
                                            @if($comment->is_admin)
                                                <span style="font-size:0.65rem; background:var(--accent-blue); color:#fff; padding:0 4px; border-radius:3px;">Admin</span>
                                            @endif
                                            <span style="color:var(--text-secondary); font-size:0.75rem;">&bull; {{ $comment->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div style="color:var(--text-secondary);">{{ $comment->comment }}</div>
                                    </div>

                                    <form action="{{ route('admin.feed.comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Delete this comment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-secondary btn-xs" style="color:#EF4444; padding:2px 6px;" title="Delete comment">
                                            ✕
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div style="text-align:center; padding:50px 20px; background:var(--bg-secondary); border-radius:12px; border:1px dashed var(--border-color);">
                <p style="color:var(--text-secondary); margin:0 0 12px 0;">No posts found in this category.</p>
                <button type="button" onclick="document.querySelector('#post-composer-form textarea').focus()" class="btn btn-primary btn-sm">
                    Write your first post above
                </button>
            </div>
        @endforelse

        {{-- Pagination --}}
        @if($posts->hasPages())
            <div style="margin-top:20px;">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
</div>

<style>
.feed-card { background:var(--bg-secondary); border:1px solid var(--border-color); border-radius:12px; padding:20px; box-shadow:0 2px 4px rgba(0,0,0,0.05); scroll-margin-top:90px; }
.feed-card.is-pinned { border-color:rgba(245,158,11,.55); box-shadow:0 0 0 1px rgba(245,158,11,.25), 0 6px 18px -10px rgba(245,158,11,.5); }
.feed-card-head { display:flex; justify-content:space-between; align-items:flex-start; gap:12px; margin-bottom:14px; }
.feed-card-author { display:flex; align-items:center; gap:12px; min-width:0; flex:1 1 auto; }
.feed-card-author > div { min-width:0; }
.feed-card-meta { font-size:0.75rem; color:var(--text-secondary); display:flex; align-items:center; flex-wrap:wrap; gap:4px 8px; margin-top:2px; }
.feed-card-meta span { white-space:nowrap; }
.feed-card-actions { display:flex; align-items:center; gap:8px; flex:0 0 auto; }
.feed-card-actions form { display:inline-flex; margin:0; }
.feed-act { display:inline-flex; align-items:center; gap:6px; white-space:nowrap; padding:7px 12px; border-radius:999px; font-size:.8rem; font-weight:600; line-height:1; cursor:pointer; background:var(--bg-primary); color:var(--text-primary); border:1px solid var(--border-color); transition:border-color .15s, background .15s; }
.feed-act:hover { border-color:var(--accent-blue); }
.feed-act.is-on { background:rgba(245,158,11,.14); border-color:rgba(245,158,11,.55); color:#FBBF24; }
.feed-act-danger { color:#F87171; }
.feed-act-danger:hover { border-color:#EF4444; background:rgba(239,68,68,.1); }
@media (max-width: 560px) {
  .feed-card { padding:16px; }
  .feed-card-head { flex-direction:column; align-items:stretch; }
  .feed-card-actions { justify-content:flex-end; border-top:1px solid var(--border-color); padding-top:10px; }
}
.media-opts { display:flex; gap:8px; flex-wrap:wrap; }
.media-opt { cursor:pointer; display:inline-flex; }
.media-opt input { position:absolute; opacity:0; pointer-events:none; }
.media-opt span { display:inline-flex; align-items:center; gap:4px; padding:6px 12px; border-radius:999px; font-size:.8rem; font-weight:600; background:var(--bg-primary); color:var(--text-secondary); border:1px solid var(--border-color); transition:all .15s; }
.media-opt input:checked + span { background:var(--accent-blue); border-color:var(--accent-blue); color:#fff; }
.media-opt input:focus-visible + span { outline:2px solid var(--accent-blue); outline-offset:2px; }
.media-note { margin-top:8px; font-size:.8rem; color:var(--text-secondary); }
.media-note.is-error { color:#F87171; font-weight:600; }
.media-preview { margin-top:10px; border-radius:8px; overflow:hidden; background:#000; border:1px solid var(--border-color); display:flex; justify-content:center; }
.media-preview img, .media-preview video { max-width:100%; max-height:280px; object-fit:contain; display:block; }
#feed-submit[disabled] { opacity:.7; cursor:progress; }
.feed-cat-label input:checked + .cat-pill {
    background: var(--accent-blue) !important;
    color: #fff !important;
    border-color: var(--accent-blue) !important;
}
.cat-pill {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 20px;
    background: var(--bg-primary);
    border: 1px solid var(--border-color);
    color: var(--text-secondary);
    font-size: 0.825rem;
    font-weight: 500;
    transition: all 0.15s;
}
.cat-pill:hover {
    border-color: var(--accent-blue);
    color: var(--text-primary);
}
</style>

<script>
function toggleMediaInputs(type) {
    const fileBlock = document.getElementById('media-file-block');
    const urlBlock = document.getElementById('video-url-block');
    fileBlock.style.display = (type === 'image' || type === 'video') ? 'block' : 'none';
    urlBlock.style.display = type === 'video' ? 'block' : 'none';
}

(function () {
    const errors = document.getElementById('feed-errors');
    if (errors) errors.scrollIntoView({ block: 'center' });

    const form = document.getElementById('post-composer-form');
    const input = document.getElementById('media-file-input');
    const note = document.getElementById('media-note');
    const preview = document.getElementById('media-preview');
    const submit = document.getElementById('feed-submit');
    const label = document.getElementById('feed-submit-label');
    if (!form || !input) return;

    const MAX_BYTES = 40 * 1024 * 1024;
    const MAX_SIDE = 2048;
    let busy = false;      // a photo is still being prepared
    let blocked = false;   // the chosen file cannot be posted

    function say(text, isError) {
        note.hidden = !text;
        note.textContent = text || '';
        note.classList.toggle('is-error', !!isError);
    }
    function showPreview(file) {
        preview.innerHTML = '';
        if (!file) { preview.hidden = true; return; }
        const url = URL.createObjectURL(file);
        const el = document.createElement(file.type.startsWith('video/') ? 'video' : 'img');
        el.src = url;
        if (el.tagName === 'VIDEO') { el.controls = true; el.muted = true; }
        preview.appendChild(el);
        preview.hidden = false;
    }
    function mb(n) { return (n / 1048576).toFixed(1) + ' MB'; }
    function setType(type) {
        const r = form.querySelector('input[name=media_type][value=' + type + ']');
        if (r && !r.checked) { r.checked = true; toggleMediaInputs(type); }
    }
    function decode(file) {
        return new Promise(function (resolve, reject) {
            const img = new Image();
            const url = URL.createObjectURL(file);
            img.onload = function () { URL.revokeObjectURL(url); resolve(img); };
            img.onerror = function () { URL.revokeObjectURL(url); reject(new Error('decode')); };
            img.src = url;
        });
    }

    input.addEventListener('change', async function () {
        blocked = false;
        const file = input.files && input.files[0];
        if (!file) { say(''); showPreview(null); return; }

        const isVideo = file.type.startsWith('video/') || /\.(mp4|webm|mov)$/i.test(file.name);
        setType(isVideo ? 'video' : 'image');

        if (isVideo) {
            if (file.size > MAX_BYTES) {
                blocked = true;
                say('This video is ' + mb(file.size) + '. The limit is 40 MB, so please choose a shorter video or paste a YouTube link instead.', true);
                showPreview(null);
                return;
            }
            say('Video ready (' + mb(file.size) + ').');
            showPreview(file);
            return;
        }

        // Photos: animated GIFs stay as they are; everything else becomes a light JPG the website can show
        if (file.type === 'image/gif') {
            blocked = file.size > MAX_BYTES;
            say(blocked ? 'This GIF is larger than 40 MB.' : 'Photo ready (' + mb(file.size) + ').', blocked);
            showPreview(blocked ? null : file);
            return;
        }

        busy = true;
        say('Preparing photo…');
        try {
            const img = await decode(file);
            const scale = Math.min(1, MAX_SIDE / Math.max(img.naturalWidth, img.naturalHeight));
            const needsWork = scale < 1 || file.size > 1.5 * 1048576 || !/^image\/(jpeg|png|webp)$/.test(file.type);
            let out = file;
            if (needsWork) {
                const c = document.createElement('canvas');
                c.width = Math.round(img.naturalWidth * scale);
                c.height = Math.round(img.naturalHeight * scale);
                const g = c.getContext('2d');
                g.fillStyle = '#fff';
                g.fillRect(0, 0, c.width, c.height);
                g.drawImage(img, 0, 0, c.width, c.height);
                const blob = await new Promise(function (r) { c.toBlob(r, 'image/jpeg', 0.86); });
                if (blob) {
                    out = new File([blob], (file.name.replace(/\.[^.]+$/, '') || 'photo') + '.jpg', { type: 'image/jpeg' });
                    const dt = new DataTransfer();
                    dt.items.add(out);
                    input.files = dt.files;
                }
            }
            say('Photo ready (' + mb(out.size) + ').');
            showPreview(out);
        } catch (e) {
            blocked = true;
            say('This photo format cannot be opened here (often a HEIC photo). Please choose a JPG or PNG photo, or take a screenshot of it and post that.', true);
            showPreview(null);
        } finally {
            busy = false;
        }
    });

    form.addEventListener('submit', function (e) {
        if (busy) { e.preventDefault(); say('Please wait a moment, the photo is still being prepared…'); return; }
        if (blocked) { e.preventDefault(); note.scrollIntoView({ block: 'center' }); return; }
        submit.disabled = true;
        label.textContent = (input.files && input.files.length) ? 'Uploading…' : 'Publishing…';
    });

    // Coming back to this page from history: re-enable the button
    window.addEventListener('pageshow', function () { submit.disabled = false; label.textContent = 'Publish Post'; });
})();
</script>
@endsection
