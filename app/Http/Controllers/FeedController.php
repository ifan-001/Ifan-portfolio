<?php

namespace App\Http\Controllers;

use App\Models\FeedPost;
use App\Services\PortfolioDataService;
use Illuminate\Http\Request;

class FeedController extends Controller
{
    public function __construct(
        protected PortfolioDataService $portfolioService
    ) {}

    /**
     * Display public portfolio feed page.
     */
    public function index(Request $request)
    {
        $category = $request->query('category', 'all');
        $query = FeedPost::published()->with('comments');

        if ($category !== 'all' && in_array($category, ['professional', 'social', 'industrial', 'project', 'research'])) {
            $query->where('category', $category);
        }

        $posts = $query->paginate(12)->withQueryString();

        $counts = [
            'all' => FeedPost::published()->count(),
            'professional' => FeedPost::published()->where('category', 'professional')->count(),
            'industrial' => FeedPost::published()->where('category', 'industrial')->count(),
            'project' => FeedPost::published()->where('category', 'project')->count(),
            'social' => FeedPost::published()->where('category', 'social')->count(),
            'research' => FeedPost::published()->where('category', 'research')->count(),
        ];

        $portfolio = $this->portfolioService->getPortfolioData();

        return view('feed.index', compact('posts', 'counts', 'category', 'portfolio'));
    }

    /**
     * Toggle or increment like on a feed post.
     */
    public function like(Request $request, FeedPost $post)
    {
        $post->increment('likes_count');

        return response()->json([
            'status' => 'success',
            'likes_count' => $post->likes_count,
        ]);
    }

    /**
     * Post a comment on a feed post.
     */
    public function storeComment(Request $request, FeedPost $post)
    {
        $validated = $request->validate([
            'user_name' => 'required|string|min:2|max:100',
            'user_email' => 'nullable|email|max:150',
            'comment' => 'required|string|min:2|max:2000',
        ]);

        $isAdmin = auth()->check();

        $comment = $post->comments()->create([
            'user_name' => $isAdmin ? 'Hasnain Al Ifan' : $validated['user_name'],
            'user_email' => $validated['user_email'] ?? null,
            'comment' => $validated['comment'],
            'is_admin' => $isAdmin,
            'status' => 'approved',
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'comment' => [
                    'id' => $comment->id,
                    'user_name' => $comment->user_name,
                    'is_admin' => $comment->is_admin,
                    'comment' => $comment->comment,
                    'created_at' => $comment->created_at->diffForHumans(),
                ],
                'comments_count' => $post->comments()->count(),
            ]);
        }

        return redirect()->back()->with('status', 'Comment published successfully!');
    }
}
