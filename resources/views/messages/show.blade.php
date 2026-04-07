@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center mb-6">
        <a href="{{ route('messages.index') }}" class="mr-4 text-gray-500 hover:text-purple-600 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
        <div class="flex items-center space-x-3">
            @if($otherUser->avatar)
                <img src="{{ asset('storage/' . $otherUser->avatar) }}" alt="{{ $otherUser->name }}" class="w-10 h-10 rounded-full object-cover">
            @else
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white font-bold">
                    {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $otherUser->name }}</h1>
                @if($otherUser->average_rating > 0)
                    <div class="flex items-center text-sm text-gray-500">
                        <span class="text-yellow-400 mr-1">★</span>
                        {{ $otherUser->average_rating }} rating
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="glass-card rounded-2xl overflow-hidden flex flex-col" style="height: 70vh;">
        <!-- Messages -->
        <div class="flex-1 overflow-y-auto p-6 space-y-4" id="messages-container">
            @forelse($messages as $message)
                <div class="flex {{ $message->sender_id == auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-xs lg:max-w-md px-4 py-3 rounded-2xl {{ $message->sender_id == auth()->id() ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-900' }}">
                        <p class="text-sm">{{ $message->message }}</p>
                        <p class="text-xs mt-1 {{ $message->sender_id == auth()->id() ? 'text-purple-200' : 'text-gray-500' }}">
                            {{ $message->created_at->format('M d, g:i a') }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="text-center py-12">
                    <p class="text-gray-500">No messages yet. Start the conversation!</p>
                </div>
            @endforelse
        </div>

        <!-- Message Input -->
        <div class="border-t border-gray-200 p-4">
            <form method="POST" action="{{ route('messages.store') }}" class="flex space-x-4">
                @csrf
                <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                <input type="text" name="message" required maxlength="2000" autocomplete="off"
                    class="flex-1 rounded-full border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 px-4 py-2 @error('message') border-red-500 focus:border-red-500 focus:ring-red-500 @enderror"
                    placeholder="Type a message...">
                <button type="submit" class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-purple-600 text-white hover:bg-purple-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </form>
            @error('message')
                <p class="text-red-500 text-sm mt-2 ml-4">{{ $message }}</p>
            @enderror
        </div>
    </div>
</div>

<script>
// Scroll to bottom on load
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('messages-container');
    container.scrollTop = container.scrollHeight;
});
</script>
@endsection

