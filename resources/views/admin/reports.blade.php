@extends('layouts.admin')

@section('title', 'Reports & Analytics')

@section('content')
<div class="space-y-8">
    <!-- Top Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Lenders</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $topLenders->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Borrowers</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $topBorrowers->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Item Categories</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $categoryDistribution->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="stat-card">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Popular Items</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">{{ $popularItems->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Two Column Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Top Lenders -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/>
                    </svg>
                    Top Lenders
                </h3>
                <span class="text-xs text-gray-500">By items listed</span>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($topLenders as $index => $lender)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                                {{ $index === 0 ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $index === 1 ? 'bg-gray-200 text-gray-600' : '' }}
                                {{ $index === 2 ? 'bg-orange-100 text-orange-700' : '' }}
                                {{ $index > 2 ? 'bg-gray-100 text-gray-500' : '' }}
                            ">
                                {{ $index + 1 }}
                            </div>
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($lender->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $lender->name }}</p>
                                <p class="text-sm text-gray-500">{{ $lender->email }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-sm font-semibold bg-green-100 text-green-800">
                                {{ $lender->items_count }} items
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">No lenders yet</div>
                @endforelse
            </div>
        </div>
        
        <!-- Top Borrowers -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-semibold text-gray-900 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    Top Borrowers
                </h3>
                <span class="text-xs text-gray-500">By borrow requests</span>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($topBorrowers as $index => $borrower)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition-colors">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                                {{ $index === 0 ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $index === 1 ? 'bg-gray-200 text-gray-600' : '' }}
                                {{ $index === 2 ? 'bg-orange-100 text-orange-700' : '' }}
                                {{ $index > 2 ? 'bg-gray-100 text-gray-500' : '' }}
                            ">
                                {{ $index + 1 }}
                            </div>
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center text-white font-bold">
                                {{ strtoupper(substr($borrower->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $borrower->name }}</p>
                                <p class="text-sm text-gray-500">{{ $borrower->email }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-sm font-semibold bg-blue-100 text-blue-800">
                                {{ $borrower->borrow_requests_count }} requests
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">No borrowers yet</div>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Category Distribution -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/>
                </svg>
                Category Distribution
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                @php
                    $colors = [
                        'bg-red-100 text-red-800 border-red-200',
                        'bg-orange-100 text-orange-800 border-orange-200',
                        'bg-amber-100 text-amber-800 border-amber-200',
                        'bg-yellow-100 text-yellow-800 border-yellow-200',
                        'bg-lime-100 text-lime-800 border-lime-200',
                        'bg-green-100 text-green-800 border-green-200',
                        'bg-emerald-100 text-emerald-800 border-emerald-200',
                        'bg-teal-100 text-teal-800 border-teal-200',
                        'bg-cyan-100 text-cyan-800 border-cyan-200',
                        'bg-sky-100 text-sky-800 border-sky-200',
                        'bg-blue-100 text-blue-800 border-blue-200',
                        'bg-purple-100 text-purple-800 border-purple-200',
                        'bg-violet-100 text-violet-800 border-violet-200',
                        'bg-purple-100 text-purple-800 border-purple-200',
                        'bg-fuchsia-100 text-fuchsia-800 border-fuchsia-200',
                        'bg-pink-100 text-pink-800 border-pink-200',
                    ];
                    $totalItems = $categoryDistribution->sum('count');
                @endphp
                @forelse($categoryDistribution as $index => $category)
                    <div class="p-4 rounded-xl border-2 {{ $colors[$index % count($colors)] }} hover:shadow-md transition-shadow">
                        <p class="font-semibold text-lg">{{ $category->count }}</p>
                        <p class="text-sm font-medium">{{ ucfirst($category->category) }}</p>
                        <p class="text-xs mt-1">{{ $totalItems > 0 ? round(($category->count / $totalItems) * 100, 1) : 0 }}%</p>
                    </div>
                @empty
                    <p class="col-span-full text-center text-gray-500 py-4">No items categorized yet</p>
                @endforelse
            </div>
        </div>
    </div>
    
    <!-- Most Popular Items -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                Most Popular Items
            </h3>
            <span class="text-xs text-gray-500">By borrow requests</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Item</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requests</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($popularItems as $index => $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold
                                    {{ $index === 0 ? 'bg-yellow-100 text-yellow-700' : '' }}
                                    {{ $index === 1 ? 'bg-gray-200 text-gray-600' : '' }}
                                    {{ $index === 2 ? 'bg-orange-100 text-orange-700' : '' }}
                                    {{ $index > 2 ? 'bg-gray-100 text-gray-500' : '' }}
                                ">
                                    {{ $index + 1 }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($item->photos->count() > 0)
                                        <img src="{{ asset('storage/' . $item->photos->first()->photo_path) }}" 
                                             alt="{{ $item->title }}" 
                                             class="w-12 h-12 rounded-lg object-cover mr-3">
                                    @else
                                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br from-gray-200 to-gray-300 mr-3 flex items-center justify-center">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ Str::limit($item->title, 30) }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->points_per_day }} pts/day</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $item->owner->name ?? 'Unknown' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ ucfirst($item->category) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-bold bg-amber-100 text-amber-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    {{ $item->borrow_requests_count }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">No items with borrow requests yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <!-- Monthly Transactions Chart (Simple Bar -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-900 flex items-center">
                <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Monthly Transactions (Last 6 Months)
            </h3>
        </div>
        <div class="p-6">
            @php
                $maxCount = $monthlyTransactions->max('count') ?: 1;
            @endphp
            <div class="flex items-end justify-between space-x-4 h-48">
                @forelse($monthlyTransactions as $transaction)
                    <div class="flex-1 flex flex-col items-center">
                        <div class="w-full bg-gradient-to-t from-purple-500 to-purple-400 rounded-t-lg transition-all duration-300 hover:from-purple-600 hover:to-purple-500"
                             style="height: {{ ($transaction->count / $maxCount) * 100 }}%">
                        </div>
                        <span class="text-sm font-medium text-gray-700 mt-2">{{ $transaction->count }}</span>
                        <span class="text-xs text-gray-500 mt-1">{{ \Carbon\Carbon::parse($transaction->month . '-01')->format('M') }}</span>
                    </div>
                @empty
                    <p class="w-full text-center text-gray-500">No transaction data available</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

