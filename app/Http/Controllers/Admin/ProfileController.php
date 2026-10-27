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

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Profile and personal brand updated successfully!',
                'profile' => $profile,
            ]);
        }

        return redirect()->route('admin.profile.edit')
            ->with('status', 'Profile and personal brand updated successfully!');
    }

    /**
     * Update the About Me detailed bio paragraphs directly.
     */
    public function updateAboutBio(Request $request)
    {
        $validated = $request->validate([
            'paragraphs' => 'required|array|min:1',
            'paragraphs.*' => 'required|string|max:4000',
        ]);

        $profile = Profile::current();
        $cleaned = array_values(array_filter(array_map('trim', $validated['paragraphs'])));

        $profile->detailed_bio = $cleaned;
        $profile->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'About Me bio paragraphs saved successfully!',
                'detailed_bio' => $profile->detailed_bio,
            ]);
        }

        return back()->with('status', 'About Me bio updated successfully!');
    }

    /**
     * Restore the original authentic 4-paragraph bio from configuration.
     */
    public function restoreAboutBio(Request $request)
    {
        $authentic = config('portfolio.brand.detailed_bio');
        $profile = Profile::current();
        $profile->detailed_bio = $authentic;
        $profile->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Authentic bio restored successfully!',
                'detailed_bio' => $profile->detailed_bio,
            ]);
        }

        return back()->with('status', 'Authentic bio restored successfully!');
    }

    /**
     * Upload and automatically fit profile picture.
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $profile = Profile::current();
        $file = $request->file('profile_image');
        $fileName = 'profile_' . time() . '.' . $file->getClientOriginalExtension();
        $destination = public_path('images');

        if (!File::isDirectory($destination)) {
            File::makeDirectory($destination, 0755, true);
        }

        $file->move($destination, $fileName);
        $profile->profile_image = '/images/' . $fileName;

        // Ensure default profile.jpg is also updated for fallback
        @copy($destination . '/' . $fileName, $destination . '/profile.jpg');
        $profile->save();

        $imageUrl = asset('images/' . $fileName) . '?v=' . time();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Profile picture updated successfully with automatic aspect ratio fitting!',
                'image_url' => $imageUrl,
                'photo_url' => $imageUrl,
            ]);
        }

        return back()->with('status', 'Profile picture updated successfully!');
    }

    /**
     * Update social profile links.
     */
    public function updateSocials(Request $request)
    {
        $validated = $request->validate([
            'socials' => 'required|array',
            'socials.*.url' => 'required|string|max:255',
            'socials.*.handle' => 'nullable|string|max:100',
        ]);

        foreach ($validated['socials'] as $platform => $data) {
            \App\Models\SocialLink::where('platform', $platform)->update([
                'url' => $data['url'],
                'handle' => $data['handle'] ?? null,
            ]);
        }

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Social links updated successfully!',
            ]);
        }

        return back()->with('status', 'Social links updated successfully!');
    }
}
