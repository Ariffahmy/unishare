@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Users</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_users']) }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </div>
        </div>
        <a href="{{ route('admin.users') }}" class="text-sm text-purple-600 hover:text-purple-800 mt-3 inline-block">View all →</a>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Total Items</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_items']) }}</p>
                <p class="text-xs text-green-600 mt-1">{{ $stats['active_items'] }} active</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
        </div>
        <a href="{{ route('admin.items') }}" class="text-sm text-purple-600 hover:text-purple-800 mt-3 inline-block">View all →</a>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Borrow Requests</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_requests']) }}</p>
                <p class="text-xs text-yellow-600 mt-1">{{ $stats['pending_requests'] }} pending</p>
            </div>
            <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
        </div>
        <a href="{{ route('admin.borrow-requests') }}" class="text-sm text-purple-600 hover:text-purple-800 mt-3 inline-block">View all →</a>
    </div>

    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500">Completed</p>
                <p class="text-3xl font-bold text-gray-900 mt-1">{{ number_format($stats['completed_transactions']) }}</p>
                <p class="text-xs text-purple-600 mt-1">{{ $stats['total_ratings'] }} ratings</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <a href="{{ route('admin.reports') }}" class="text-sm text-purple-600 hover:text-purple-800 mt-3 inline-block">View reports →</a>
    </div>
</div>

<!-- Recent Activity Grid -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Recent Users -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-900">Recent Users</h3>
            <a href="{{ route('admin.users') }}" class="text-sm text-purple-600 hover:text-purple-800">View all</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentUsers as $user)
                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-purple-500 flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">{{ $user->created_at ? $user->created_at->diffForHumans() : 'N/A' }}</p>
                        @if($user->is_admin)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">Admin</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-gray-500">No users yet</div>
            @endforelse
        </div>
    </div>

    <!-- Recent Items -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-900">Recent Items</h3>
            <a href="{{ route('admin.items') }}" class="text-sm text-purple-600 hover:text-purple-800">View all</a>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($recentItems as $item)
                <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50">
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-900 truncate">{{ $item->title }}</p>
                        <p class="text-sm text-gray-500">by {{ $item->owner->name }}</p>
                    </div>
                    <div class="text-right ml-4">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $item->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                            {{ $item->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        <p class="text-sm text-gray-500 mt-1">{{ $item->points_per_day }} pts/day</p>
                    </div>
                </div>
            @empty
                <div class="px-6 py-8 text-center text-gray-500">No items yet</div>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent Requests -->
<div class="mt-8 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-semibold text-gray-900">Recent Borrow Requests</h3>
        <a href="{{ route('admin.borrow-requests') }}" class="text-sm text-purple-600 hover:text-purple-800">View all</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Borrower</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lender</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($recentRequests as $request)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $request->item->title ?? 'Deleted' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $request->borrower->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">{{ $request->lender->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $request->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $request->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $request->status === 'borrowed' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $request->status === 'returned' ? 'bg-gray-100 text-gray-800' : '' }}
                                {{ $request->status === 'cancelled' ? 'bg-gray-100 text-gray-600' : '' }}
                            ">
                                {{ ucfirst($request->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $request->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">No requests yet</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

