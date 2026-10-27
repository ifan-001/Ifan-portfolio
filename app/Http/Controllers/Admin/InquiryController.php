<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $query = ContactMessage::orderByDesc('created_at');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $inquiries = $query->paginate(15);
        $counts = [
            'all' => ContactMessage::count(),
            'unread' => ContactMessage::where('status', 'unread')->count(),
            'replied' => ContactMessage::where('status', 'replied')->count(),
            'archived' => ContactMessage::where('status', 'archived')->count(),
        ];

        return view('admin.inquiries.index', compact('inquiries', 'counts', 'status'));
    }

    public function show(ContactMessage $inquiry)
    {
        if ($inquiry->status === 'unread') {
            $inquiry->update(['status' => 'read']);
        }

        return view('admin.inquiries.show', compact('inquiry'));
    }

    public function updateStatus(Request $request, ContactMessage $inquiry)
    {
        $validated = $request->validate([
            'status' => 'required|in:unread,read,replied,archived',
        ]);

        $inquiry->update(['status' => $validated['status']]);

        return redirect()->back()->with('status', 'Inquiry marked as ' . ucfirst($validated['status']));
    }

    public function destroy(ContactMessage $inquiry)
    {
        $inquiry->delete();
        return redirect()->route('admin.inquiries.index')->with('status', 'Inquiry deleted successfully.');
    }
}
