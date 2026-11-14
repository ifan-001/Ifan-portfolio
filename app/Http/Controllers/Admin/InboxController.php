<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\ChatStatus;
use App\Models\ContactMessage;
use Illuminate\Http\Request;

class InboxController extends Controller
{
    /**
     * Unified Inbox: List and conversation viewer.
     */
    public function index(Request $request)
    {
        // 1. If viewing a specific Live Chat conversation
        if ($request->filled('conversation_id')) {
            $conversation = ChatConversation::with(['messages' => function ($q) {
                $q->orderBy('created_at', 'asc');
            }])->findOrFail($request->query('conversation_id'));

            // Mark unread visitor messages as read
            $conversation->messages()
                ->where('sender', 'visitor')
                ->where('is_read', false)
                ->update(['is_read' => true]);

            $chatStatus = ChatStatus::current();

            return view('admin.inbox.chat_view', compact('conversation', 'chatStatus'));
        }

        // 2. If viewing a specific Contact Inquiry
        if ($request->filled('inquiry_id')) {
            $inquiry = ContactMessage::findOrFail($request->query('inquiry_id'));

            if ($inquiry->status === 'unread') {
                $inquiry->update(['status' => 'read']);
            }

            return view('admin.inbox.inquiry_view', compact('inquiry'));
        }

        // 3. Default: Unified Inbox List View
        $filter = $request->query('filter', 'all'); // all, random, named, inquiries, unread
        $search = $request->query('q');

        // Top Section: Random & Anonymous Portfolio Chats (No Name AND No Email)
        $randomChatsQuery = ChatConversation::with(['messages' => function ($q) {
            $q->latest()->limit(1);
        }])
        ->where(function ($q) {
            $q->whereNull('visitor_name')
                ->orWhere('visitor_name', '')
                ->orWhere('visitor_name', 'like', 'Visitor%')
                ->orWhere('visitor_name', 'like', 'Anonymous%')
                ->orWhere('visitor_name', 'like', 'Guest%');
        })
        ->where(function ($q) {
            $q->whereNull('visitor_email')
                ->orWhere('visitor_email', '');
        })
        ->orderByDesc('last_message_at');

        // Named Direct Chats (User provided their Name OR their Email/Gmail)
        $namedChatsQuery = ChatConversation::with(['messages' => function ($q) {
            $q->latest()->limit(1);
        }])
        ->where(function ($q) {
            $q->where(function ($sub) {
                $sub->whereNotNull('visitor_name')
                    ->where('visitor_name', '!=', '')
                    ->where('visitor_name', 'not like', 'Visitor%')
                    ->where('visitor_name', 'not like', 'Anonymous%')
                    ->where('visitor_name', 'not like', 'Guest%');
            })
            ->orWhere(function ($sub) {
                $sub->whereNotNull('visitor_email')
                    ->where('visitor_email', '!=', '');
            });
        })
        ->orderByDesc('last_message_at');

        // Named Contact Inquiries
        $inquiriesQuery = ContactMessage::orderByDesc('created_at');

        if (!empty($search)) {
            $inquiriesQuery->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%");
            });

            $randomChatsQuery->whereHas('messages', function ($q) use ($search) {
                $q->where('message', 'like', "%{$search}%");
            });

            $namedChatsQuery->where(function ($q) use ($search) {
                $q->where('visitor_name', 'like', "%{$search}%")
                    ->orWhere('visitor_email', 'like', "%{$search}%")
                    ->orWhereHas('messages', function ($sub) use ($search) {
                        $sub->where('message', 'like', "%{$search}%");
                    });
            });
        }

        // Cap each list so the inbox stays fast as history grows; counts below use full totals.
        $limit = 100;
        $randomTotal = (clone $randomChatsQuery)->count();
        $namedTotal = (clone $namedChatsQuery)->count();
        $inquiryTotal = (clone $inquiriesQuery)->count();

        $randomChats = $randomChatsQuery->limit($limit)->get();
        $inquiries = $inquiriesQuery->limit($limit)->get();
        $namedChats = $namedChatsQuery->limit($limit)->get();

        $unreadChatCount = ChatMessage::where('sender', 'visitor')->where('is_read', false)->count();
        $unreadInquiryCount = ContactMessage::where('status', 'unread')->count();

        $counts = [
            'all' => $randomTotal + $inquiryTotal + $namedTotal,
            'random' => $randomTotal,
            'named' => $namedTotal,
            'inquiries' => $inquiryTotal,
            'unread' => $unreadChatCount + $unreadInquiryCount,
        ];

        return view('admin.inbox.index', compact(
            'randomChats',
            'inquiries',
            'namedChats',
            'filter',
            'search',
            'counts'
        ));
    }

    /**
     * Unread counters and latest preview for the admin alert poller.
     */
    public function notificationsCheck()
    {
        $chatUnread = ChatMessage::where('sender', 'visitor')->where('is_read', false)->count();
        $inquiryUnread = ContactMessage::where('status', 'unread')->count();
        $latestChat = ChatMessage::where('sender', 'visitor')->where('is_read', false)->latest()->first();
        $latestInquiry = ContactMessage::where('status', 'unread')->latest()->first();

        return response()->json([
            'total_unread' => $chatUnread + $inquiryUnread,
            'chat_unread' => $chatUnread,
            'inquiry_unread' => $inquiryUnread,
            'latest_preview' => $latestChat ? $latestChat->message : ($latestInquiry ? $latestInquiry->message : null),
            'latest_sender' => $latestInquiry ? $latestInquiry->name : 'Live Visitor',
        ]);
    }

    /**
     * Send an admin reply to a live chat thread.
     */
    public function replyChat(Request $request)
    {
        $validated = $request->validate([
            'conversation_id' => 'required|exists:chat_conversations,id',
            'message' => 'required|string|min:1|max:2000',
        ]);

        $conversation = ChatConversation::findOrFail($validated['conversation_id']);

        $message = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender' => 'admin',
            'message' => $validated['message'],
            'is_read' => true,
        ]);

        $conversation->update(['last_message_at' => now()]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message_id' => $message->id,
                'created_at' => $message->created_at->format('g:i A'),
            ]);
        }

        return redirect()->route('admin.inbox.index', ['conversation_id' => $conversation->id])
            ->with('status', 'Reply sent successfully.');
    }

    /**
     * Update inquiry status.
     */
    public function updateInquiryStatus(Request $request, ContactMessage $inquiry)
    {
        $validated = $request->validate([
            'status' => 'required|in:unread,read,replied,archived',
        ]);

        $inquiry->update(['status' => $validated['status']]);

        return redirect()->back()->with('status', 'Inquiry marked as ' . ucfirst($validated['status']));
    }

    /**
     * Delete an inquiry.
     */
    public function destroyInquiry(ContactMessage $inquiry)
    {
        $inquiry->delete();

        return redirect()->route('admin.inbox.index')->with('status', 'Inquiry deleted successfully.');
    }

    /**
     * Delete a chat conversation.
     */
    public function destroyChat(ChatConversation $conversation)
    {
        $conversation->messages()->delete();
        $conversation->delete();

        return redirect()->route('admin.inbox.index')->with('status', 'Conversation deleted.');
    }
}
