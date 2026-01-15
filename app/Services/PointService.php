<?php

namespace App\Services;

use App\Models\PointTransaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class PointService
{
    public function add(
    int $userId,
    int $amount,
    string $type = 'earn',
    ?string $description = null,
    ?int $borrowRequestId = null
): PointTransaction {
    if ($amount <= 0) {
        throw new \InvalidArgumentException('Amount must be > 0 for add().');
    }

    return DB::transaction(function () use ($userId, $amount, $type, $description, $borrowRequestId) {
        // 1) Create transaction log
        $tx = PointTransaction::create([
            'user_id' => $userId,
            'borrow_request_id' => $borrowRequestId,
            'type' => $type,
            'amount' => $amount, // positive
            'description' => $description,
        ]);

        // 2) Update user balance
        User::where('id', $userId)->increment('points_balance', $amount);

        return $tx;
    });
}

   public function spend(
    int $userId,
    int $amount,
    string $type = 'spend',
    ?string $description = null,
    ?int $borrowRequestId = null
): PointTransaction {
    if ($amount <= 0) {
        throw new \InvalidArgumentException('Amount must be > 0 for spend().');
    }

    return DB::transaction(function () use ($userId, $amount, $type, $description, $borrowRequestId) {
        // Lock the row so two requests can't spend at the same time
        $user = User::where('id', $userId)->lockForUpdate()->firstOrFail();

        if ($user->points_balance < $amount) {
            throw new \RuntimeException('Insufficient points.');
        }

        // 1) Create transaction log (negative)
        $tx = PointTransaction::create([
            'user_id' => $userId,
            'borrow_request_id' => $borrowRequestId,
            'type' => $type,
            'amount' => -$amount, // negative
            'description' => $description,
        ]);

        // 2) Update user balance
        $user->decrement('points_balance', $amount);

        return $tx;
    });
}
 

}
