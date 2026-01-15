<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BorrowRequest;
use App\Services\PointService;
use Illuminate\Support\Facades\DB;


class BorrowRequestController extends Controller
{
    public function approve(BorrowRequest $borrowRequest, PointService $pointService)
{

        if (!BorrowRequest::canTransition($borrowRequest->status, BorrowRequest::STATUS_APPROVED)) {
            return back()->with('error', 'This request cannot be approved.');
        }


    DB::transaction(function () use ($borrowRequest, $pointService) {

            // 1) Calculate total points
            $totalPoints = $borrowRequest->calculateTotalPoints();

            // 2) Deduct points from borrower
            $pointService->spend(
                $borrowRequest->borrower_id,
                $totalPoints,
                'borrow_spend',
                'Borrow item ID ' . $borrowRequest->item_id,
                $borrowRequest->id
            );

            // 3) Lender earns points
            $pointService->add(
                $borrowRequest->lender_id,
                $totalPoints,
                'lend_earn',
                'Lend item ID ' . $borrowRequest->item_id,
                $borrowRequest->id
            );

            // 3) Update borrow request
            $borrowRequest->update([
                'total_points' => $totalPoints,
                'status' => 'approved',
            ]);
    });

        return back()->with('success', 'Borrow request approved.');
    }

    public function reject(BorrowRequest $borrowRequest)
        {
            if (!BorrowRequest::canTransition($borrowRequest->status, BorrowRequest::STATUS_REJECTED)) {
                    return back()->with('error', 'Only pending requests can be rejected.');
                }


                    // 2) Update status to rejected
            $borrowRequest->update([
                'status' => BorrowRequest::STATUS_REJECTED,
            ]);

            return back()->with('success', 'Borrow request rejected.');
        }


    public function cancel(BorrowRequest $borrowRequest, PointService $pointService)
        {
            if (!BorrowRequest::canTransition($borrowRequest->status, BorrowRequest::STATUS_CANCELLED)) {
                return back()->with('error', 'Only approved requests can be cancelled.');
            }


            DB::transaction(function () use ($borrowRequest, $pointService) {

                $totalPoints = (int) $borrowRequest->total_points;

                // 1) Refund borrower (give points back)
                $pointService->add(
                    $borrowRequest->borrower_id,
                    $totalPoints,
                    'borrow_refund',
                    'Refund for borrow request ID ' . $borrowRequest->id,
                    $borrowRequest->id
                );

                // 2) Reverse lender earning (take points back)
                $pointService->spend(
                    $borrowRequest->lender_id,
                    $totalPoints,
                    'lend_reversal',
                    'Reversal for borrow request ID ' . $borrowRequest->id,
                    $borrowRequest->id
                );

                // 3) Update request status
                $borrowRequest->update(['status' => BorrowRequest::STATUS_CANCELLED]);

            });

            return back()->with('success', 'Borrow request cancelled and points refunded.');
        }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_id' => 'required|integer|exists:items,id',
            'borrower_id' => 'required|integer|exists:users,id',
            'lender_id' => 'required|integer|exists:users,id|different:borrower_id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'points_per_day' => 'required|integer|min:0',
        ]);

            // 1) Availability check (block double booking)
            if (\App\Models\BorrowRequest::hasOverlapForItem(
                (int) $validated['item_id'],
                $validated['start_date'],
                $validated['end_date']
            )) {
                return back()->with('error', 'Item is not available for the selected dates.');
            }

            // 2) Create pending request (total_points can be 0/null for now)
            \App\Models\BorrowRequest::create([
                'item_id' => $validated['item_id'],
                'borrower_id' => $validated['borrower_id'],
                'lender_id' => $validated['lender_id'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'points_per_day' => $validated['points_per_day'],
                'total_points' => 0,
                'status' => 'pending',
            ]);

            return back()->with('success', 'Borrow request submitted.');
    }

    public function markBorrowed(BorrowRequest $borrowRequest)
        {
            if (!BorrowRequest::canTransition($borrowRequest->status, BorrowRequest::STATUS_BORROWED)) {
                return back()->with('error', 'Only approved requests can be marked as borrowed.');
            }



            $borrowRequest->update(['status' => BorrowRequest::STATUS_BORROWED]);


            return back()->with('success', 'Marked as borrowed.');
        }

    public function markReturned(BorrowRequest $borrowRequest)
        {
            if (!BorrowRequest::canTransition($borrowRequest->status, BorrowRequest::STATUS_RETURNED)) {
                return back()->with('error', 'Only borrowed requests can be marked as returned.');
            }


        $borrowRequest->update(['status' => BorrowRequest::STATUS_RETURNED]);


        return back()->with('success', 'Marked as returned.');
    }




}
