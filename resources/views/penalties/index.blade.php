@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Penalties</h1>
        <p class="text-gray-500 mt-1">Track all penalties applied to your account</p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <!-- Total Penalties -->
        <div class="glass-card rounded-xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $penalties->count() }}</p>
                    <p class="text-xs text-gray-500">Total Penalties</p>
                </div>
            </div>
        </div>

        <!-- Total Points Lost -->
        <div class="glass-card rounded-xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-red-600">-{{ $totalPoints }}</p>
                    <p class="text-xs text-gray-500">Points Lost</p>
                </div>
            </div>
        </div>

        <!-- Late Returns -->
        <div class="glass-card rounded-xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $typeCounts['late_return'] }}</p>
                    <p class="text-xs text-gray-500">Late Returns</p>
                </div>
            </div>
        </div>

        <!-- Damaged + Missing -->
        <div class="glass-card rounded-xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900">{{ $typeCounts['damaged'] + $typeCounts['missing'] }}</p>
                    <p class="text-xs text-gray-500">Damaged / Missing</p>
                </div>
            </div>
        </div>
    </div>

    @if($penalties->count() > 0)
        <!-- Active Penalty Warning -->
        @if($activePenalties->count() > 0)
            <div class="mb-6 p-4 bg-gradient-to-r from-red-500 to-orange-500 rounded-xl text-white shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 opacity-80" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-lg">You have {{ $activePenalties->count() }} active {{ $activePenalties->count() === 1 ? 'penalty' : 'penalties' }}</p>
                        <p class="text-sm opacity-90">Total of {{ $activePenalties->sum('penalty_points') }} penalty points deducted from your account.</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Penalty List -->
        <div class="space-y-4">
            @foreach($penalties as $penalty)
                <div class="glass-card rounded-xl overflow-hidden transition-all duration-200 hover:shadow-md
                    {{ in_array($penalty->status, ['active', 'pending']) ? 'border-l-4' : '' }}
                    {{ $penalty->status === 'pending' ? 'border-l-yellow-400' : '' }}
                    {{ $penalty->type === 'late_return' && $penalty->status === 'active' ? 'border-l-orange-500' : '' }}
                    {{ $penalty->type === 'damaged' && $penalty->status === 'active' ? 'border-l-yellow-500' : '' }}
                    {{ $penalty->type === 'missing' && $penalty->status === 'active' ? 'border-l-red-500' : '' }}
                    {{ $penalty->status === 'rejected' ? 'opacity-60' : '' }}
                ">
                    <div class="p-5">
                        <div class="flex items-start gap-4">
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

                            <!-- Penalty Details -->
                            <div class="flex-grow min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-semibold text-lg
                                        {{ $penalty->type === 'late_return' ? 'text-orange-800' : '' }}
                                        {{ $penalty->type === 'damaged' ? 'text-yellow-800' : '' }}
                                        {{ $penalty->type === 'missing' ? 'text-red-800' : '' }}
                                    ">{{ $penalty->type_label }}</span>

                                    <!-- Status Badge -->
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                        {{ $penalty->status === 'pending' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ $penalty->status === 'active' ? 'bg-red-100 text-red-700' : '' }}
                                        {{ $penalty->status === 'rejected' ? 'bg-gray-100 text-gray-600' : '' }}
                                        {{ $penalty->status === 'resolved' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $penalty->status === 'waived' ? 'bg-gray-100 text-gray-600' : '' }}
                                    ">
                                        <span class="w-1.5 h-1.5 rounded-full mr-1
                                            {{ $penalty->status === 'pending' ? 'bg-yellow-400' : '' }}
                                            {{ $penalty->status === 'active' ? 'bg-red-500' : '' }}
                                            {{ $penalty->status === 'rejected' ? 'bg-gray-400' : '' }}
                                            {{ $penalty->status === 'resolved' ? 'bg-green-500' : '' }}
                                            {{ $penalty->status === 'waived' ? 'bg-gray-400' : '' }}
                                        "></span>
                                        {{ $penalty->status === 'pending' ? 'Pending Review' : ucfirst($penalty->status) }}
                                    </span>
                                </div>

                                <!-- Item Info -->
                                @if($penalty->borrowRequest && $penalty->borrowRequest->item)
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                        <a href="{{ route('items.show', $penalty->borrowRequest->item) }}" class="text-sm text-purple-600 hover:text-purple-800 hover:underline font-medium">
                                            {{ $penalty->borrowRequest->item->title }}
                                        </a>
                                        <span class="text-xs text-gray-400">• Request #{{ $penalty->borrow_request_id }}</span>
                                    </div>
                                @endif

                                <!-- Reason -->
                                <p class="text-sm text-gray-600">{{ $penalty->reason }}</p>

                                <!-- Meta -->
                                <div class="flex items-center gap-4 mt-2 text-xs text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        Reported by {{ $penalty->reportedBy->name }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $penalty->created_at->format('M d, Y \a\t g:i A') }}
                                    </span>
                                    <span>{{ $penalty->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            <!-- Points Badge -->
                            <div class="flex-shrink-0 text-right">
                                <div class="inline-flex items-center px-4 py-2 rounded-xl text-lg font-bold
                                    {{ $penalty->type === 'late_return' ? 'bg-orange-100 text-orange-700' : '' }}
                                    {{ $penalty->type === 'damaged' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $penalty->type === 'missing' ? 'bg-red-100 text-red-700' : '' }}
                                ">
                                    -{{ $penalty->penalty_points }} pts
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="glass-card rounded-2xl p-12 text-center">
            <div class="w-20 h-20 mx-auto rounded-2xl bg-green-100 flex items-center justify-center mb-4">
                <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 mb-2">No penalties — you're all clear! 🎉</h3>
            <p class="text-gray-500 max-w-md mx-auto">
                You have a clean record. Keep returning items on time and in good condition to maintain your standing.
            </p>
        </div>
    @endif
</div>
@endsection

