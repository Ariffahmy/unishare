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
    public const STATUS_OVERDUE   = 'overdue';
    public const STATUS_MISSING   = 'missing';

    // 2) Mass assignment
    protected $fillable = [
        'item_id',
        'borrower_id',
        'lender_id',
        'start_date',
        'end_date',
        'points_per_day',
        'total_points',
        'penalty_points',
        'overdue_days',
        'damage_description',
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

    /**
     * Ratings for this borrow request
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Review for this borrow request
     */
    public function review()
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Penalties for this borrow request
     */
    public function penalties()
    {
        return $this->hasMany(Penalty::class);
    }

    /**
     * Check if this borrow request is overdue
     */
    public function isOverdue(): bool
    {
        if ($this->status !== self::STATUS_BORROWED && $this->status !== self::STATUS_OVERDUE) {
            return false;
        }

        return $this->end_date->isPast();
    }

    /**
     * Get overdue days count
     */
    public function getOverdueDaysCountAttribute(): int
    {
        if (!$this->isOverdue()) {
            return 0;
        }

        return (int) $this->end_date->diffInDays(now());
    }

    /**
     * Check if this request can be rated by a user
     */
    public function canBeRatedBy($userId): bool
    {
        // Must be returned and user must be part of the transaction
        if ($this->status !== self::STATUS_RETURNED) {
            return false;
        }
        
        if ($this->borrower_id !== $userId && $this->lender_id !== $userId) {
            return false;
        }

        // Check if user already rated
        return !$this->ratings()->where('rater_id', $userId)->exists();
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
            self::STATUS_PENDING   => [self::STATUS_APPROVED, self::STATUS_REJECTED],
            self::STATUS_APPROVED  => [self::STATUS_BORROWED, self::STATUS_CANCELLED],
            self::STATUS_BORROWED  => [self::STATUS_RETURNED, self::STATUS_OVERDUE, self::STATUS_MISSING],
            self::STATUS_OVERDUE   => [self::STATUS_RETURNED, self::STATUS_MISSING],
            self::STATUS_REJECTED  => [],
            self::STATUS_CANCELLED => [],
            self::STATUS_RETURNED  => [],
            self::STATUS_MISSING   => [self::STATUS_RETURNED], // Can be resolved if found
        ];

        return in_array($to, $allowed[$from] ?? [], true);
    }
}
