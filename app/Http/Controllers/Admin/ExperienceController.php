<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\Request;

class ExperienceController extends Controller
{
    public function index()
    {
        $experiences = Experience::orderBy('order')->orderByDesc('id')->get();
        return view('admin.experience.index', compact('experiences'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'company' => 'required|string|max:150',
            'location' => 'nullable|string|max:150',
            'website' => 'nullable|url|max:200',
            'designation' => 'required|string|max:150',
            'period' => 'required|string|max:100',
            'type' => 'required|string|max:50',
            'timeline_label' => 'nullable|string|max:100',
            'is_current' => 'nullable|boolean',
            'summary' => 'nullable|string|max:1000',
            'highlights_text' => 'nullable|string|max:4000',
            'order' => 'nullable|integer',
        ]);

        $highlights = [];
        if (!empty($validated['highlights_text'])) {
            $highlights = array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", "", $validated['highlights_text'])))));
        }

        Experience::create([
            'company' => $validated['company'],
            'location' => $validated['location'],
            'website' => $validated['website'],
            'designation' => $validated['designation'],
            'period' => $validated['period'],
            'type' => $validated['type'],
            'timeline_label' => $validated['timeline_label'] ?: $validated['period'],
            'is_current' => $request->boolean('is_current'),
            'summary' => $validated['summary'],
            'highlights' => $highlights,
            'order' => $validated['order'] ?? (Experience::max('order') + 1),
        ]);

        return redirect()->route('admin.experience.index')
            ->with('status', 'Experience record created successfully!');
    }

    public function update(Request $request, Experience $experience)
    {
        $validated = $request->validate([
            'company' => 'required|string|max:150',
            'location' => 'nullable|string|max:150',
            'website' => 'nullable|url|max:200',
            'designation' => 'required|string|max:150',
            'period' => 'required|string|max:100',
            'type' => 'required|string|max:50',
            'timeline_label' => 'nullable|string|max:100',
            'is_current' => 'nullable|boolean',
            'summary' => 'nullable|string|max:1000',
            'highlights_text' => 'nullable|string|max:4000',
            'order' => 'nullable|integer',
        ]);

        $highlights = [];
        if (!empty($validated['highlights_text'])) {
            $highlights = array_values(array_filter(array_map('trim', explode("\n", str_replace("\r", "", $validated['highlights_text'])))));
        }

        $experience->update([
            'company' => $validated['company'],
            'location' => $validated['location'],
            'website' => $validated['website'],
            'designation' => $validated['designation'],
            'period' => $validated['period'],
            'type' => $validated['type'],
            'timeline_label' => $validated['timeline_label'] ?: $validated['period'],
            'is_current' => $request->boolean('is_current'),
            'summary' => $validated['summary'],
            'highlights' => $highlights,
            'order' => $validated['order'] ?? $experience->order,
        ]);

        return redirect()->route('admin.experience.index')
            ->with('status', 'Experience updated successfully!');
    }

    public function destroy(Experience $experience)
    {
        $company = $experience->company;
        $experience->delete();

        return redirect()->route('admin.experience.index')
            ->with('status', 'Experience at ' . $company . ' deleted successfully.');
    }
}
