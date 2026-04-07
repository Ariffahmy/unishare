@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Rate Your Experience</h1>
        <p class="mt-2 text-sm text-gray-600">Share your feedback about this transaction.</p>
    </div>

    <div class="glass-card rounded-2xl p-8">
        <!-- Transaction Summary -->
        <div class="mb-8 p-4 bg-gray-50 rounded-lg">
            <h3 class="font-semibold text-gray-900 mb-2">Transaction Details</h3>
            <p class="text-sm text-gray-600">
                <strong>Item:</strong> {{ $borrowRequest->item->title }}<br>
                <strong>Dates:</strong> {{ $borrowRequest->start_date->format('M d, Y') }} - {{ $borrowRequest->end_date->format('M d, Y') }}<br>
                <strong>Total Points:</strong> {{ $borrowRequest->total_points }} pts
            </p>
        </div>

        <form method="POST" action="{{ route('ratings.store', $borrowRequest) }}" class="space-y-8">
            @csrf

            <!-- User Rating -->
            <div>
                <label class="block text-lg font-medium text-gray-900 mb-4">
                    How was your experience with {{ $ratedUser->name }}?
                </label>
                <div class="flex items-center justify-center space-x-2" id="user-rating">
                    @for($i = 1; $i <= 5; $i++)
                        <button type="button" onclick="setRating('rating', {{ $i }})" 
                            class="star-btn p-2 text-4xl text-gray-300 hover:text-yellow-400 transition-colors focus:outline-none"
                            data-rating="{{ $i }}">
                            ★
                        </button>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="rating" value="" required>
                @error('rating')
                    <p class="mt-1 text-sm text-red-600 text-center">{{ $message }}</p>
                @enderror
            </div>

            @if($borrowRequest->borrower_id == auth()->id())
                <!-- Item Review (Only for borrowers) -->
                <div class="border-t border-gray-200 pt-8">
                    <label class="block text-lg font-medium text-gray-900 mb-4">
                        How was the item? (Optional)
                    </label>
                    <div class="flex items-center justify-center space-x-2 mb-4" id="item-rating">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" onclick="setRating('review_rating', {{ $i }})" 
                                class="star-btn-item p-2 text-3xl text-gray-300 hover:text-yellow-400 transition-colors focus:outline-none"
                                data-rating="{{ $i }}">
                                ★
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" name="review_rating" id="review_rating" value="">

                    <div class="mt-4">
                        <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Leave a comment (Optional)</label>
                        <textarea name="comment" id="comment" rows="4" maxlength="1000"
                            class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500 sm:text-sm py-3 px-4"
                            placeholder="Share your experience with this item...">{{ old('comment') }}</textarea>
                    </div>
                </div>
            @endif

            <!-- Submit -->
            <div class="pt-6 flex items-center justify-end space-x-4 border-t border-gray-200">
                <a href="{{ route('borrow-requests.index') }}" class="px-5 py-2.5 rounded-lg border border-gray-300 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                    Skip
                </a>
                <button type="submit" class="inline-flex justify-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 shadow-sm transition-all duration-200">
                    Submit Rating
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function setRating(inputId, value) {
    document.getElementById(inputId).value = value;
    
    // Update star display
    const isUserRating = inputId === 'rating';
    const buttons = document.querySelectorAll(isUserRating ? '.star-btn' : '.star-btn-item');
    
    buttons.forEach((btn, index) => {
        if (index < value) {
            btn.classList.remove('text-gray-300');
            btn.classList.add('text-yellow-400');
        } else {
            btn.classList.remove('text-yellow-400');
            btn.classList.add('text-gray-300');
        }
    });
}
</script>
@endsection

