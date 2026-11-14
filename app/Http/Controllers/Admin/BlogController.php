<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::orderByDesc('created_at')->paginate(15);
        return view('admin.blog.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.blog.create');
    }

    public function store(Request $request)
    {
        // Normalise the slug first so uniqueness is checked against the stored value.
        if ($request->filled('slug')) {
            $request->merge(['slug' => Str::slug($request->input('slug'))]);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'slug' => 'nullable|string|max:200|unique:blog_posts,slug',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'tags_text' => 'nullable|string|max:300',
            'status' => 'required|in:draft,published',
            'read_time' => 'nullable|integer|min:1|max:60',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $tags = [];
        if (!empty($validated['tags_text'])) {
            $tags = array_values(array_filter(array_map('trim', explode(',', $validated['tags_text']))));
        }

        $coverPath = null;
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $fileName = 'blog_' . time() . '_' . Str::slug($validated['title']) . '.' . ($file->guessExtension() ?: 'jpg');
            $dest = public_path('images/blog');
            if (!File::isDirectory($dest)) {
                File::makeDirectory($dest, 0755, true);
            }
            $file->move($dest, $fileName);
            $coverPath = '/images/blog/' . $fileName;
        }

        // Calculate approximate reading time if not supplied (200 words per minute)
        $wordCount = str_word_count(strip_tags($validated['content']));
        $readTime = $validated['read_time'] ?: max(1, ceil($wordCount / 200));

        $post = BlogPost::create([
            'title' => $validated['title'],
            'slug' => !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']),
            'excerpt' => $validated['excerpt'],
            'content' => $validated['content'],
            'tags' => $tags,
            'cover_image' => $coverPath,
            'status' => $validated['status'],
            'read_time' => $readTime,
            'published_at' => $validated['status'] === 'published' ? now() : null,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Blog article "' . $post->title . '" saved successfully!',
            ]);
        }

        return redirect()->route('admin.blog.index')
            ->with('status', 'Blog article "' . htmlspecialchars($post->title) . '" saved successfully!');
    }

    public function edit(BlogPost $blog)
    {
        return view('admin.blog.edit', ['post' => $blog]);
    }

    public function update(Request $request, BlogPost $blog)
    {
        // Normalise the slug first so uniqueness is checked against the stored value.
        if ($request->filled('slug')) {
            $request->merge(['slug' => Str::slug($request->input('slug'))]);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'slug' => 'required|string|max:200|unique:blog_posts,slug,' . $blog->id,
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'tags_text' => 'nullable|string|max:300',
            'status' => 'required|in:draft,published',
            'read_time' => 'nullable|integer|min:1|max:60',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $tags = [];
        if (!empty($validated['tags_text'])) {
            $tags = array_values(array_filter(array_map('trim', explode(',', $validated['tags_text']))));
        }

        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $fileName = 'blog_' . time() . '_' . Str::slug($validated['title']) . '.' . ($file->guessExtension() ?: 'jpg');
            $dest = public_path('images/blog');
            if (!File::isDirectory($dest)) {
                File::makeDirectory($dest, 0755, true);
            }
            $file->move($dest, $fileName);
            $blog->cover_image = '/images/blog/' . $fileName;
        }

        $wordCount = str_word_count(strip_tags($validated['content']));
        $readTime = $validated['read_time'] ?: max(1, ceil($wordCount / 200));

        $wasDraft = $blog->status === 'draft';
        $nowPublished = $validated['status'] === 'published';

        $blog->title = $validated['title'];
        $blog->slug = Str::slug($validated['slug']);
        $blog->excerpt = $validated['excerpt'];
        $blog->content = $validated['content'];
        $blog->tags = $tags;
        $blog->status = $validated['status'];
        $blog->read_time = $readTime;
        if ($wasDraft && $nowPublished && empty($blog->published_at)) {
            $blog->published_at = now();
        }
        $blog->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Article updated successfully!',
            ]);
        }

        return redirect()->route('admin.blog.index')
            ->with('status', 'Article updated successfully!');
    }

    public function destroy(Request $request, BlogPost $blog)
    {
        $title = $blog->title;
        $blog->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Article "' . $title . '" deleted.',
            ]);
        }

        return redirect()->route('admin.blog.index')
            ->with('status', 'Article "' . $title . '" deleted.');
    }
}
