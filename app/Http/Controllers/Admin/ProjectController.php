<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::orderBy('order')->orderByDesc('id')->get();
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        return view('admin.projects.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'slug' => 'nullable|string|max:150|unique:projects,slug',
            'category' => 'required|string|max:50',
            'tagline' => 'nullable|string|max:200',
            'summary' => 'required|string|max:1000',
            'description' => 'nullable|string|max:5000',
            'technologies_text' => 'nullable|string|max:500',
            'live_url' => 'nullable|url|max:255',
            'github_url' => 'nullable|url|max:255',
            'is_featured' => 'nullable|boolean',
            'order' => 'nullable|integer',
            'thumbnail_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $techArray = [];
        if (!empty($validated['technologies_text'])) {
            $techArray = array_values(array_filter(array_map('trim', explode(',', $validated['technologies_text']))));
        }

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail_image')) {
            $file = $request->file('thumbnail_image');
            $fileName = 'project_' . time() . '_' . Str::slug($validated['title']) . '.' . ($file->guessExtension() ?: 'jpg');
            $file->move(public_path('images/projects'), $fileName);
            $thumbnailPath = '/images/projects/' . $fileName;
        }

        Project::create([
            'title' => $validated['title'],
            'slug' => !empty($validated['slug']) ? Str::slug($validated['slug']) : Str::slug($validated['title']),
            'category' => $validated['category'],
            'tagline' => $validated['tagline'] ?? null,
            'summary' => $validated['summary'],
            'description' => $validated['description'] ?? null,
            'technologies' => $techArray,
            'thumbnail' => $thumbnailPath,
            'live_url' => $validated['live_url'] ?? null,
            'github_url' => $validated['github_url'] ?? null,
            'is_featured' => $request->boolean('is_featured'),
            'order' => $validated['order'] ?? (Project::max('order') + 1),
        ]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Project "' . $validated['title'] . '" created successfully!',
            ]);
        }

        return redirect()->route('admin.projects.index')
            ->with('status', 'Project "' . htmlspecialchars($validated['title']) . '" created successfully!');
    }

    public function edit(Project $project)
    {
        return view('admin.projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'slug' => 'required|string|max:150|unique:projects,slug,' . $project->id,
            'category' => 'required|string|max:50',
            'tagline' => 'nullable|string|max:200',
            'summary' => 'required|string|max:1000',
            'description' => 'nullable|string|max:5000',
            'technologies_text' => 'nullable|string|max:500',
            'live_url' => 'nullable|url|max:255',
            'github_url' => 'nullable|url|max:255',
            'is_featured' => 'nullable|boolean',
            'order' => 'nullable|integer',
            'thumbnail_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        $techArray = [];
        if (!empty($validated['technologies_text'])) {
            $techArray = array_values(array_filter(array_map('trim', explode(',', $validated['technologies_text']))));
        }

        if ($request->hasFile('thumbnail_image')) {
            $file = $request->file('thumbnail_image');
            $fileName = 'project_' . time() . '_' . Str::slug($validated['title']) . '.' . ($file->guessExtension() ?: 'jpg');
            $dest = public_path('images/projects');
            if (!File::isDirectory($dest)) {
                File::makeDirectory($dest, 0755, true);
            }
            $file->move($dest, $fileName);
            $project->thumbnail = '/images/projects/' . $fileName;
        }

        $project->title = $validated['title'];
        $project->slug = Str::slug($validated['slug']);
        $project->category = $validated['category'];
        $project->tagline = $validated['tagline'] ?? null;
        $project->summary = $validated['summary'];
        $project->description = $validated['description'] ?? null;
        $project->technologies = $techArray;
        $project->live_url = $validated['live_url'] ?? null;
        $project->github_url = $validated['github_url'] ?? null;
        $project->is_featured = $request->boolean('is_featured');
        if (isset($validated['order'])) {
            $project->order = $validated['order'];
        }
        $project->save();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Project "' . $project->title . '" updated successfully!',
            ]);
        }

        return redirect()->route('admin.projects.index')
            ->with('status', 'Project "' . htmlspecialchars($project->title) . '" updated successfully!');
    }

    public function destroy(Request $request, Project $project)
    {
        $title = $project->title;
        $project->delete();

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Project "' . $title . '" deleted successfully.',
            ]);
        }

        return redirect()->route('admin.projects.index')
            ->with('status', 'Project "' . $title . '" deleted successfully.');
    }
}
