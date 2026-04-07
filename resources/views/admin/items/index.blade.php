@extends('layouts.admin')

@section('title', 'Item Management')

@section('content')
<!-- Search & Filters -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 mb-6">
    <form method="GET" action="{{ route('admin.items') }}" class="flex flex-col sm:flex-row gap-4">
        <div class="flex-1">
            <input type="text" name="search" value="{{ request('search') }}" 
                placeholder="Search by title or description..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
        </div>
        <div class="sm:w-48">
            <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                <option value="">All Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>
        <div class="sm:w-48">
            <select name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500">
                <option value="">All Categories</option>
                <option value="Electronics" {{ request('category') === 'Electronics' ? 'selected' : '' }}>Electronics</option>
                <option value="Books & Textbooks" {{ request('category') === 'Books & Textbooks' ? 'selected' : '' }}>Books & Textbooks</option>
                <option value="Clothing & Accessories" {{ request('category') === 'Clothing & Accessories' ? 'selected' : '' }}>Clothing & Accessories</option>
                <option value="Sports Equipment" {{ request('category') === 'Sports Equipment' ? 'selected' : '' }}>Sports Equipment</option>
                <option value="Lab Equipment" {{ request('category') === 'Lab Equipment' ? 'selected' : '' }}>Lab Equipment</option>
                <option value="Furniture" {{ request('category') === 'Furniture' ? 'selected' : '' }}>Furniture</option>
                <option value="Kitchen & Dining" {{ request('category') === 'Kitchen & Dining' ? 'selected' : '' }}>Kitchen & Dining</option>
                <option value="Other" {{ request('category') === 'Other' ? 'selected' : '' }}>Other</option>
            </select>
        </div>
        <button type="submit" class="px-6 py-2 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors">
            Filter
        </button>
        @if(request('search') || request('status') || request('category'))
            <a href="{{ route('admin.items') }}" class="px-6 py-2 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors">
                Clear
            </a>
        @endif
    </form>
</div>

<!-- Items Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Points/Day</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Condition</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($items as $item)
                    <tr class="hover:bg-gray-50 {{ !$item->is_active ? 'bg-gray-50' : '' }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($item->primaryPhoto)
                                    <img src="{{ asset('storage/' . $item->primaryPhoto->photo_path) }}" 
                                         alt="{{ $item->title }}" 
                                         class="w-12 h-12 rounded-lg object-cover flex-shrink-0">
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-gray-200 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ Str::limit($item->title, 30) }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($item->description, 40) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.users.show', $item->owner) }}" class="text-sm text-purple-600 hover:text-purple-900">
                                {{ $item->owner->name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $item->category ?? 'None' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $item->points_per_day }} pts
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $item->condition ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600' }}">
                                {{ $item->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $item->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end space-x-2">
                                <a href="{{ route('items.show', $item) }}" target="_blank" class="text-purple-600 hover:text-purple-900">View</a>
                                
                                <form action="{{ route('admin.items.toggle-status', $item) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="{{ $item->is_active ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900' }}">
                                        {{ $item->is_active ? 'Deactivate' : 'Activate' }}
                                    </button>
                                </form>

                                <form action="{{ route('admin.items.delete', $item) }}" method="POST" class="inline" 
                                      onsubmit="return confirm('Are you sure you want to delete this item? This cannot be undone.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-8 text-center text-gray-500">No items found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($items->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $items->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection

