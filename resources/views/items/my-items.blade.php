@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">My Items</h1>
            <p class="mt-2 text-sm text-gray-600">Manage the items you've posted to the community.</p>
        </div>
        <a href="{{ route('items.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Post New Item
        </a>
    </div>

    <!-- Items Grid -->
    @if($items->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($items as $item)
                <div class="glass-card rounded-xl overflow-hidden hover:shadow-lg transition duration-300">
                    <!-- Image/Photo -->
                    @if($item->primaryPhoto)
                        <img src="{{ $item->primaryPhoto->url }}" alt="{{ $item->title }}" class="h-48 w-full object-cover">
                    @else
                        <div class="h-48 bg-gray-200 w-full object-cover flex items-center justify-center text-gray-400 hover:bg-gray-300 transition-colors">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                    
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <span class="px-2 py-1 text-xs font-semibold bg-purple-100 text-purple-800 rounded-full">
                                {{ $item->category ?? 'General' }}
                            </span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $item->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        
                        <h3 class="text-xl font-bold text-gray-900 mb-2 truncate" title="{{ $item->title }}">{{ $item->title }}</h3>
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $item->description }}</p>
                        
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <span class="font-medium">{{ $item->points_per_day }} pts/day</span>
                            <span>Max {{ $item->max_days }} days</span>
                        </div>

                        <!-- Actions -->
                        <div class="pt-4 border-t border-gray-100 flex items-center justify-between gap-2">
                            <a href="{{ route('items.show', $item) }}" class="text-purple-600 hover:text-purple-800 font-medium text-sm">
                                View
                            </a>
                            <a href="{{ route('items.edit', $item) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                Edit
                            </a>
                            
                            <!-- Toggle Active/Inactive -->
                            <form action="{{ route('items.toggle-status', $item) }}" method="POST" class="inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-sm font-medium {{ $item->is_active ? 'text-orange-600 hover:text-orange-800' : 'text-green-600 hover:text-green-800' }}">
                                    {{ $item->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>

                            <!-- Delete -->
                            <form action="{{ route('items.destroy', $item) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this item?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="glass-card rounded-2xl p-12 text-center">
            <svg class="mx-auto h-16 w-16 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900">No items yet</h3>
            <p class="mt-2 text-sm text-gray-500">Get started by posting your first item to the community.</p>
            <div class="mt-6">
                <a href="{{ route('items.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                    </svg>
                    Post Your First Item
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

