@extends('layouts.admin')

@section('title', 'Borrow Requests')

@section('content')
<!-- Status Filter Tabs -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6 p-2">
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.borrow-requests') }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ !request('status') ? 'bg-purple-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            All <span class="ml-1 px-2 py-0.5 rounded-full text-xs {{ !request('status') ? 'bg-purple-500' : 'bg-gray-200' }}">{{ $statusCounts['all'] }}</span>
        </a>
        <a href="{{ route('admin.borrow-requests', ['status' => 'pending']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') === 'pending' ? 'bg-yellow-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Pending <span class="ml-1 px-2 py-0.5 rounded-full text-xs {{ request('status') === 'pending' ? 'bg-yellow-400' : 'bg-yellow-100 text-yellow-800' }}">{{ $statusCounts['pending'] }}</span>
        </a>
        <a href="{{ route('admin.borrow-requests', ['status' => 'approved']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') === 'approved' ? 'bg-green-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Approved <span class="ml-1 px-2 py-0.5 rounded-full text-xs {{ request('status') === 'approved' ? 'bg-green-400' : 'bg-green-100 text-green-800' }}">{{ $statusCounts['approved'] }}</span>
        </a>
        <a href="{{ route('admin.borrow-requests', ['status' => 'borrowed']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') === 'borrowed' ? 'bg-blue-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Borrowed <span class="ml-1 px-2 py-0.5 rounded-full text-xs {{ request('status') === 'borrowed' ? 'bg-blue-400' : 'bg-blue-100 text-blue-800' }}">{{ $statusCounts['borrowed'] }}</span>
        </a>
        <a href="{{ route('admin.borrow-requests', ['status' => 'returned']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') === 'returned' ? 'bg-purple-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Returned <span class="ml-1 px-2 py-0.5 rounded-full text-xs {{ request('status') === 'returned' ? 'bg-purple-400' : 'bg-purple-100 text-purple-800' }}">{{ $statusCounts['returned'] }}</span>
        </a>
        <a href="{{ route('admin.borrow-requests', ['status' => 'overdue']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') === 'overdue' ? 'bg-orange-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Overdue <span class="ml-1 px-2 py-0.5 rounded-full text-xs {{ request('status') === 'overdue' ? 'bg-orange-400' : 'bg-orange-100 text-orange-800' }}">{{ $statusCounts['overdue'] }}</span>
        </a>
        <a href="{{ route('admin.borrow-requests', ['status' => 'missing']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') === 'missing' ? 'bg-red-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Missing <span class="ml-1 px-2 py-0.5 rounded-full text-xs {{ request('status') === 'missing' ? 'bg-red-500' : 'bg-red-100 text-red-800' }}">{{ $statusCounts['missing'] }}</span>
        </a>
        <a href="{{ route('admin.borrow-requests', ['status' => 'rejected']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') === 'rejected' ? 'bg-red-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Rejected <span class="ml-1 px-2 py-0.5 rounded-full text-xs {{ request('status') === 'rejected' ? 'bg-red-400' : 'bg-red-100 text-red-800' }}">{{ $statusCounts['rejected'] }}</span>
        </a>
        <a href="{{ route('admin.borrow-requests', ['status' => 'cancelled']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') === 'cancelled' ? 'bg-gray-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Cancelled <span class="ml-1 px-2 py-0.5 rounded-full text-xs {{ request('status') === 'cancelled' ? 'bg-gray-400' : 'bg-gray-200' }}">{{ $statusCounts['cancelled'] }}</span>
        </a>
    </div>
</div>

<!-- Requests Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Borrower</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lender</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Duration</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Points</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($requests as $request)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            #{{ $request->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($request->item)
                                    @if($request->item->photos->count() > 0)
                                        <img src="{{ asset('storage/' . $request->item->photos->first()->photo_path) }}" 
                                             alt="{{ $request->item->title }}" 
                                             class="w-10 h-10 rounded-lg object-cover mr-3">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-gray-200 mr-3 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ Str::limit($request->item->title, 25) }}</div>
                                        <div class="text-xs text-gray-500">{{ $request->item->category }}</div>
                                    </div>
                                @else
                                    <span class="text-gray-400 italic">Item deleted</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white text-sm font-bold mr-2">
                                    {{ strtoupper(substr($request->borrower->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $request->borrower->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $request->borrower->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center text-white text-sm font-bold mr-2">
                                    {{ strtoupper(substr($request->lender->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $request->lender->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $request->lender->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            <div class="flex flex-col">
                                <span>{{ \Carbon\Carbon::parse($request->start_date)->format('M d') }} - {{ \Carbon\Carbon::parse($request->end_date)->format('M d, Y') }}</span>
                                <span class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($request->start_date)->diffInDays(\Carbon\Carbon::parse($request->end_date)) + 1 }} days</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-sm font-semibold bg-amber-100 text-amber-800">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/>
                                </svg>
                                {{ $request->total_points ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $request->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $request->status === 'approved' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $request->status === 'rejected' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $request->status === 'borrowed' ? 'bg-blue-100 text-blue-800' : '' }}
                                {{ $request->status === 'returned' ? 'bg-purple-100 text-purple-800' : '' }}
                                {{ $request->status === 'overdue' ? 'bg-orange-100 text-orange-800' : '' }}
                                {{ $request->status === 'missing' ? 'bg-red-200 text-red-900' : '' }}
                                {{ $request->status === 'cancelled' ? 'bg-gray-100 text-gray-600' : '' }}
                            ">
                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full
                                    {{ $request->status === 'pending' ? 'bg-yellow-400' : '' }}
                                    {{ $request->status === 'approved' ? 'bg-green-400' : '' }}
                                    {{ $request->status === 'rejected' ? 'bg-red-400' : '' }}
                                    {{ $request->status === 'borrowed' ? 'bg-blue-400' : '' }}
                                    {{ $request->status === 'returned' ? 'bg-purple-400' : '' }}
                                    {{ $request->status === 'overdue' ? 'bg-orange-400' : '' }}
                                    {{ $request->status === 'missing' ? 'bg-red-600' : '' }}
                                    {{ $request->status === 'cancelled' ? 'bg-gray-400' : '' }}
                                "></span>
                                {{ ucfirst($request->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <div class="flex flex-col">
                                <span>{{ $request->created_at->format('M d, Y') }}</span>
                                <span class="text-xs text-gray-400">{{ $request->created_at->diffForHumans() }}</span>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="text-gray-500">No borrow requests found</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($requests->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $requests->withQueryString()->links() }}
        </div>
    @endif
</div>
@endsection

