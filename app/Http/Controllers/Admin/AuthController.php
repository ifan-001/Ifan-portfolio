<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Show the discreet admin login form.
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    /**
     * Process secure administrator authentication with rate limiting.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Rate Limiter key: 5 attempts per 1 minute
        $throttleKey = Str::transliterate(Str::lower($request->input('email')) . '|' . $request->ip());

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            Log::warning('Admin login throttled due to excessive attempts', [
                'email' => $request->input('email'),
                'ip' => $request->ip(),
                'retry_after' => $seconds,
            ]);

            throw ValidationException::withMessages([
                'email' => trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ]);
        }

        // The admin signs in on their own devices (phone app included): stay signed in until "Sign out".
        $remember = true;

        if (Auth::attempt($credentials, $remember)) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();

            Log::info('Admin user authenticated successfully', [
                'user_id' => Auth::id(),
                'ip' => $request->ip(),
            ]);

            return redirect()->intended(route('admin.dashboard'))
                ->with('status', 'Welcome back, ' . Auth::user()->name);
        }

        RateLimiter::hit($throttleKey, 60);

        Log::warning('Failed admin login attempt', [
            'email' => $request->input('email'),
            'ip' => $request->ip(),
        ]);

        throw ValidationException::withMessages([
            'email' => 'Invalid administrator credentials. Access attempts are monitored.',
        ]);
    }

    /**
     * Log out administrator securely and return to public portfolio.
     */
    public function logout(Request $request)
    {
        $userId = Auth::id();
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('Admin user logged out', ['user_id' => $userId]);

        return redirect()->route('home');
    }
}
