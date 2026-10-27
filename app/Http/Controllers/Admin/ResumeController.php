<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ResumeController extends Controller
{
    public function index()
    {
        $profile = Profile::current();
        $resumeFilePath = public_path($profile->resume_path);
        $fileExists = File::exists($resumeFilePath);
        $fileSize = $fileExists ? round(File::size($resumeFilePath) / 1024, 1) . ' KB' : 'Missing';
        $lastModified = $fileExists ? date('d M Y, h:i A', File::lastModified($resumeFilePath)) : 'N/A';

        return view('admin.resume.index', compact('profile', 'fileExists', 'fileSize', 'lastModified'));
    }

    public function update(Request $request)
    {
        $profile = Profile::current();

        $validated = $request->validate([
            'resume_pdf' => 'nullable|file|mimes:pdf|max:10240',
            'resume_available' => 'nullable|boolean',
            'resume_status_note' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('resume_pdf')) {
            $file = $request->file('resume_pdf');
            $fileName = 'Hasnain-Al-Ifan-CV.pdf';
            $destination = public_path('resume');
            if (!File::isDirectory($destination)) {
                File::makeDirectory($destination, 0755, true);
            }
            $file->move($destination, $fileName);
            $profile->resume_path = '/resume/' . $fileName;
        }

        $profile->resume_available = $request->boolean('resume_available');
        if ($request->filled('resume_status_note')) {
            $profile->resume_status_note = $validated['resume_status_note'];
        }
        $profile->save();

        return redirect()->route('admin.resume.index')
            ->with('status', 'Resume settings & PDF file updated successfully!');
    }
}
