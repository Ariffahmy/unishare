@extends('layouts.admin')

@section('title', 'Penalties Management')

@section('content')
<!-- Pending Alert -->
@if($pendingCount > 0)
    <div class="mb-6 p-4 bg-yellow-50 border border-yellow-200 rounded-xl flex items-center gap-3">
        <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-yellow-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
        </div>
        <div class="flex-grow">
            <p class="font-semibold text-yellow-800">{{ $pendingCount }} penalty {{ $pendingCount === 1 ? 'report' : 'reports' }} awaiting your review</p>
            <p class="text-sm text-yellow-600">Review evidence and approve or reject penalty reports.</p>
        </div>
        <a href="{{ route('admin.penalties', ['status' => 'pending']) }}" class="px-4 py-2 bg-yellow-500 text-white rounded-lg text-sm font-medium hover:bg-yellow-600 transition-colors flex-shrink-0">
            View Pending
        </a>
    </div>
@endif

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Penalties</p>
                <p class="text-2xl font-bold text-gray-900">{{ $typeCounts['all'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Late Returns</p>
                <p class="text-2xl font-bold text-orange-600">{{ $typeCounts['late_return'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Damaged Items</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $typeCounts['damaged'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-yellow-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                </svg>
            </div>
        </div>
    </div>
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Missing Items</p>
                <p class="text-2xl font-bold text-red-600">{{ $typeCounts['missing'] }}</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Total Penalty Points -->
<div class="bg-gradient-to-r from-red-500 to-orange-500 rounded-xl p-5 mb-6 text-white">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm opacity-90">Total Active Penalty Points</p>
            <p class="text-3xl font-bold">{{ number_format($totalPenaltyPoints) }} pts</p>
        </div>
        <svg class="w-10 h-10 opacity-50" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
        </svg>
    </div>
</div>

<!-- Filter Tabs -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 mb-6 p-2">
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.penalties') }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ !request('type') && !request('status') ? 'bg-red-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            All <span class="ml-1 px-2 py-0.5 rounded-full text-xs {{ !request('type') && !request('status') ? 'bg-red-500' : 'bg-gray-200' }}">{{ $typeCounts['all'] }}</span>
        </a>
        <a href="{{ route('admin.penalties', ['status' => 'pending']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('status') === 'pending' ? 'bg-yellow-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            ⏳ Pending <span class="ml-1 px-2 py-0.5 rounded-full text-xs {{ request('status') === 'pending' ? 'bg-yellow-400' : 'bg-yellow-100 text-yellow-800' }}">{{ $pendingCount }}</span>
        </a>
        <a href="{{ route('admin.penalties', ['type' => 'late_return']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('type') === 'late_return' ? 'bg-orange-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Late Returns <span class="ml-1 px-2 py-0.5 rounded-full text-xs {{ request('type') === 'late_return' ? 'bg-orange-400' : 'bg-orange-100 text-orange-800' }}">{{ $typeCounts['late_return'] }}</span>
        </a>
        <a href="{{ route('admin.penalties', ['type' => 'damaged']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('type') === 'damaged' ? 'bg-yellow-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Damaged <span class="ml-1 px-2 py-0.5 rounded-full text-xs {{ request('type') === 'damaged' ? 'bg-yellow-400' : 'bg-yellow-100 text-yellow-800' }}">{{ $typeCounts['damaged'] }}</span>
        </a>
        <a href="{{ route('admin.penalties', ['type' => 'missing']) }}" 
           class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ request('type') === 'missing' ? 'bg-red-500 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
            Missing <span class="ml-1 px-2 py-0.5 rounded-full text-xs {{ request('type') === 'missing' ? 'bg-red-400' : 'bg-red-100 text-red-800' }}">{{ $typeCounts['missing'] }}</span>
        </a>
    </div>
</div>

<!-- Penalties List -->
<div class="space-y-4">
    @forelse($penalties as $penalty)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden
            {{ $penalty->status === 'pending' ? 'border-l-4 border-l-yellow-400' : '' }}
            {{ $penalty->status === 'active' ? 'border-l-4 border-l-red-400' : '' }}
            {{ $penalty->status === 'rejected' ? 'border-l-4 border-l-gray-300 opacity-75' : '' }}
        ">
            <div class="p-5">
                <div class="flex items-start gap-5">
                    <!-- Type Icon -->
                    <div class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center
                        {{ $penalty->type === 'late_return' ? 'bg-orange-100' : '' }}
                        {{ $penalty->type === 'damaged' ? 'bg-yellow-100' : '' }}
                        {{ $penalty->type === 'missing' ? 'bg-red-100' : '' }}
                    ">
                        @if($penalty->type === 'late_return')
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @elseif($penalty->type === 'damaged')
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                        @else
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                        @endif
                    </div>

                    <!-- Details -->
                    <div class="flex-grow min-w-0">
                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                            <span class="font-semibold text-gray-900">{{ $penalty->type_label }}</span>
                            <span class="text-sm text-gray-400">#{{ $penalty->id }}</span>
                            <!-- Status Badge -->
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                {{ $penalty->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                {{ $penalty->status === 'active' ? 'bg-red-100 text-red-800' : '' }}
                                {{ $penalty->status === 'resolved' ? 'bg-green-100 text-green-800' : '' }}
                                {{ $penalty->status === 'waived' ? 'bg-gray-100 text-gray-600' : '' }}
                                {{ $penalty->status === 'rejected' ? 'bg-gray-100 text-gray-600' : '' }}
                            ">
                                <span class="w-1.5 h-1.5 mr-1.5 rounded-full
                                    {{ $penalty->status === 'pending' ? 'bg-yellow-400' : '' }}
                                    {{ $penalty->status === 'active' ? 'bg-red-400' : '' }}
                                    {{ $penalty->status === 'resolved' ? 'bg-green-400' : '' }}
                                    {{ $penalty->status === 'waived' ? 'bg-gray-400' : '' }}
                                    {{ $penalty->status === 'rejected' ? 'bg-gray-400' : '' }}
                                "></span>
                                {{ $penalty->status_label }}
                            </span>
                        </div>

                        <!-- People -->
                        <div class="flex items-center gap-4 text-sm text-gray-600 mb-2">
                            <span class="flex items-center gap-1">
                                <span class="font-medium text-gray-700">Borrower:</span>
                                <div class="w-5 h-5 rounded-full bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center text-white text-xs font-bold">
                                    {{ strtoupper(substr($penalty->borrower->name, 0, 1)) }}
                                </div>
                                {{ $penalty->borrower->name }}
                            </span>
                            <span class="text-gray-300">|</span>
                            <span>Reported by: <span class="font-medium">{{ $penalty->reportedBy->name }}</span></span>
                        </div>

                        <!-- Item -->
                        @if($penalty->borrowRequest && $penalty->borrowRequest->item)
                            <p class="text-sm text-gray-600 mb-1">
                                Item: <span class="font-medium text-gray-800">{{ $penalty->borrowRequest->item->title }}</span>
                                <span class="text-gray-400">• Request #{{ $penalty->borrow_request_id }}</span>
                            </p>
                        @endif

                        <!-- Reason -->
                        <p class="text-sm text-gray-700 bg-gray-50 rounded-lg px-3 py-2 mt-2">{{ $penalty->reason }}</p>

                        <!-- Evidence Photo -->
                        @if($penalty->evidence_photo)
                            <div class="mt-3">
                                <p class="text-xs text-gray-500 font-medium mb-1">📷 Evidence Photo:</p>
                                <a href="{{ asset('storage/' . $penalty->evidence_photo) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $penalty->evidence_photo) }}" 
                                         alt="Evidence" 
                                         class="w-48 h-36 object-cover rounded-lg border border-gray-200 hover:border-purple-400 hover:shadow-md transition-all cursor-pointer">
                                </a>
                            </div>
                        @endif

                        <!-- Meta -->
                        <p class="text-xs text-gray-400 mt-2">{{ $penalty->created_at->format('M d, Y \a\t g:i A') }} • {{ $penalty->created_at->diffForHumans() }}</p>
                    </div>

                    <!-- Right side: Points + Actions -->
                    <div class="flex-shrink-0 text-right space-y-3">
                        <!-- Points Badge -->
                        <div class="inline-flex items-center px-4 py-2 rounded-xl text-lg font-bold
                            {{ $penalty->type === 'late_return' ? 'bg-orange-100 text-orange-700' : '' }}
                            {{ $penalty->type === 'damaged' ? 'bg-yellow-100 text-yellow-700' : '' }}
                            {{ $penalty->type === 'missing' ? 'bg-red-100 text-red-700' : '' }}
                        ">
                            -{{ $penalty->penalty_points }} pts
                        </div>

                        <!-- Approve / Reject buttons for pending -->
                        @if($penalty->status === 'pending')
                            <div class="flex flex-col gap-2">
                                <form action="{{ route('admin.penalties.approve', $penalty) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" onclick="return confirm('Approve this penalty? Points will be deducted from the borrower.')"
                                        class="w-full px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700 transition-colors flex items-center justify-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Approve
                                    </button>
                                </form>
                                <form action="{{ route('admin.penalties.reject', $penalty) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" onclick="return confirm('Reject this penalty report? No points will be deducted.')"
                                        class="w-full px-4 py-2 bg-gray-200 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-300 transition-colors flex items-center justify-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Reject
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-12 text-center">
            <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-gray-500">No penalties found — all users are behaving well!</p>
        </div>
    @endforelse
</div>

<!-- Pagination -->
@if($penalties->hasPages())
    <div class="mt-6">
        {{ $penalties->withQueryString()->links() }}
    </div>
@endif
@endsection

