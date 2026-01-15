<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BorrowRequest extends Model
{
    // 1) Status constants
    public const STATUS_PENDING   = 'pending';
    public const STATUS_APPROVED  = 'approved';
    public const STATUS_REJECTED  = 'rejected';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_BORROWED  = 'borrowed';
    public const STATUS_RETURNED  = 'returned';

    // 2) Mass assignment
    protected $fillable = [
        'item_id',
        'borrower_id',
        'lender_id',
        'start_date',
        'end_date',
        'points_per_day',
        'total_points',
        'status',
        'note',
    ];

    // 3) Casting
    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    // 4) Relationships
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function borrower()
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }

    public function lender()
    {
        return $this->belongsTo(User::class, 'lender_id');
    }

    // 5) Business logic
    public function calculateTotalPoints(): int
    {
        $days = $this->start_date->diffInDays($this->end_date) + 1;
        return $days * $this->points_per_day;
    }

    public static function hasOverlapForItem(
        int $itemId,
        string $startDate,
        string $endDate,
        array $statuses = [self::STATUS_APPROVED, self::STATUS_BORROWED]
    ): bool {
        return self::query()
            ->where('item_id', $itemId)
            ->whereIn('status', $statuses)
            ->whereDate('start_date', '<=', $endDate)
            ->whereDate('end_date', '>=', $startDate)
            ->exists();
    }

    // 6) Status transition rules
    public static function canTransition(string $from, string $to): bool
    {
        $allowed = [
            self::STATUS_PENDING  => [self::STATUS_APPROVED, self::STATUS_REJECTED],
            self::STATUS_APPROVED => [self::STATUS_BORROWED, self::STATUS_CANCELLED],
            self::STATUS_BORROWED => [self::STATUS_RETURNED],
            self::STATUS_REJECTED => [],
            self::STATUS_CANCELLED => [],
            self::STATUS_RETURNED => [],
        ];

        return in_array($to, $allowed[$from] ?? [], true);
    }
}
