@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto">
    <!-- Back Button -->
    <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-purple-600 mb-6 transition-colors">
        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Back to Dashboard
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Item Details (Left - 2cols) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Photo Gallery -->
            <div class="glass-card rounded-2xl overflow-hidden p-1">
                @if($item->photos->count() > 0)
                    <!-- Main Photo -->
                    <div class="rounded-xl overflow-hidden bg-gray-100 relative h-96 w-full" id="main-photo-container">
                        <img src="{{ asset('storage/' . $item->photos->first()->photo_path) }}" 
                             alt="{{ $item->title }}" 
                             id="main-photo"
                             class="w-full h-full object-cover">
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 bg-white/90 backdrop-blur text-sm font-bold text-gray-800 rounded-full shadow-sm">{{ $item->category ?? 'Item' }}</span>
                        </div>
                    </div>
                    
                    <!-- Photo Thumbnails -->
                    @if($item->photos->count() > 1)
                        <div class="flex gap-2 mt-2 overflow-x-auto py-2">
                            @foreach($item->photos as $index => $photo)
                                <button onclick="changeMainPhoto('{{ asset('storage/' . $photo->photo_path) }}')" 
                                        class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden border-2 transition-all hover:border-purple-500 {{ $index === 0 ? 'border-purple-500' : 'border-transparent' }}">
                                    <img src="{{ asset('storage/' . $photo->photo_path) }}" 
                                         alt="Photo {{ $index + 1 }}" 
                                         class="w-full h-full object-cover">
                                </button>
                            @endforeach
                        </div>
                    @endif
                @else
                    <!-- Placeholder when no photos -->
                    <div class="rounded-xl overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200 relative h-96 w-full flex items-center justify-center">
                        <span class="text-gray-400">
                             <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </span>
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 bg-white/90 backdrop-blur text-sm font-bold text-gray-800 rounded-full shadow-sm">{{ $item->category ?? 'Item' }}</span>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Item Info Card -->
            <div class="glass-card rounded-2xl p-8">
                <div class="flex justify-between items-start mb-4">
                    <h1 class="text-3xl font-extrabold text-gray-900">{{ $item->title }}</h1>
                    <div class="text-right">
                         <span class="block text-2xl font-bold text-purple-600">{{ $item->points_per_day }} pts</span>
                         <span class="text-sm text-gray-500">per day</span>
                    </div>
                </div>

                <div class="prose max-w-none text-gray-600 mb-8">
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">Description</h3>
                    <p class="whitespace-pre-line">{{ $item->description }}</p>
                </div>

                <div class="grid grid-cols-2 gap-4 border-t border-gray-200 pt-6">
                    <div>
                        <span class="block text-sm text-gray-500">Condition</span>
                        <span class="block font-medium text-gray-800">{{ $item->condition }}</span>
                    </div>
                    <div>
                        <span class="block text-sm text-gray-500">Owner</span>
                        <span class="block font-medium text-gray-800">{{ $item->owner->name }}</span>
                        @if($item->owner->average_rating > 0)
                            <div class="flex items-center mt-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $item->owner->average_rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                                <span class="ml-1 text-sm text-gray-600">({{ $item->owner->average_rating }})</span>
                            </div>
                        @endif
                    </div>
                    <div>
                        <span class="block text-sm text-gray-500">Max Duration</span>
                        <span class="block font-medium text-gray-800">{{ $item->max_days }} Days</span>
                    </div>
                    <div>
                        <span class="block text-sm text-gray-500">Status</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $item->is_active ? 'Available' : 'Unavailable' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Reviews Section -->
            <div class="glass-card rounded-2xl p-8">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Reviews</h2>
                
                @php
                    $reviews = $item->reviews()->with('reviewer')->latest()->get();
                @endphp
                
                @if($reviews->count() > 0)
                    <div class="space-y-6">
                        @foreach($reviews as $review)
                            <div class="border-b border-gray-100 pb-6 last:border-0 last:pb-0">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-purple-500 flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($review->reviewer->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $review->reviewer->name }}</h4>
                                            <p class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-5 h-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        @endfor
                                    </div>
                                </div>
                                @if($review->comment)
                                    <p class="mt-3 text-gray-600">{{ $review->comment }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p class="mt-2 text-gray-500">No reviews yet. Be the first to borrow and review!</p>
                    </div>
                @endif

                @if($reviews->count() > 0)
                    <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-sm text-gray-600">
                            <strong>Average Rating:</strong> 
                            {{ number_format($item->average_rating, 1) }} / 5
                            ({{ $reviews->count() }} {{ $reviews->count() === 1 ? 'review' : 'reviews' }})
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Borrow Action (Right - 1col) -->
        <div class="lg:col-span-1 space-y-6">
            <div class="glass-card rounded-2xl p-6 sticky top-24">
                <h2 class="text-xl font-bold text-gray-900 mb-6">Request to Borrow</h2>

                @if(Auth::id() === $item->owner_id)
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    This is your item. You cannot borrow it.
                                </p>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('items.edit', $item) }}" class="w-full text-center block bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-3 px-4 rounded-xl transition duration-300">
                        Edit Item
                    </a>
                @else
                    <form action="{{ route('borrow-requests.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="item_id" value="{{ $item->id }}">
                        <input type="hidden" name="lender_id" value="{{ $item->owner_id }}">
                        <input type="hidden" name="borrower_id" value="{{ Auth::id() }}">
                        <input type="hidden" name="points_per_day" value="{{ $item->points_per_day }}">

                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" name="start_date" id="start_date" required min="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-2 rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500 shadow-sm">
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" name="end_date" id="end_date" required min="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-2 rounded-lg border-gray-300 focus:border-purple-500 focus:ring-purple-500 shadow-sm">
                        </div>

                        <div class="bg-purple-50 rounded-lg p-4 mt-6">
                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                <span>Daily Rate</span>
                                <span>{{ $item->points_per_day }} pts</span>
                            </div>
                            <div class="flex justify-between text-sm text-gray-600 mb-2">
                                <span>Duration</span>
                                <span id="duration-display">-- days</span>
                            </div>
                            <div class="border-t border-purple-100 pt-2 flex justify-between font-bold text-purple-900">
                                <span>Estimated Total</span>
                                <span id="total-cost">-- pts</span>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-purple-600 hover:from-purple-700 hover:to-purple-700 text-white font-bold py-3 px-4 rounded-xl shadow-lg transform transition hover:-translate-y-0.5">
                            Send Request
                        </button>
                    </form>

                    <!-- Contact Owner Button -->
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <form action="{{ route('messages.start') }}" method="POST">
                            @csrf
                            <input type="hidden" name="recipient_id" value="{{ $item->owner_id }}">
                            <input type="hidden" name="message" value="Hi! I'm interested in borrowing your item: {{ $item->title }}">
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-colors">
                                <svg class="w-5 h-5 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                Message Owner
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    // Photo gallery script
    function changeMainPhoto(src) {
        document.getElementById('main-photo').src = src;
        // Update active thumbnail border
        document.querySelectorAll('#main-photo-container ~ div button').forEach(btn => {
            btn.classList.remove('border-purple-500');
            btn.classList.add('border-transparent');
        });
        event.target.closest('button').classList.remove('border-transparent');
        event.target.closest('button').classList.add('border-purple-500');
    }

    // Cost calculator script
    const startInput = document.getElementById('start_date');
    const endInput = document.getElementById('end_date');
    const durationDisplay = document.getElementById('duration-display');
    const totalCostDisplay = document.getElementById('total-cost');
    const pointsPerDay = {{ $item->points_per_day }};

    function calculate() {
        const start = new Date(startInput.value);
        const end = new Date(endInput.value);
        
        if (start && end && !isNaN(start) && !isNaN(end) && end >= start) {
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // Inclusive
            const total = diffDays * pointsPerDay;
            
            durationDisplay.textContent = diffDays + (diffDays === 1 ? ' day' : ' days');
            totalCostDisplay.textContent = total + ' pts';
        } else {
            durationDisplay.textContent = '-- days';
            totalCostDisplay.textContent = '-- pts';
        }
    }

    if (startInput && endInput) {
        startInput.addEventListener('change', calculate);
        endInput.addEventListener('change', calculate);
    }
</script>
@endsection

