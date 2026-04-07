@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Messages</h1>
            <p class="mt-1 text-sm text-gray-600">Your conversations with other users.</p>
        </div>
    </div>

    <div class="glass-card rounded-2xl overflow-hidden">
        @if($conversations->count() > 0)
            <ul class="divide-y divide-gray-200">
                @foreach($conversations as $conversation)
                    @php
                        $otherUser = $conversation->getOtherUser(auth()->id());
                        $latestMessage = $conversation->latestMessage();
                        $unreadCount = $conversation->unreadCountFor(auth()->id());
                    @endphp
                    <li>
                        <a href="{{ route('messages.show', $conversation) }}" class="block hover:bg-gray-50 transition-colors">
                            <div class="px-6 py-4 flex items-center space-x-4">
                                <!-- Avatar -->
                                <div class="flex-shrink-0">
                                    @if($otherUser->avatar)
                                        <img src="{{ asset('storage/' . $otherUser->avatar) }}" alt="{{ $otherUser->name }}" class="w-12 h-12 rounded-full object-cover">
                                    @else
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white text-lg font-bold">
                                            {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <p class="text-sm font-semibold text-gray-900 truncate">
                                            {{ $otherUser->name }}
                                        </p>
                                        @if($latestMessage)
                                            <p class="text-xs text-gray-500">
                                                {{ $latestMessage->created_at->diffForHumans() }}
                                            </p>
                                        @endif
                                    </div>
                                    @if($latestMessage)
                                        <p class="text-sm text-gray-600 truncate {{ $unreadCount > 0 ? 'font-semibold' : '' }}">
                                            @if($latestMessage->sender_id == auth()->id())
                                                <span class="text-gray-400">You:</span>
                                            @endif
                                            {{ $latestMessage->message }}
                                        </p>
                                    @else
                                        <p class="text-sm text-gray-400 italic">No messages yet</p>
                                    @endif
                                </div>

                                <!-- Unread Badge -->
                                @if($unreadCount > 0)
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-purple-600 rounded-full">
                                            {{ $unreadCount }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No conversations yet</h3>
                <p class="mt-1 text-sm text-gray-500">Start a conversation by messaging an item owner.</p>
            </div>
        @endif
    </div>
</div>
@endsection

