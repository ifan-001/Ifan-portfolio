<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class PortfolioController extends Controller
{
    /**
     * Display the personal portfolio page.
     */
    public function index()
    {
        $portfolio = config('portfolio');
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

        // Construct functional mailto link
        $subject = urlencode($validated['subject'] ?: ('Portfolio Inquiry from ' . $validated['name']));
        $body = urlencode("From: {$validated['name']} ({$validated['email']})\n\n{$validated['message']}");
        $mailtoUrl = "mailto:hasnainalifan23@gmail.com?subject={$subject}&body={$body}";

        // 3. Persist to database so no message is ever lost
        try {
            \App\Models\ContactMessage::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'subject' => $validated['subject'] ?? null,
                'message' => $validated['message'],
                'ip' => $request->ip(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to persist contact message: ' . $e->getMessage());
        }

        // 4. Attempt mail dispatch if SMTP or mailer is configured
        try {
            \Illuminate\Support\Facades\Mail::raw(
                "You received a new message from your portfolio website:\n\nName: {$validated['name']}\nEmail: {$validated['email']}\nSubject: {$validated['subject']}\n\nMessage:\n{$validated['message']}",
                function ($m) use ($validated) {
                    $m->to('hasnainalifan23@gmail.com')
                      ->replyTo($validated['email'], $validated['name'])
                      ->subject('Portfolio Inquiry: ' . ($validated['subject'] ?: $validated['name']));
                }
            );
        } catch (\Throwable $e) {
            Log::info('Mail dispatch fallback: ' . $e->getMessage());
        }

        Log::info('Portfolio Contact Inquiry Received & Saved', [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'] ?? 'N/A',
            'ip' => $request->ip(),
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Thank you for reaching out, ' . htmlspecialchars($validated['name']) . '! Your message has been prepared.',
                'mailto_url' => $mailtoUrl,
            ]);
        }

        return redirect()->back()->with('contact_success', 'Thank you for reaching out! You can also connect directly via hasnainalifan23@gmail.com');
    }

    /**
     * Handle resume download with fallback.
     */
    public function downloadResume()
    {
        $resumeConfig = config('portfolio.resume');
        $resumePath = public_path($resumeConfig['path']);

        if (File::exists($resumePath)) {
            return response()->file($resumePath, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="Hasnain-Al-Ifan-CV.pdf"',
            ]);
        }

        return redirect()->route('home')->with('resume_notice', 'The official CV PDF is currently being updated. Please contact hasnainalifan23@gmail.com.');
    }
}
