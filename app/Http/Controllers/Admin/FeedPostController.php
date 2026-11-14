<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeedPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class FeedPostController extends Controller
{
    /**
     * Display post feed manager.
     */
    public function index(Request $request)
    {
        $category = $request->query('category', 'all');
        $query = FeedPost::with('comments')->orderByDesc('is_pinned')->orderByDesc('published_at');

        if ($category !== 'all') {
            $query->where('category', $category);
        }

        $posts = $query->paginate(15);

        $counts = [
            'all' => FeedPost::count(),
            'professional' => FeedPost::where('category', 'professional')->count(),
            'industrial' => FeedPost::where('category', 'industrial')->count(),
            'project' => FeedPost::where('category', 'project')->count(),
            'social' => FeedPost::where('category', 'social')->count(),
            'research' => FeedPost::where('category', 'research')->count(),
        ];

        return view('admin.feed.index', compact('posts', 'counts', 'category'));
    }

    /**
     * Store a newly created feed post.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|in:professional,social,industrial,project,research',
            'title' => 'nullable|string|max:200',
            'content' => 'required|string|min:3|max:10000',
            'media_type' => 'required|in:none,image,video',
            'media_file' => 'nullable|file|mimes:jpeg,png,jpg,webp,gif,avif,mp4,webm,mov|max:51200',
            'video_url' => 'nullable|url|max:500',
            'is_pinned' => 'nullable|boolean',
        ]);

        $mediaPath = null;
        $mediaType = $validated['media_type'];

        if ($request->hasFile('media_file')) {
            $file = $request->file('media_file');
            $extension = strtolower($file->guessExtension() ?: 'bin');
            $fileName = 'post_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;

            $file->move(public_path('uploads/feed'), $fileName);
            $mediaPath = '/uploads/feed/' . $fileName;

            // Automatically detect image vs video if needed
            if (in_array($extension, ['mp4', 'webm', 'mov'])) {
                $mediaType = 'video';
            } elseif (in_array($extension, ['jpeg', 'png', 'jpg', 'webp', 'gif', 'avif'])) {
                $mediaType = 'image';
            }
        } elseif (!empty($validated['video_url'])) {
            $mediaType = 'video';
        }

        FeedPost::create([
            'author_name' => 'Hasnain Al Ifan',
            'title' => $validated['title'] ?? null,
            'category' => $validated['category'],
            'content' => $validated['content'],
            'media_type' => $mediaType,
            'media_path' => $mediaPath,
            'video_url' => $validated['video_url'] ?? null,
            'is_pinned' => $request->boolean('is_pinned'),
            'status' => 'published',
            'published_at' => now(),
        ]);

        return redirect()->route('admin.feed.index')->with('status', 'Post published to your portfolio feed successfully!');
    }

    /**
     * Update an existing post.
     */
    public function update(Request $request, FeedPost $post)
    {
        $validated = $request->validate([
            'category' => 'required|in:professional,social,industrial,project,research',
            'title' => 'nullable|string|max:200',
            'content' => 'required|string|min:3|max:10000',
            'media_type' => 'required|in:none,image,video',
            'media_file' => 'nullable|file|mimes:jpeg,png,jpg,webp,gif,avif,mp4,webm,mov|max:51200',
            'video_url' => 'nullable|url|max:500',
            'is_pinned' => 'nullable|boolean',
        ]);

        $mediaPath = $post->media_path;
        $mediaType = $validated['media_type'];

        if ($request->hasFile('media_file')) {
            // Delete old file if present in uploads/feed
            if ($mediaPath && str_starts_with($mediaPath, '/uploads/feed/') && File::exists(public_path($mediaPath))) {
                File::delete(public_path($mediaPath));
            }

            $file = $request->file('media_file');
            $extension = strtolower($file->guessExtension() ?: 'bin');
            $fileName = 'post_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $extension;

            $file->move(public_path('uploads/feed'), $fileName);
            $mediaPath = '/uploads/feed/' . $fileName;

            if (in_array($extension, ['mp4', 'webm', 'mov'])) {
                $mediaType = 'video';
            } else {
                $mediaType = 'image';
            }
        } elseif (!empty($validated['video_url'])) {
            $mediaType = 'video';
        }

        $post->update([
            'title' => $validated['title'] ?? null,
            'category' => $validated['category'],
            'content' => $validated['content'],
            'media_type' => $mediaType,
            'media_path' => $mediaPath,
            'video_url' => $validated['video_url'] ?? null,
            'is_pinned' => $request->boolean('is_pinned'),
        ]);

        return redirect()->route('admin.feed.index')->with('status', 'Post updated successfully!');
    }

    /**
     * Delete a feed post.
     */
    public function destroy(FeedPost $post)
    {
        if ($post->media_path && str_starts_with($post->media_path, '/uploads/feed/') && File::exists(public_path($post->media_path))) {
            File::delete(public_path($post->media_path));
        }

        $post->delete();

        return redirect()->route('admin.feed.index')->with('status', 'Post deleted.');
    }

    /**
     * Toggle pinned state.
     */
    public function togglePin(FeedPost $post)
    {
        $post->update(['is_pinned' => !$post->is_pinned]);

        return redirect()->back()->with('status', $post->is_pinned ? 'Post pinned to top of feed.' : 'Post unpinned.');
    }

    /**
     * Delete a comment on a feed post.
     */
    public function destroyComment(\App\Models\FeedPostComment $comment)
    {
        $comment->delete();

        return redirect()->back()->with('status', 'Comment deleted.');
    }
}
