<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    public function index()
    {
        $skills = Skill::orderBy('category')->orderBy('order')->get();
        $categories = [
            'Backend & Web Development',
            'Mobile Development',
            'Databases & Storage',
            'Programming Languages',
            'Integrations & External APIs',
            'Tools & Development Environment',
            'Engineering Practices',
            'AI & Computer Vision',
        ];

        return view('admin.skills.index', compact('skills', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'category' => 'required|string|max:100',
            'proficiency' => 'required|integer|min:1|max:100',
            'is_featured' => 'nullable|boolean',
            'order' => 'nullable|integer',
        ]);

        Skill::create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'proficiency' => $validated['proficiency'],
            'is_featured' => $request->boolean('is_featured', true),
            'order' => $validated['order'] ?? (Skill::max('order') + 1),
        ]);

        return redirect()->route('admin.skills.index')
            ->with('status', 'Skill "' . htmlspecialchars($validated['name']) . '" added successfully!');
    }

    public function update(Request $request, Skill $skill)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'category' => 'required|string|max:100',
            'proficiency' => 'required|integer|min:1|max:100',
            'is_featured' => 'nullable|boolean',
            'order' => 'nullable|integer',
        ]);

        $skill->update([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'proficiency' => $validated['proficiency'],
            'is_featured' => $request->boolean('is_featured'),
            'order' => $validated['order'] ?? $skill->order,
        ]);

        return redirect()->route('admin.skills.index')
            ->with('status', 'Skill updated successfully!');
    }

    public function destroy(Skill $skill)
    {
        $name = $skill->name;
        $skill->delete();

        return redirect()->route('admin.skills.index')
            ->with('status', 'Skill "' . $name . '" deleted successfully.');
    }
}
