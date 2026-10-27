<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PushSubscription;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'show_hero' => (bool) SiteSetting::get('show_hero', true),
            'show_about' => (bool) SiteSetting::get('show_about', true),
            'show_experience' => (bool) SiteSetting::get('show_experience', true),
            'show_projects' => (bool) SiteSetting::get('show_projects', true),
            'show_skills' => (bool) SiteSetting::get('show_skills', true),
            'show_education' => (bool) SiteSetting::get('show_education', true),
            'show_research' => (bool) SiteSetting::get('show_research', true),
            'show_blog' => (bool) SiteSetting::get('show_blog', true),
            'show_contact' => (bool) SiteSetting::get('show_contact', true),
            'primary_accent_color' => SiteSetting::get('primary_accent_color', '#3B82F6'),
            'live_chat_enabled' => (bool) SiteSetting::get('live_chat_enabled', true),
        ];

        $pushSubscriptionsCount = PushSubscription::count();

        return view('admin.settings.index', compact('settings', 'pushSubscriptionsCount'));
    }

    public function update(Request $request)
    {
        $keys = [
            'show_hero',
            'show_about',
            'show_experience',
            'show_projects',
            'show_skills',
            'show_education',
            'show_research',
            'show_blog',
            'show_contact',
            'live_chat_enabled',
        ];

        foreach ($keys as $k) {
            SiteSetting::set($k, $request->boolean($k));
        }

        if ($request->filled('primary_accent_color')) {
            SiteSetting::set('primary_accent_color', $request->input('primary_accent_color'));
        }

        return redirect()->route('admin.settings.index')
            ->with('status', 'Platform visibility and appearance settings updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required|current_password',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();
        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('admin.settings.index')
            ->with('status', 'Administrator password updated securely!');
    }
}
