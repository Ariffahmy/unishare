<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Item;
use App\Models\BorrowRequest;
use App\Models\Penalty;
use Illuminate\Database\Seeder;

class PenaltyDemoSeeder extends Seeder
{
    public function run(): void
    {
        // Get first two users (or create them)
        $users = User::take(2)->get();

        if ($users->count() < 2) {
            echo "Need at least 2 users in the database.\n";
            return;
        }

        $lender = $users[0];
        $borrower = $users[1];

        echo "Lender:   {$lender->name} (ID: {$lender->id}, Balance: {$lender->points_balance})\n";
        echo "Borrower: {$borrower->name} (ID: {$borrower->id}, Balance: {$borrower->points_balance})\n\n";

        // Get an item owned by the lender, or any item
        $item = Item::where('owner_id', $lender->id)->first();
        if (!$item) {
            $item = Item::first();
        }
        if (!$item) {
            echo "Need at least 1 item in the database.\n";
            return;
        }

        echo "Using item: {$item->title} (ID: {$item->id})\n\n";

        // ============================
        // Example 1: OVERDUE (borrowed, past end_date) 
        // ============================
        $overdue = BorrowRequest::create([
            'item_id'        => $item->id,
            'borrower_id'    => $borrower->id,
            'lender_id'      => $lender->id,
            'start_date'     => now()->subDays(10),
            'end_date'       => now()->subDays(3), // ended 3 days ago = 3 days overdue
            'points_per_day' => $item->points_per_day ?? 5,
            'total_points'   => ($item->points_per_day ?? 5) * 11,
            'status'         => BorrowRequest::STATUS_BORROWED, // still borrowed!
        ]);
        echo "✅ Created OVERDUE borrow request #{$overdue->id} (3 days overdue)\n";

        // ============================
        // Example 2: DAMAGED (returned with damage)
        // ============================
        $damaged = BorrowRequest::create([
            'item_id'        => $item->id,
            'borrower_id'    => $borrower->id,
            'lender_id'      => $lender->id,
            'start_date'     => now()->subDays(14),
            'end_date'       => now()->subDays(7),
            'points_per_day' => $item->points_per_day ?? 5,
            'total_points'   => ($item->points_per_day ?? 5) * 8,
            'status'         => BorrowRequest::STATUS_RETURNED,
            'penalty_points' => Penalty::DAMAGE_PENALTY,
            'damage_description' => 'Screen has visible scratches and one corner is dented',
        ]);

        Penalty::create([
            'borrow_request_id' => $damaged->id,
            'borrower_id'       => $borrower->id,
            'reported_by'       => $lender->id,
            'type'              => Penalty::TYPE_DAMAGED,
            'penalty_points'    => Penalty::DAMAGE_PENALTY,
            'reason'            => 'Screen has visible scratches and one corner is dented',
            'status'            => Penalty::STATUS_ACTIVE,
        ]);
        echo "✅ Created DAMAGED example #{$damaged->id} (50 pts penalty)\n";

        // ============================
        // Example 3: MISSING (never returned)
        // ============================
        $missingTotal = ($item->points_per_day ?? 5) * 6;
        $missingPenalty = Penalty::calculateMissingPenalty($missingTotal);

        $missing = BorrowRequest::create([
            'item_id'        => $item->id,
            'borrower_id'    => $borrower->id,
            'lender_id'      => $lender->id,
            'start_date'     => now()->subDays(20),
            'end_date'       => now()->subDays(14),
            'points_per_day' => $item->points_per_day ?? 5,
            'total_points'   => $missingTotal,
            'status'         => BorrowRequest::STATUS_MISSING,
            'penalty_points' => $missingPenalty,
        ]);

        Penalty::create([
            'borrow_request_id' => $missing->id,
            'borrower_id'       => $borrower->id,
            'reported_by'       => $lender->id,
            'type'              => Penalty::TYPE_MISSING,
            'penalty_points'    => $missingPenalty,
            'reason'            => 'Item was not returned and marked as missing',
            'status'            => Penalty::STATUS_ACTIVE,
        ]);
        echo "✅ Created MISSING example #{$missing->id} ({$missingPenalty} pts penalty = 3x borrow cost)\n";

        // ============================
        // Example 4: LATE RETURN (returned late, penalty applied)
        // ============================
        $lateDays = 5;
        $latePenalty = Penalty::calculateLatePenalty($lateDays);

        $lateReturn = BorrowRequest::create([
            'item_id'        => $item->id,
            'borrower_id'    => $borrower->id,
            'lender_id'      => $lender->id,
            'start_date'     => now()->subDays(12),
            'end_date'       => now()->subDays(7),
            'points_per_day' => $item->points_per_day ?? 5,
            'total_points'   => ($item->points_per_day ?? 5) * 6,
            'status'         => BorrowRequest::STATUS_RETURNED,
            'penalty_points' => $latePenalty,
            'overdue_days'   => $lateDays,
        ]);

        Penalty::create([
            'borrow_request_id' => $lateReturn->id,
            'borrower_id'       => $borrower->id,
            'reported_by'       => $lender->id,
            'type'              => Penalty::TYPE_LATE_RETURN,
            'penalty_points'    => $latePenalty,
            'reason'            => "Returned {$lateDays} day(s) late",
            'status'            => Penalty::STATUS_ACTIVE,
        ]);
        echo "✅ Created LATE RETURN example #{$lateReturn->id} ({$latePenalty} pts penalty = {$lateDays} days × 5 pts/day)\n";

        echo "\n🎉 Done! You can now view:\n";
        echo "  - Borrow Requests page: /borrow-requests\n";
        echo "  - Admin Penalties page: /admin/penalties\n";
        echo "  - Admin Borrow Requests: /admin/borrow-requests\n";
    }
}
