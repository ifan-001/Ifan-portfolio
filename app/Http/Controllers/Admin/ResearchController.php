<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\ResearchItem;
use Illuminate\Http\Request;

class ResearchController extends Controller
{
    public function index()
    {
        $researchItems = ResearchItem::orderBy('order')->get();
        $achievements = Achievement::orderBy('order')->get();

        return view('admin.research.index', compact('researchItems', 'achievements'));
    }

    public function storeItem(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'type' => 'required|string|max:100',
            'domain' => 'required|string|max:150',
            'status' => 'required|string|max:100',
            'summary' => 'required|string|max:2000',
            'technologies_text' => 'nullable|string|max:500',
            'link' => 'nullable|url|max:255',
            'order' => 'nullable|integer',
        ]);

        $tech = [];
        if (!empty($validated['technologies_text'])) {
            $tech = array_values(array_filter(array_map('trim', explode(',', $validated['technologies_text']))));
        }

        ResearchItem::create([
            'title' => $validated['title'],
            'type' => $validated['type'],
            'domain' => $validated['domain'],
            'status' => $validated['status'],
            'summary' => $validated['summary'],
            'technologies' => $tech,
            'link' => $validated['link'],
            'order' => $validated['order'] ?? (ResearchItem::max('order') + 1),
        ]);

        return redirect()->route('admin.research.index')
            ->with('status', 'Research item created successfully!');
    }

    public function updateItem(Request $request, ResearchItem $item)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'type' => 'required|string|max:100',
            'domain' => 'required|string|max:150',
            'status' => 'required|string|max:100',
            'summary' => 'required|string|max:2000',
            'technologies_text' => 'nullable|string|max:500',
            'link' => 'nullable|url|max:255',
            'order' => 'nullable|integer',
        ]);

        $tech = [];
        if (!empty($validated['technologies_text'])) {
            $tech = array_values(array_filter(array_map('trim', explode(',', $validated['technologies_text']))));
        }

        $item->update([
            'title' => $validated['title'],
            'type' => $validated['type'],
            'domain' => $validated['domain'],
            'status' => $validated['status'],
            'summary' => $validated['summary'],
            'technologies' => $tech,
            'link' => $validated['link'],
            'order' => $validated['order'] ?? $item->order,
        ]);

        return redirect()->route('admin.research.index')
            ->with('status', 'Research item updated successfully!');
    }

    public function destroyItem(ResearchItem $item)
    {
        $title = $item->title;
        $item->delete();

        return redirect()->route('admin.research.index')
            ->with('status', 'Research item "' . $title . '" deleted.');
    }

    public function storeAchievement(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:200',
            'category' => 'required|string|max:100',
            'organization' => 'nullable|string|max:150',
            'year' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:1000',
            'order' => 'nullable|integer',
        ]);

        Achievement::create([
            'title' => $validated['title'],
            'category' => $validated['category'],
            'organization' => $validated['organization'],
            'year' => $validated['year'] ?: date('Y'),
            'description' => $validated['description'],
            'order' => $validated['order'] ?? (Achievement::max('order') + 1),
        ]);

        return redirect()->route('admin.research.index')
            ->with('status', 'Highlight/Achievement added successfully!');
    }

    public function destroyAchievement(Achievement $achievement)
    {
        $title = $achievement->title;
        $achievement->delete();

        return redirect()->route('admin.research.index')
            ->with('status', 'Achievement "' . $title . '" deleted.');
    }
}
