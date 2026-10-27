<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    public function edit()
    {
        $profile = Profile::current();
        return view('admin.profile.edit', compact('profile'));
    }

    public function update(Request $request)
    {
        $profile = Profile::current();

        $validated = $request->validate([
            'short_name' => 'required|string|max:50',
            'full_name' => 'required|string|max:100',
            'title' => 'required|string|max:120',
            'sub_title' => 'nullable|string|max:200',
            'location' => 'nullable|string|max:150',
            'email' => 'required|email|max:150',
            'phone' => 'nullable|string|max:50',
            'hero_headline' => 'nullable|string|max:250',
            'hero_subheadline' => 'nullable|string|max:250',
            'short_bio' => 'nullable|string|max:2000',
            'detailed_bio_text' => 'nullable|string|max:6000',
            'positioning' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        // Process detailed bio into paragraphs array
        $paragraphs = [];
        if (!empty($validated['detailed_bio_text'])) {
            $lines = explode("\n", str_replace("\r", "", $validated['detailed_bio_text']));
            $currentP = [];
            foreach ($lines as $line) {
                $trimmed = trim($line);
                if ($trimmed === '') {
                    if (!empty($currentP)) {
                        $paragraphs[] = implode(' ', $currentP);
                        $currentP = [];
                    }
                } else {
                    $currentP[] = $trimmed;
                }
            }
            if (!empty($currentP)) {
                $paragraphs[] = implode(' ', $currentP);
            }
        }

        // Process profile image upload
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $fileName = 'profile_' . time() . '.' . $file->getClientOriginalExtension();
            $destination = public_path('images');
            if (!File::isDirectory($destination)) {
                File::makeDirectory($destination, 0755, true);
            }
            $file->move($destination, $fileName);
            $profile->profile_image = '/images/' . $fileName;

            // Also copy as standard profile.jpg for fallback
            @copy($destination . '/' . $fileName, $destination . '/profile.jpg');
        }

        $profile->short_name = $validated['short_name'];
        $profile->full_name = $validated['full_name'];
        $profile->title = $validated['title'];
        $profile->sub_title = $validated['sub_title'] ?? null;
        $profile->location = $validated['location'] ?? null;
        $profile->email = $validated['email'];
        $profile->phone = $validated['phone'] ?? null;
        $profile->hero_headline = $validated['hero_headline'] ?? null;
        $profile->hero_subheadline = $validated['hero_subheadline'] ?? null;
        $profile->short_bio = $validated['short_bio'] ?? null;
        $profile->positioning = $validated['positioning'] ?? null;
        if (!empty($paragraphs)) {
            $profile->detailed_bio = $paragraphs;
        }
        $profile->save();

        return redirect()->route('admin.profile.edit')
            ->with('status', 'Profile and personal brand updated successfully!');
    }
}
