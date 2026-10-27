<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\ChatStatus;
use Illuminate\Http\Request;

class ChatConsoleController extends Controller
{
    public function index(Request $request)
    {
        $status = ChatStatus::current();
        $selectedConversationId = $request->query('conversation_id');

        $conversations = ChatConversation::with(['messages' => function ($q) {
            $q->latest()->limit(1);
        }])
        ->orderByDesc('last_message_at')
        ->get();

        $activeConversation = null;
        if ($selectedConversationId) {
            $activeConversation = ChatConversation::with(['messages' => function ($q) {
                $q->orderBy('created_at', 'asc');
            }])->find($selectedConversationId);
        } elseif ($conversations->isNotEmpty()) {
            $activeConversation = ChatConversation::with(['messages' => function ($q) {
                $q->orderBy('created_at', 'asc');
            }])->find($conversations->first()->id);
        }

        // Mark unread messages in active conversation as read
        if ($activeConversation) {
            $activeConversation->messages()
                ->where('sender', 'visitor')
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        return view('admin.chat.index', compact('status', 'conversations', 'activeConversation'));
    }

    /**
     * Send an admin reply.
     */
    public function reply(Request $request)
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
                'message' => $message->message,
                'time' => $message->created_at->format('h:i A'),
            ]);
        }

        return redirect()->route('admin.chat.index', ['conversation_id' => $conversation->id])
            ->with('status', 'Reply sent to visitor!');
    }

    /**
     * Polling endpoint for live messages in admin view.
     */
    public function poll(Request $request, ChatConversation $conversation)
    {
        // Mark as read
        $conversation->messages()
            ->where('sender', 'visitor')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = $conversation->messages()
            ->orderBy('created_at', 'asc')
            ->get(['id', 'sender', 'message', 'created_at']);

        return response()->json([
            'status' => 'success',
            'messages' => $messages->map(function ($msg) {
                return [
                    'id' => $msg->id,
                    'sender' => $msg->sender,
                    'message' => $msg->message,
                    'time' => $msg->created_at->format('h:i A'),
                ];
            }),
        ]);
    }
}
