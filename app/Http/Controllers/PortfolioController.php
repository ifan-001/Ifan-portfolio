<?php

namespace App\Http\Controllers;

use App\Models\AnalyticsEvent;
use App\Models\BlogPost;
use App\Models\ContactMessage;
use App\Services\PortfolioDataService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PortfolioController extends Controller
{
    protected PortfolioDataService $portfolioService;

    public function __construct(PortfolioDataService $portfolioService)
    {
        $this->portfolioService = $portfolioService;
    }

    protected function ownerEmail(): string
    {
        return $this->portfolioService->getPortfolioData()['brand']['email'] ?? config('portfolio.brand.email', 'hasnainalifan23@gmail.com');
    }

    /**
     * Display the personal portfolio page.
     */
    public function index(Request $request)
    {
        $portfolio = $this->portfolioService->getPortfolioData();

        // Privacy-conscious anonymous page view tracking
        try {
            AnalyticsEvent::track(
                eventType: 'page_view',
                pageUrl: $request->fullUrl(),
                referrer: $request->header('referer'),
                ip: $request->ip(),
                userAgent: $request->userAgent()
            );
        } catch (\Throwable $e) {
            Log::info('Analytics tracking suppressed: ' . $e->getMessage());
        }

        return view('portfolio.index', compact('portfolio'));
    }

    /**
     * Handle incoming contact inquiries.
     * Validates input and provides graceful JSON/redirect responses.
     */
    public function contact(Request $request)
    {
        // 1. Anti-Spam Honeypot Check: bots fill hidden inputs
        if ($request->filled('_hp_title')) {
            Log::warning('Portfolio contact form honeypot triggered', [
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Spam protection triggered. Please contact directly via email.',
            ], 422);
        }

        // 2. Strict Input Validation
        $validated = $request->validate([
            'name' => 'required|string|min:2|max:100',
            'email' => 'required|email|max:150',
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|min:10|max:3000',
        ]);

        $ownerEmail = $this->ownerEmail();

        // Construct functional mailto link
        $subject = urlencode($validated['subject'] ?: ('Portfolio Inquiry from ' . $validated['name']));
        $body = urlencode("From: {$validated['name']} ({$validated['email']})\n\n{$validated['message']}");
        $mailtoUrl = "mailto:{$ownerEmail}?subject={$subject}&body={$body}";

        // 3. Persist to database so no message is ever lost
        $savedMessage = null;
        try {
            $savedMessage = ContactMessage::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'] ?? null,
                'message' => $validated['message'],
                'ip' => $request->ip(),
                'status' => 'unread',
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to persist contact message: ' . $e->getMessage());
        }

        // 4. Attempt mail dispatch if SMTP or mailer is configured
        $mailSent = false;
        try {
            Mail::raw(
                "You received a new inquiry on your portfolio website:\n\n" .
                "Name: {$validated['name']}\n" .
                "Email: {$validated['email']}\n" .
                "Subject: " . ($validated['subject'] ?? 'General Inquiry') . "\n\n" .
                "Message:\n{$validated['message']}\n\n" .
                "---\nManage this inquiry in your Admin Dashboard: " . url('/admin/inquiries'),
                function ($m) use ($validated, $ownerEmail) {
                    $m->to($ownerEmail)
                      ->replyTo($validated['email'], $validated['name'])
                      ->subject('Portfolio Inquiry: ' . ($validated['subject'] ?: $validated['name']));
                }
            );
            $mailSent = true;
        } catch (\Throwable $e) {
            Log::info('Mail dispatch fallback: ' . $e->getMessage());
        }

        Log::info('Portfolio Contact Inquiry Received & Saved', [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'] ?? 'N/A',
            'ip' => $request->ip(),
            'mail_sent' => $mailSent,
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Thank you for reaching out, ' . htmlspecialchars($validated['name']) . '! Your message has been received.',
                'mailto_url' => $mailtoUrl,
                'mail_dispatched' => $mailSent,
            ]);
        }

        return redirect()->back()->with('contact_success', 'Thank you for reaching out! You can also connect directly via ' . $ownerEmail);
    }

    /**
     * Handle resume download with fallback.
     */
    public function downloadResume(Request $request)
    {
        $ownerEmail = $this->ownerEmail();
        $portfolio = $this->portfolioService->getPortfolioData();
        $resumePath = public_path($portfolio['resume']['path']);

        // Track resume download event
        try {
            AnalyticsEvent::track(
                eventType: 'resume_download',
                pageUrl: $request->fullUrl(),
                target: 'CV.pdf',
                ip: $request->ip(),
                userAgent: $request->userAgent()
            );
        } catch (\Throwable $e) {
            // ignore
        }

        if (File::exists($resumePath)) {
            return response()->file($resumePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Hasnain-Al-Ifan-CV.pdf"',
            ]);
        }

        return redirect()->route('home')->with('resume_notice', 'The official CV PDF is currently being updated. Please contact ' . $ownerEmail . '.');
    }

    /**
     * Printable HTML version of the CV (source for the PDF).
     */
    public function resumeView()
    {
        $portfolio = $this->portfolioService->getPortfolioData();

        return response()->view('resume.cv', compact('portfolio'))->header('X-Robots-Tag', 'noindex');
    }

    /**
     * Display public blog post index.
     */
    public function blogIndex(Request $request)
    {
        $portfolio = $this->portfolioService->getPortfolioData();
        $posts = BlogPost::published()->paginate(9);

        try {
            AnalyticsEvent::track(
                eventType: 'page_view',
                pageUrl: $request->fullUrl(),
                target: 'blog_index',
                ip: $request->ip(),
                userAgent: $request->userAgent()
            );
        } catch (\Throwable $e) {}

        return view('portfolio.blog.index', compact('portfolio', 'posts'));
    }

    /**
     * Display single public blog post.
     */
    public function blogShow($slug, Request $request)
    {
        $post = BlogPost::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Increment view count
        $post->increment('views_count');

        $portfolio = $this->portfolioService->getPortfolioData();
        $relatedPosts = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->take(3)
            ->get();

        try {
            AnalyticsEvent::track(
                eventType: 'page_view',
                pageUrl: $request->fullUrl(),
                target: 'blog:' . $post->slug,
                ip: $request->ip(),
                userAgent: $request->userAgent()
            );
        } catch (\Throwable $e) {}

        return view('portfolio.blog.show', compact('portfolio', 'post', 'relatedPosts'));
    }

    /**
     * Track outbound project clicks (demo / repo).
     */
    public function trackClick(Request $request)
    {
        $validated = $request->validate([
            'project_slug' => 'required|string|max:100',
            'click_type' => 'required|string|in:demo,github,external',
        ]);

        try {
            AnalyticsEvent::track(
                eventType: 'project_click',
                pageUrl: $request->header('referer'),
                target: $validated['project_slug'] . ':' . $validated['click_type'],
                ip: $request->ip(),
                userAgent: $request->userAgent()
            );
        } catch (\Throwable $e) {}

        return response()->json(['status' => 'tracked']);
    }
}
