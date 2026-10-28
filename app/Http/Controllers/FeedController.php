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
        $query = FeedPost::published();

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
}
