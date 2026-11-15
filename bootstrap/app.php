<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Behind a reverse proxy/load balancer, set TRUSTED_PROXIES (comma-separated IPs or "*") in .env.
        if ($proxies = env('TRUSTED_PROXIES')) {
            $middleware->trustProxies(at: $proxies === '*' ? '*' : array_map('trim', explode(',', $proxies)));
        }

        $middleware->appendToGroup('web', \App\Http\Middleware\SecurityHeaders::class);

        $middleware->redirectGuestsTo(fn () => route('admin.login'));

        $middleware->validateCsrfTokens(except: [
            'api/chat/send',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // A page left open for a long time (or restored by the phone) carries an old form token.
        // Instead of the bare "419 Page Expired" screen, send people somewhere useful.
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, \Illuminate\Http\Request $request) {
            if ($request->expectsJson()) {
                return null;
            }

            if ($request->is('portal/logout')) {
                \Illuminate\Support\Facades\Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return redirect('/');
            }

            if ($request->is('portal/login')) {
                return redirect()->route('admin.login')
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => 'The sign-in page had expired, so it was refreshed. Please sign in again.']);
            }

            return redirect()->back()
                ->withInput($request->except(['password', 'password_confirmation', '_token']))
                ->withErrors(['session' => 'This page was open for a long time, so it was refreshed. Please try again.']);
        });
    })->create();
