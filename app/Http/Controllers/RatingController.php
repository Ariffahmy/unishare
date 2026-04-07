<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\Review;
use App\Models\BorrowRequest;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    /**
     * Show the rating form for a borrow request
     */
    public function create(BorrowRequest $borrowRequest)
    {
        $userId = Auth::id();
        
        // Check if user can rate
        if (!$borrowRequest->canBeRatedBy($userId)) {
            return back()->with('error', 'You cannot rate this transaction.');
        }

        // Determine who the user is rating
        $ratedUser = $borrowRequest->borrower_id == $userId 
            ? $borrowRequest->lender 
            : $borrowRequest->borrower;

        return view('ratings.create', compact('borrowRequest', 'ratedUser'));
    }

    /**
     * Store a new rating and optional review
     */
    public function store(Request $request, BorrowRequest $borrowRequest)
    {
        $userId = Auth::id();
        
        // Check if user can rate
        if (!$borrowRequest->canBeRatedBy($userId)) {
            return back()->with('error', 'You cannot rate this transaction.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review_rating' => 'nullable|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Determine who is being rated
        $ratedUserId = $borrowRequest->borrower_id == $userId 
            ? $borrowRequest->lender_id 
            : $borrowRequest->borrower_id;

        // Create the user rating
        Rating::create([
            'rater_id' => $userId,
            'rated_user_id' => $ratedUserId,
            'borrow_request_id' => $borrowRequest->id,
            'rating' => $validated['rating'],
        ]);

        // Create item review if provided (only borrowers can review items)
        if ($borrowRequest->borrower_id == $userId && ($validated['review_rating'] || $validated['comment'])) {
            Review::create([
                'reviewer_id' => $userId,
                'item_id' => $borrowRequest->item_id,
                'borrow_request_id' => $borrowRequest->id,
                'rating' => $validated['review_rating'] ?? $validated['rating'],
                'comment' => $validated['comment'],
            ]);
        }

        return redirect()->route('borrow-requests.index')->with('success', 'Thank you for your feedback!');
    }

    /**
     * Show all reviews for an item
     */
    public function itemReviews($itemId)
    {
        $reviews = Review::with('reviewer')
            ->where('item_id', $itemId)
            ->latest()
            ->get();

        return view('ratings.item-reviews', compact('reviews'));
    }
}
