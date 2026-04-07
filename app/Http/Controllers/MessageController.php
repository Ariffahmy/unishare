<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display all conversations for the authenticated user
     */
    public function index()
    {
        $userId = Auth::id();
        
        $conversations = Conversation::where('user_one_id', $userId)
            ->orWhere('user_two_id', $userId)
            ->with(['userOne', 'userTwo', 'messages' => function ($query) {
                $query->latest()->limit(1);
            }])
            ->get()
            ->sortByDesc(function ($conversation) {
                $latestMessage = $conversation->latestMessage();
                return $latestMessage ? $latestMessage->created_at : $conversation->created_at;
            });

        return view('messages.index', compact('conversations'));
    }

    /**
     * Display a specific conversation
     */
    public function show(Conversation $conversation)
    {
        $userId = Auth::id();
        
        // Ensure user is part of this conversation
        if (!$conversation->hasUser($userId)) {
            abort(403, 'Unauthorized');
        }

        // Mark messages as read
        $conversation->messages()
            ->where('sender_id', '!=', $userId)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = $conversation->messages()->with('sender')->get();
        $otherUser = $conversation->getOtherUser($userId);

        return view('messages.show', compact('conversation', 'messages', 'otherUser'));
    }

    /**
     * Send a new message
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'conversation_id' => 'required|exists:conversations,id',
            'message' => 'required|string|max:2000',
        ]);

        $conversation = Conversation::findOrFail($validated['conversation_id']);
        $userId = Auth::id();

        // Ensure user is part of this conversation
        if (!$conversation->hasUser($userId)) {
            abort(403, 'Unauthorized');
        }

        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $userId,
            'message' => $validated['message'],
        ]);

        return back()->with('success', 'Message sent!');
    }

    /**
     * Start a new conversation with a user
     */
    public function startConversation(Request $request)
    {
        $validated = $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'message' => 'required|string|max:2000',
        ]);

        $userId = Auth::id();
        $recipientId = $validated['recipient_id'];

        // Can't message yourself
        if ($userId == $recipientId) {
            return back()->with('error', 'You cannot message yourself.');
        }

        // Get or create conversation
        $conversation = Conversation::findOrCreateBetween($userId, $recipientId);

        // Create the message
        Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $userId,
            'message' => $validated['message'],
        ]);

        return redirect()->route('messages.show', $conversation)->with('success', 'Message sent!');
    }

    /**
     * Get unread messages count (for AJAX)
     */
    public function unreadCount()
    {
        return response()->json([
            'count' => Auth::user()->unread_messages_count
        ]);
    }
}
