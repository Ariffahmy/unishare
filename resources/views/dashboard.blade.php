@extends('layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Welcome Section -->
    <div class="glass-card rounded-2xl p-8 text-center relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-4xl font-extrabold text-gray-900 mb-4">
                Welcome back, <span class="gradient-text">{{ Auth::user()->name }}</span>!
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Explore items shared by your community or post your own to earn points.
            </p>
        </div>
        <!-- Decorative bg blob -->
        <div class="absolute top-0 left-0 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob"></div>
        <div class="absolute top-0 right-0 translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-purple-200 rounded-full mix-blend-multiply filter blur-3xl opacity-30 animate-blob animation-delay-2000"></div>
    </div>

    <!-- Search/Filter Section -->
    <div class="glass-card rounded-xl p-6">
        <form method="GET" action="{{ route('dashboard') }}" class="flex flex-col sm:flex-row gap-4">
            <!-- Search Bar -->
            <div class="flex-1">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 sm:text-sm" 
                        placeholder="Search items...">
                </div>
            </div>

            <!-- Category Filter -->
            <div class="sm:w-64">
                <select name="category" 
                    class="block w-full py-2.5 px-3 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 sm:text-sm">
                    <option value="">All Categories</option>
                    <option value="Electronics" {{ request('category') == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                    <option value="Books & Textbooks" {{ request('category') == 'Books & Textbooks' ? 'selected' : '' }}>Books & Textbooks</option>
                    <option value="Clothing & Accessories" {{ request('category') == 'Clothing & Accessories' ? 'selected' : '' }}>Clothing & Accessories</option>
                    <option value="Sports Equipment" {{ request('category') == 'Sports Equipment' ? 'selected' : '' }}>Sports Equipment</option>
                    <option value="Lab Equipment" {{ request('category') == 'Lab Equipment' ? 'selected' : '' }}>Lab Equipment</option>
                    <option value="Furniture" {{ request('category') == 'Furniture' ? 'selected' : '' }}>Furniture</option>
                    <option value="Kitchen & Dining" {{ request('category') == 'Kitchen & Dining' ? 'selected' : '' }}>Kitchen & Dining</option>
                    <option value="Other" {{ request('category') == 'Other' ? 'selected' : '' }}>Other</option>
                </select>
            </div>

            <!-- Filter Button -->
            <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                </svg>
                Filter
            </button>

            <!-- Clear Filters Link -->
            @if(request('search') || request('category'))
                <a href="{{ route('dashboard') }}" class="inline-flex items-center justify-center px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors">
                    Clear
                </a>
            @endif
        </form>
    </div>

    <!-- Results Header -->
    <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">
            @if(request('search') || request('category'))
                Search Results
                @if($items->count())
                    <span class="text-lg font-normal text-gray-500">({{ $items->count() }} {{ $items->count() === 1 ? 'item' : 'items' }})</span>
                @endif
            @else
                Recent Items
            @endif
        </h2>
    </div>

    <!-- Items Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($items as $item)
            <div class="glass-card rounded-xl overflow-hidden hover:shadow-lg transition duration-300 transform hover:-translate-y-1 group flex flex-col h-full">
                <!-- Item Image -->
                <div class="h-48 bg-gradient-to-br from-gray-100 to-gray-200 w-full relative overflow-hidden">
                    @if($item->primaryPhoto)
                        <img src="{{ asset('storage/' . $item->primaryPhoto->photo_path) }}" 
                             alt="{{ $item->title }}" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400 group-hover:text-gray-500 transition-colors">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                    <!-- Overlay gradient for better text readability if needed -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                </div>
                
                <div class="p-6 flex flex-col flex-grow">
                    <div class="flex justify-between items-start mb-2">
                        <span class="px-2 py-1 text-xs font-semibold bg-purple-100 text-purple-800 rounded-full">
                            {{ $item->category ?? 'General' }}
                        </span>
                        <span class="text-sm font-bold text-gray-900 flex items-center">
                            {{ $item->points_per_day }} pts/day
                        </span>
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-2 truncate" title="{{ $item->title }}">{{ $item->title }}</h3>
                    <p class="text-gray-600 text-sm mb-4 line-clamp-2 flex-grow">{{ $item->description }}</p>
                    
                    <div class="pt-4 border-t border-gray-100 flex items-center justify-between mt-auto">
                        <span class="text-sm text-gray-500">Condition: <span class="font-medium text-gray-800">{{ $item->condition ?? 'N/A' }}</span></span>
                        <a href="{{ route('items.show', $item) }}" class="text-purple-600 hover:text-purple-800 font-medium text-sm flex items-center">
                            View Details <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No items available</h3>
                <p class="mt-1 text-sm text-gray-500">Be the first to share an item with the community!</p>
                <div class="mt-6">
                    <a href="{{ route('items.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                        </svg>
                        Post Item
                    </a>
                </div>
            </div>
        @endforelse
    </div>
</div>
@endsection

