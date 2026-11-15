<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        // Pages rendered for a logged-in admin contain edit controls and CSRF tokens:
        // never let a browser, proxy or service worker store or reuse them for anyone else.
        if (($request->user() || $request->is('portal', 'portal/*')) && ! $response->headers->has('Content-Disposition')) {
            $response->headers->set('Cache-Control', 'no-store, private, max-age=0');
            $response->headers->set('Vary', 'Cookie', false);
        }

        // Keep the admin area and API out of search indexes without advertising paths in robots.txt.
        if ($request->is('admin', 'admin/*', 'portal', 'portal/*', 'api/*')) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
        }

        return $response;
    }
}
