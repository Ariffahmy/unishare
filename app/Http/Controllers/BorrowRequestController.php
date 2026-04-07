<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BorrowRequest;
use App\Models\Penalty;
use App\Services\PointService;
use Illuminate\Support\Facades\DB;


class BorrowRequestController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $receivedRequests = BorrowRequest::with(['item', 'borrower'])
            ->where('lender_id', $userId)
            ->latest()
            ->get();

        $sentRequests = BorrowRequest::with(['item', 'lender'])
            ->where('borrower_id', $userId)
            ->latest()
            ->get();

        return view('borrow.index', compact('receivedRequests', 'sentRequests'));
    }

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

    public function markReturned(BorrowRequest $borrowRequest, PointService $pointService)
        {
            if (!BorrowRequest::canTransition($borrowRequest->status, BorrowRequest::STATUS_RETURNED)) {
                return back()->with('error', 'This request cannot be marked as returned.');
            }

        DB::transaction(function () use ($borrowRequest, $pointService) {
            // Check if overdue and apply late penalty
            if ($borrowRequest->isOverdue()) {
                $overdueDays = $borrowRequest->overdue_days_count;
                $latePenalty = Penalty::calculateLatePenalty($overdueDays);

                if ($latePenalty > 0) {
                    // Create penalty record
                    Penalty::create([
                        'borrow_request_id' => $borrowRequest->id,
                        'borrower_id' => $borrowRequest->borrower_id,
                        'reported_by' => auth()->id(),
                        'type' => Penalty::TYPE_LATE_RETURN,
                        'penalty_points' => $latePenalty,
                        'reason' => "Returned {$overdueDays} day(s) late",
                        'status' => Penalty::STATUS_ACTIVE,
                    ]);

                    // Deduct penalty points from borrower
                    try {
                        $pointService->spend(
                            $borrowRequest->borrower_id,
                            $latePenalty,
                            'penalty_late',
                            "Late return penalty ({$overdueDays} days overdue) for borrow request #{$borrowRequest->id}",
                            $borrowRequest->id
                        );
                    } catch (\RuntimeException $e) {
                        // If insufficient points, set balance to 0
                        $borrower = \App\Models\User::find($borrowRequest->borrower_id);
                        $actualDeduct = $borrower->points_balance;
                        if ($actualDeduct > 0) {
                            $pointService->spend(
                                $borrowRequest->borrower_id,
                                $actualDeduct,
                                'penalty_late',
                                "Late return penalty (partial, {$overdueDays} days overdue) for borrow request #{$borrowRequest->id}",
                                $borrowRequest->id
                            );
                        }
                    }

                    $borrowRequest->penalty_points = ($borrowRequest->penalty_points ?? 0) + $latePenalty;
                    $borrowRequest->overdue_days = $overdueDays;
                }
            }

            $borrowRequest->status = BorrowRequest::STATUS_RETURNED;
            $borrowRequest->save();
        });

        return back()->with('success', 'Marked as returned.' . ($borrowRequest->penalty_points > 0 ? " Late penalty of {$borrowRequest->penalty_points} points applied." : ''));
    }

    /**
     * Report damage on a returned item (requires admin approval)
     */
    public function reportDamage(Request $request, BorrowRequest $borrowRequest)
    {
        // Only the lender can report damage
        if ($borrowRequest->lender_id !== auth()->id()) {
            return back()->with('error', 'Only the item owner can report damage.');
        }

        if (!in_array($borrowRequest->status, [BorrowRequest::STATUS_RETURNED, BorrowRequest::STATUS_BORROWED, BorrowRequest::STATUS_OVERDUE])) {
            return back()->with('error', 'Damage can only be reported on borrowed or returned items.');
        }

        $validated = $request->validate([
            'damage_description' => 'required|string|max:1000',
            'evidence_photo'     => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $damagePenalty = Penalty::DAMAGE_PENALTY;

        // Store the evidence photo
        $photoPath = $request->file('evidence_photo')->store('penalty-evidence', 'public');

        // Create penalty record with PENDING status — no points deducted yet
        Penalty::create([
            'borrow_request_id' => $borrowRequest->id,
            'borrower_id'       => $borrowRequest->borrower_id,
            'reported_by'       => auth()->id(),
            'type'              => Penalty::TYPE_DAMAGED,
            'penalty_points'    => $damagePenalty,
            'reason'            => $validated['damage_description'],
            'evidence_photo'    => $photoPath,
            'status'            => Penalty::STATUS_PENDING,
        ]);

        // Save damage description on the borrow request
        $borrowRequest->update([
            'damage_description' => $validated['damage_description'],
        ]);

        return back()->with('success', 'Damage report submitted. An admin will review it before any penalty is applied.');
    }

    /**
     * Mark an item as missing (requires admin approval)
     */
    public function markMissing(Request $request, BorrowRequest $borrowRequest)
    {
        // Only the lender can mark as missing
        if ($borrowRequest->lender_id !== auth()->id()) {
            return back()->with('error', 'Only the item owner can mark an item as missing.');
        }

        if (!BorrowRequest::canTransition($borrowRequest->status, BorrowRequest::STATUS_MISSING)) {
            return back()->with('error', 'This request cannot be marked as missing.');
        }

        $validated = $request->validate([
            'evidence_photo' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $totalBorrowPoints = (int) $borrowRequest->total_points;
        $missingPenalty = Penalty::calculateMissingPenalty($totalBorrowPoints);

        // Store the evidence photo
        $photoPath = $request->file('evidence_photo')->store('penalty-evidence', 'public');

        // Create penalty record with PENDING status — no points deducted yet
        Penalty::create([
            'borrow_request_id' => $borrowRequest->id,
            'borrower_id'       => $borrowRequest->borrower_id,
            'reported_by'       => auth()->id(),
            'type'              => Penalty::TYPE_MISSING,
            'penalty_points'    => $missingPenalty,
            'reason'            => 'Item was not returned and marked as missing',
            'evidence_photo'    => $photoPath,
            'status'            => Penalty::STATUS_PENDING,
        ]);

        // Update status to missing
        $borrowRequest->update([
            'status' => BorrowRequest::STATUS_MISSING,
        ]);

        return back()->with('success', 'Item marked as missing. An admin will review the report before any penalty is applied.');
    }
}
