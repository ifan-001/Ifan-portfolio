<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Education;
use Illuminate\Http\Request;

class EducationController extends Controller
{
    public function index()
    {
        $educations = Education::orderBy('order')->orderByDesc('id')->get();
        return view('admin.education.index', compact('educations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'institution' => 'required|string|max:150',
            'degree' => 'required|string|max:150',
            'field' => 'nullable|string|max:100',
            'period' => 'required|string|max:100',
            'cgpa' => 'nullable|string|max:20',
            'scale' => 'nullable|string|max:10',
            'status' => 'required|string|max:100',
            'coursework_text' => 'nullable|string|max:1000',
            'order' => 'nullable|integer',
        ]);

        $coursework = [];
        if (!empty($validated['coursework_text'])) {
            $coursework = array_values(array_filter(array_map('trim', explode(',', $validated['coursework_text']))));
        }

        Education::create([
            'institution' => $validated['institution'],
            'degree' => $validated['degree'],
            'field' => $validated['field'],
            'period' => $validated['period'],
            'cgpa' => $validated['cgpa'],
            'scale' => $validated['scale'] ?: '4.00',
            'status' => $validated['status'],
            'achievements' => $coursework,
            'order' => $validated['order'] ?? (Education::max('order') + 1),
        ]);

        return redirect()->route('admin.education.index')
            ->with('status', 'Education record added successfully!');
    }

    public function update(Request $request, Education $education)
    {
        $validated = $request->validate([
            'institution' => 'required|string|max:150',
            'degree' => 'required|string|max:150',
            'field' => 'nullable|string|max:100',
            'period' => 'required|string|max:100',
            'cgpa' => 'nullable|string|max:20',
            'scale' => 'nullable|string|max:10',
            'status' => 'required|string|max:100',
            'coursework_text' => 'nullable|string|max:1000',
            'order' => 'nullable|integer',
        ]);

        $coursework = [];
        if (!empty($validated['coursework_text'])) {
            $coursework = array_values(array_filter(array_map('trim', explode(',', $validated['coursework_text']))));
        }

        $education->update([
            'institution' => $validated['institution'],
            'degree' => $validated['degree'],
            'field' => $validated['field'],
            'period' => $validated['period'],
            'cgpa' => $validated['cgpa'],
            'scale' => $validated['scale'] ?: '4.00',
            'status' => $validated['status'],
            'achievements' => $coursework,
            'order' => $validated['order'] ?? $education->order,
        ]);

        return redirect()->route('admin.education.index')
            ->with('status', 'Education updated successfully!');
    }

    public function destroy(Education $education)
    {
        $inst = $education->institution;
        $education->delete();

        return redirect()->route('admin.education.index')
            ->with('status', 'Education record for ' . $inst . ' deleted successfully.');
    }
}
