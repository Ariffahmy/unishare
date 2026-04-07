<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penalty extends Model
{
    // Penalty types
    public const TYPE_LATE_RETURN = 'late_return';
    public const TYPE_DAMAGED    = 'damaged';
    public const TYPE_MISSING    = 'missing';

    // Statuses
    public const STATUS_PENDING  = 'pending';   // Awaiting admin review
    public const STATUS_ACTIVE   = 'active';    // Approved and points deducted
    public const STATUS_REJECTED = 'rejected';  // Admin rejected the report
    public const STATUS_RESOLVED = 'resolved';
    public const STATUS_WAIVED   = 'waived';

    // Penalty rates (points)
    public const LATE_PENALTY_PER_DAY = 5;     // 5 points per overdue day
    public const DAMAGE_PENALTY       = 50;    // Flat 50 points for damage
    public const MISSING_PENALTY_MULTIPLIER = 3; // 3x the total borrow cost

    protected $fillable = [
        'borrow_request_id',
        'borrower_id',
        'reported_by',
        'type',
        'penalty_points',
        'reason',
        'evidence_photo',
        'status',
    ];

    /**
     * The borrow request this penalty belongs to
     */
    public function borrowRequest()
    {
        return $this->belongsTo(BorrowRequest::class);
    }

    /**
     * The borrower who received this penalty
     */
    public function borrower()
    {
        return $this->belongsTo(User::class, 'borrower_id');
    }

    /**
     * The user who reported/created this penalty
     */
    public function reportedBy()
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    /**
     * Calculate late return penalty points
     */
    public static function calculateLatePenalty(int $overdueDays): int
    {
        return $overdueDays * self::LATE_PENALTY_PER_DAY;
    }

    /**
     * Calculate missing item penalty points (3x the borrow cost)
     */
    public static function calculateMissingPenalty(int $totalBorrowPoints): int
    {
        return $totalBorrowPoints * self::MISSING_PENALTY_MULTIPLIER;
    }

    /**
     * Get a friendly label for the penalty type
     */
    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            self::TYPE_LATE_RETURN => 'Late Return',
            self::TYPE_DAMAGED    => 'Damaged Item',
            self::TYPE_MISSING    => 'Missing Item',
            default               => ucfirst($this->type),
        };
    }

    /**
     * Get a friendly label for the penalty status
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING  => 'Pending Review',
            self::STATUS_ACTIVE   => 'Active',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_RESOLVED => 'Resolved',
            self::STATUS_WAIVED   => 'Waived',
            default               => ucfirst($this->status),
        };
    }
}
