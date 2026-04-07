@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Profile Header -->
    <div class="glass-card rounded-2xl p-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
            <!-- Avatar -->
            <div class="flex-shrink-0">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">
                @else
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center text-white text-3xl font-bold shadow-lg">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
            </div>

            <!-- User Info -->
            <div class="flex-grow">
                <h1 class="text-3xl font-bold text-gray-900">{{ $user->name }}</h1>
                <!-- Rating Display -->
                @if($user->average_rating > 0)
                    <div class="flex items-center mt-2">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-5 h-5 {{ $i <= $user->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                        <span class="ml-2 text-gray-600">{{ $user->average_rating }} ({{ $user->ratingsReceived->count() }} {{ $user->ratingsReceived->count() === 1 ? 'rating' : 'ratings' }})</span>
                    </div>
                @else
                    <p class="text-gray-500 text-sm mt-2">No ratings yet</p>
                @endif
                @if($user->location)
                    <p class="text-gray-600 mt-1 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        {{ $user->location }}
                    </p>
                @endif
                @if($user->bio)
                    <p class="mt-3 text-gray-700">{{ $user->bio }}</p>
                @endif
            </div>

            <!-- Edit Button -->
            <div class="flex-shrink-0">
                <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Profile
                </a>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-8 pt-6 border-t border-gray-200">
            <div class="text-center">
                <div class="text-3xl font-bold text-purple-600">{{ $user->points_balance ?? 0 }}</div>
                <div class="text-sm text-gray-600 mt-1">Points Balance</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-purple-600">{{ $user->items()->count() }}</div>
                <div class="text-sm text-gray-600 mt-1">Items Posted</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-pink-600">{{ $borrowHistory->count() }}</div>
                <div class="text-sm text-gray-600 mt-1">Borrow Requests</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold {{ $penalties->count() > 0 ? 'text-red-600' : 'text-green-600' }}">{{ $penalties->count() }}</div>
                <div class="text-sm text-gray-600 mt-1">Penalties</div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="glass-card rounded-2xl overflow-hidden">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px" id="profile-tabs">
                <button onclick="showTab('borrow-history')" id="tab-borrow-history" class="tab-button active px-6 py-4 text-sm font-medium border-b-2 border-purple-600 text-purple-600 focus:outline-none">
                    Borrow History
                </button>
                <button onclick="showTab('liked-items')" id="tab-liked-items" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-600 hover:text-gray-800 hover:border-gray-300 focus:outline-none">
                    Liked Items ({{ $user->likedItems->count() }})
                </button>
                <button onclick="showTab('ratings')" id="tab-ratings" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-600 hover:text-gray-800 hover:border-gray-300 focus:outline-none">
                    Ratings ({{ $user->ratingsReceived->count() }})
                </button>
                <button onclick="showTab('penalties')" id="tab-penalties" class="tab-button px-6 py-4 text-sm font-medium border-b-2 border-transparent text-gray-600 hover:text-gray-800 hover:border-gray-300 focus:outline-none">
                    Penalties
                    @if($penalties->count() > 0)
                        <span class="ml-1 px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700">{{ $penalties->count() }}</span>
                    @endif
                </button>
            </nav>
        </div>

        <!-- Borrow History Tab -->
        <div id="content-borrow-history" class="tab-content p-6">
            @if($borrowHistory->count() > 0)
                <div class="space-y-4">
                    @foreach($borrowHistory as $request)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex-grow">
                                <h3 class="font-semibold text-gray-900">{{ $request->item->title }}</h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ \Carbon\Carbon::parse($request->start_date)->format('M d, Y') }} - {{ \Carbon\Carbon::parse($request->end_date)->format('M d, Y') }}
                                </p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                    @if($request->status == 'approved') bg-green-100 text-green-800
                                    @elseif($request->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($request->status == 'rejected') bg-red-100 text-red-800
                                    @elseif($request->status == 'borrowed') bg-blue-100 text-blue-800
                                    @elseif($request->status == 'returned') bg-gray-100 text-gray-800
                                    @elseif($request->status == 'overdue') bg-orange-100 text-orange-800
                                    @elseif($request->status == 'missing') bg-red-200 text-red-900
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst($request->status) }}
                                </span>
                                <p class="text-sm text-gray-600 mt-1">{{ $request->total_points ?? 0 }} pts</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-600">No borrow history yet</p>
                </div>
            @endif
        </div>

        <!-- Liked Items Tab -->
        <div id="content-liked-items" class="tab-content hidden p-6">
            @if($user->likedItems->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($user->likedItems as $item)
                        <div class="glass-card rounded-xl overflow-hidden hover:shadow-lg transition duration-300">
                            <div class="h-32 bg-gray-200 flex items-center justify-center">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 truncate">{{ $item->title }}</h3>
                                <p class="text-sm text-gray-600 mt-1">{{ $item->points_per_day }} pts/day</p>
                                <div class="mt-3 flex justify-between items-center">
                                    <a href="{{ route('items.show', $item) }}" class="text-purple-600 hover:text-purple-800 text-sm font-medium">View</a>
                                    <form action="{{ route('items.unlike', $item) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">Remove</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <p class="mt-2 text-sm text-gray-600">No liked items yet</p>
                    <p class="text-sm text-gray-500">Browse items and add them to your favorites!</p>
                </div>
            @endif
        </div>

        <!-- Ratings Tab -->
        <div id="content-ratings" class="tab-content hidden p-6">
            @if($user->ratingsReceived->count() > 0)
                <div class="space-y-4">
                    @foreach($user->ratingsReceived()->with(['rater', 'borrowRequest.item'])->latest()->get() as $rating)
                        <div class="flex items-start justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-purple-500 flex items-center justify-center text-white font-bold flex-shrink-0">
                                    {{ strtoupper(substr($rating->rater->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-gray-900">{{ $rating->rater->name }}</span>
                                        <span class="text-sm text-gray-500">{{ $rating->created_at->diffForHumans() }}</span>
                                    </div>
                                    @if($rating->borrowRequest && $rating->borrowRequest->item)
                                        <p class="text-sm text-gray-600 mt-1">
                                            For: <a href="{{ route('items.show', $rating->borrowRequest->item) }}" class="text-purple-600 hover:underline">{{ $rating->borrowRequest->item->title }}</a>
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= $rating->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    <p class="mt-2 text-sm text-gray-600">No ratings received yet</p>
                    <p class="text-sm text-gray-500">Complete transactions to get rated by other users!</p>
                </div>
            @endif
        </div>

        <!-- Penalties Tab -->
        <div id="content-penalties" class="tab-content hidden p-6">
            @if($penalties->count() > 0)
                {{-- Penalty Summary --}}
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-red-800">Total penalties received</p>
                                <p class="text-2xl font-bold text-red-700">{{ $penalties->sum('penalty_points') }} pts</p>
                            </div>
                        </div>
                        <div class="text-right text-sm text-red-600">
                            <p>{{ $penalties->where('type', 'late_return')->count() }} late</p>
                            <p>{{ $penalties->where('type', 'damaged')->count() }} damaged</p>
                            <p>{{ $penalties->where('type', 'missing')->count() }} missing</p>
                        </div>
                    </div>
                </div>

                {{-- Penalty List --}}
                <div class="space-y-4">
                    @foreach($penalties as $penalty)
                        <div class="flex items-start gap-4 p-4 rounded-lg border
                            {{ $penalty->type === 'late_return' ? 'bg-orange-50 border-orange-200' : '' }}
                            {{ $penalty->type === 'damaged' ? 'bg-yellow-50 border-yellow-200' : '' }}
                            {{ $penalty->type === 'missing' ? 'bg-red-50 border-red-200' : '' }}
                        ">
                            {{-- Icon --}}
                            <div class="flex-shrink-0 w-10 h-10 rounded-lg flex items-center justify-center
                                {{ $penalty->type === 'late_return' ? 'bg-orange-100' : '' }}
                                {{ $penalty->type === 'damaged' ? 'bg-yellow-100' : '' }}
                                {{ $penalty->type === 'missing' ? 'bg-red-100' : '' }}
                            ">
                                @if($penalty->type === 'late_return')
                                    <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @elseif($penalty->type === 'damaged')
                                    <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                @endif
                            </div>

                            {{-- Details --}}
                            <div class="flex-grow">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-sm font-semibold
                                        {{ $penalty->type === 'late_return' ? 'text-orange-800' : '' }}
                                        {{ $penalty->type === 'damaged' ? 'text-yellow-800' : '' }}
                                        {{ $penalty->type === 'missing' ? 'text-red-800' : '' }}
                                    ">{{ $penalty->type_label }}</span>
                                    <span class="text-xs text-gray-500">{{ $penalty->created_at->diffForHumans() }}</span>
                                </div>
                                @if($penalty->borrowRequest && $penalty->borrowRequest->item)
                                    <p class="text-sm text-gray-700">
                                        Item: <a href="{{ route('items.show', $penalty->borrowRequest->item) }}" class="text-purple-600 hover:underline font-medium">{{ $penalty->borrowRequest->item->title }}</a>
                                    </p>
                                @endif
                                <p class="text-sm text-gray-600 mt-1">{{ $penalty->reason }}</p>
                                <p class="text-xs text-gray-400 mt-1">Reported by: {{ $penalty->reportedBy->name }}</p>
                            </div>

                            {{-- Points --}}
                            <div class="flex-shrink-0 text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-sm font-bold text-red-700 bg-red-100">
                                    -{{ $penalty->penalty_points }} pts
                                </span>
                                <p class="text-xs mt-1
                                    {{ $penalty->status === 'active' ? 'text-red-500' : '' }}
                                    {{ $penalty->status === 'resolved' ? 'text-green-500' : '' }}
                                    {{ $penalty->status === 'waived' ? 'text-gray-500' : '' }}
                                ">{{ ucfirst($penalty->status) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="mt-2 text-sm font-medium text-green-600">No penalties — great job!</p>
                    <p class="text-sm text-gray-500">Keep returning items on time and in good condition.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active state from all tab buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-purple-600', 'text-purple-600');
        button.classList.add('border-transparent', 'text-gray-600');
    });
    
    // Show selected tab content
    document.getElementById('content-' + tabName).classList.remove('hidden');
    
    // Add active state to selected tab button
    const activeButton = document.getElementById('tab-' + tabName);
    activeButton.classList.add('active', 'border-purple-600', 'text-purple-600');
    activeButton.classList.remove('border-transparent', 'text-gray-600');
}
</script>
@endsection

