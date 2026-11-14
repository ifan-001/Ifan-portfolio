<?php

namespace App\Http\Controllers;

use App\Models\ChatConversation;
use App\Models\ChatMessage;
use App\Models\ChatStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    /**
     * Get current live chat status (Online / Offline).
     */
    public function getStatus()
    {
        $status = ChatStatus::current();

        return response()->json([
            'is_online' => (bool) $status->is_online,
            'status_text' => $status->status_text,
            'last_active_at' => $status->last_active_at?->diffForHumans() ?? 'Recently',
        ]);
    }

    /**
     * Toggle or update live chat status.
     * Accessible by Hasnain to switch active/offline.
     */
    public function toggleStatus(Request $request)
    {
        $status = ChatStatus::current();
        
        if ($request->has('is_online')) {
            $isOnline = filter_var($request->input('is_online'), FILTER_VALIDATE_BOOLEAN);
        } else {
            $isOnline = !$status->is_online;
        }

        $status->is_online = $isOnline;
        $status->status_text = $isOnline ? 'Active Now' : 'Offline';
        $status->last_active_at = now();
        $status->save();

        return response()->json([
            'status' => 'success',
            'is_online' => $status->is_online,
            'status_text' => $status->status_text,
            'message' => 'Status updated to ' . ($status->is_online ? 'Active' : 'Offline'),
        ]);
    }

    /**
     * Fetch conversation and message history for current session.
     */
    public function getMessages(Request $request)
    {
        $sessionId = (string) $request->input('session_id', '');
        $status = ChatStatus::current();

        // Read-only: never create a conversation from a GET with a client-chosen ID.
        $conversation = $sessionId !== ''
            ? ChatConversation::where('session_id', $sessionId)->first()
            : null;

        if (!$conversation) {
            return response()->json([
                'session_id' => null,
                'is_online' => (bool) $status->is_online,
                'status_text' => $status->status_text,
                'messages' => [],
            ]);
        }

        $messages = $conversation->messages()
            ->orderBy('created_at', 'asc')
            ->get(['id', 'sender', 'message', 'created_at']);

        return response()->json([
            'session_id' => $conversation->session_id,
            'is_online' => (bool) $status->is_online,
            'status_text' => $status->status_text,
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

    /**
     * Send a visitor chat message.
     */
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'message' => 'required|string|min:1|max:2000',
            'session_id' => 'nullable|string|max:100',
            'visitor_name' => 'nullable|string|max:100',
            'visitor_email' => 'nullable|email|max:150',
        ]);

        // Only continue an existing thread; otherwise the server issues a fresh, unguessable token.
        $conversation = !empty($validated['session_id'])
            ? ChatConversation::where('session_id', $validated['session_id'])->first()
            : null;

        if (!$conversation) {
            $conversation = ChatConversation::create([
                'session_id' => Str::random(48),
                'visitor_name' => $validated['visitor_name'] ?? null,
                'visitor_email' => $validated['visitor_email'] ?? null,
                'last_message_at' => now(),
            ]);
        }

        if (!empty($validated['visitor_name'])) {
            $conversation->visitor_name = $validated['visitor_name'];
        }
        if (!empty($validated['visitor_email'])) {
            $conversation->visitor_email = $validated['visitor_email'];
        }
        $conversation->last_message_at = now();
        $conversation->save();

        $message = ChatMessage::create([
            'conversation_id' => $conversation->id,
            'sender' => 'visitor',
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        return response()->json([
            'status' => 'success',
            'session_id' => $conversation->session_id,
            'message_id' => $message->id,
            'message' => $message->message,
            'time' => $message->created_at->format('h:i A'),
        ]);
    }

    /**
     * Admin/Hasnain Reply to a conversation.
     * Prepared for Web & Future Mobile App.
     */
    public function adminReply(Request $request)
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

        $conversation->last_message_at = now();
        $conversation->save();

        return response()->json([
            'status' => 'success',
            'message_id' => $message->id,
            'message' => $message->message,
            'time' => $message->created_at->format('h:i A'),
        ]);
    }

    /**
     * List all conversations for Admin / Mobile App view.
     */
    public function listConversations()
    {
        $conversations = ChatConversation::with(['messages' => function ($q) {
            $q->latest()->limit(1);
        }])
        ->orderByDesc('last_message_at')
        ->paginate(20);

        return response()->json([
            'conversations' => $conversations,
        ]);
    }
}
