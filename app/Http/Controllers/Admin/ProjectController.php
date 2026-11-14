<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    /** Folder for images uploaded from the admin panel (safe to delete from). */
    private const UPLOAD_DIR = 'images/projects/uploads';

    public const ICONS = ['code' => 'Code', 'qr' => 'QR code', 'news' => 'News', 'users' => 'People', 'shield' => 'Shield', 'brain' => 'AI / Brain', 'cart' => 'Shop', 'phone' => 'Mobile', 'building' => 'Building'];

    public function index()
    {
        $projects = Project::orderBy('order')->orderByDesc('id')->get();

        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.projects.form', ['project' => new Project(['category' => 'web', 'showcase' => ['accent' => '#3B82F6', 'icon' => 'code']]), 'icons' => self::ICONS]);
    }

    public function edit(Project $project)
    {
        return view('admin.projects.form', ['project' => $project, 'icons' => self::ICONS]);
    }

    public function store(Request $request)
    {
        $project = new Project();
        $this->save($request, $project, true);

        return $this->done($request, 'Project "' . $project->title . '" created successfully!');
    }

    public function update(Request $request, Project $project)
    {
        $this->save($request, $project, false);

        return $this->done($request, 'Project "' . $project->title . '" updated successfully!');
    }

    public function destroy(Request $request, Project $project)
    {
        $title = $project->title;
        foreach (array_merge($project->gallery ?? [], $project->mobile_gallery ?? [], [['src' => $project->thumbnail]]) as $img) {
            $this->deleteUploaded($img['src'] ?? null);
        }
        $project->delete();

        return $this->done($request, 'Project "' . $title . '" deleted successfully.');
    }

    // ------------------------------------------------------------------

    private function save(Request $request, Project $project, bool $creating): void
    {
        if ($request->filled('slug')) {
            $request->merge(['slug' => Str::slug($request->input('slug'))]);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'slug' => ($creating ? 'nullable' : 'required') . '|string|max:150|unique:projects,slug' . ($creating ? '' : ',' . $project->id),
            'category' => 'required|string|in:professional,web,mobile,desktop,ai',
            'tagline' => 'nullable|string|max:200',
            'summary' => 'required|string|max:2000',
            'description' => 'nullable|string|max:8000',
            'technologies_text' => 'nullable|string|max:800',
            'live_url' => 'nullable|url|max:255',
            'github_url' => 'nullable|url|max:255',
            'is_featured' => 'nullable|boolean',
            'order' => 'nullable|integer|min:0|max:999',
            'thumbnail_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:8192',

            // Case study (sent by the full editor only)
            'showcase_form' => 'nullable|boolean',
            'sc.tagline' => 'nullable|string|max:200',
            'sc.status' => 'nullable|string|max:80',
            'sc.year' => 'nullable|string|max:20',
            'sc.accent' => ['nullable', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'sc.icon' => 'nullable|string|in:' . implode(',', array_keys(self::ICONS)),
            'sc.role' => 'nullable|string|max:300',
            'sc.problem' => 'nullable|string|max:1500',
            'stats' => 'nullable|array|max:8',
            'stats.*.value' => 'nullable|string|max:30',
            'stats.*.label' => 'nullable|string|max:60',
            'features' => 'nullable|array|max:20',
            'features.*.title' => 'nullable|string|max:120',
            'features.*.desc' => 'nullable|string|max:600',
            'gallery' => 'nullable|array|max:30',
            'gallery.*.src' => 'nullable|string|max:300',
            'gallery.*.caption' => 'nullable|string|max:150',
            'gallery.*.remove' => 'nullable|boolean',
            'mobile_gallery' => 'nullable|array|max:30',
            'mobile_gallery.*.src' => 'nullable|string|max:300',
            'mobile_gallery.*.caption' => 'nullable|string|max:150',
            'mobile_gallery.*.remove' => 'nullable|boolean',
            'gallery_new' => 'nullable|array|max:12',
            'gallery_new.*' => 'image|mimes:jpeg,png,jpg,webp|max:8192',
            'mobile_new' => 'nullable|array|max:12',
            'mobile_new.*' => 'image|mimes:jpeg,png,jpg,webp|max:8192',
        ]);

        $project->title = $validated['title'];
        $project->slug = $validated['slug'] ?? Str::slug($validated['title']);
        $project->category = $validated['category'];
        $project->tagline = $validated['tagline'] ?? null;
        $project->summary = $validated['summary'];
        $project->description = $validated['description'] ?? $project->description;
        $project->technologies = array_values(array_filter(array_map('trim', explode(',', $validated['technologies_text'] ?? ''))));
        $project->live_url = $validated['live_url'] ?? null;
        $project->github_url = $validated['github_url'] ?? null;
        $project->is_featured = $request->boolean('is_featured');
        if (isset($validated['order'])) {
            $project->order = $validated['order'];
        } elseif ($creating) {
            $project->order = (int) Project::max('order') + 1;
        }

        if ($request->hasFile('thumbnail_image')) {
            $this->deleteUploaded($project->thumbnail);
            $project->thumbnail = $this->storeImage($request->file('thumbnail_image'), $project->slug);
        }

        // The quick in-place editor does not send case-study fields; never wipe them in that case.
        if ($request->boolean('showcase_form')) {
            $sc = array_map(fn ($v) => is_string($v) ? trim($v) : $v, $validated['sc'] ?? []);
            $sc['stats'] = array_values(array_filter($validated['stats'] ?? [], fn ($s) => trim($s['value'] ?? '') !== '' && trim($s['label'] ?? '') !== ''));
            $sc['features'] = array_values(array_filter(array_map(fn ($f) => ['title' => trim($f['title'] ?? ''), 'desc' => trim($f['desc'] ?? '')], $validated['features'] ?? []), fn ($f) => $f['title'] !== ''));
            $project->showcase = $sc;
            $project->gallery = $this->mergeGallery($validated['gallery'] ?? [], $request->file('gallery_new', []), $project->slug);
            $project->mobile_gallery = $this->mergeGallery($validated['mobile_gallery'] ?? [], $request->file('mobile_new', []), $project->slug);
        }

        if (empty($project->thumbnail) && ! empty($project->gallery[0]['src'])) {
            $project->thumbnail = $project->gallery[0]['src'];
        }

        $project->save();
    }

    /** Keep listed images (in submitted order), drop removed ones, append new uploads. */
    private function mergeGallery(array $items, array $uploads, string $slug): array
    {
        $out = [];
        foreach ($items as $item) {
            $src = $item['src'] ?? '';
            if ($src === '' || ! str_starts_with($src, '/images/')) {
                continue;
            }
            if (! empty($item['remove'])) {
                $this->deleteUploaded($src);
                continue;
            }
            $out[] = ['src' => $src, 'caption' => trim($item['caption'] ?? '')];
        }
        foreach ($uploads as $file) {
            if ($file instanceof UploadedFile && $file->isValid()) {
                $out[] = ['src' => $this->storeImage($file, $slug), 'caption' => ''];
            }
        }

        return $out;
    }

    private function storeImage(UploadedFile $file, string $slug): string
    {
        $dir = public_path(self::UPLOAD_DIR);
        if (! File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }
        $name = Str::slug($slug ?: 'project') . '-' . Str::random(8) . '.' . ($file->guessExtension() ?: 'jpg');
        if (! app()->environment('testing')) {
            $file->move($dir, $name);
        }

        return '/' . self::UPLOAD_DIR . '/' . $name;
    }

    private function deleteUploaded(?string $src): void
    {
        if ($src && str_starts_with($src, '/' . self::UPLOAD_DIR . '/') && ! str_contains($src, '..')) {
            File::delete(public_path(ltrim($src, '/')));
        }
    }

    private function done(Request $request, string $message)
    {
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['status' => 'success', 'message' => $message]);
        }

        return redirect()->route('admin.projects.index')->with('status', $message);
    }
}
