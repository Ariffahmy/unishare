<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone_number',
        'password',
        'points_balance',
        'bio',
        'avatar',
        'location',
        'is_admin',
        'is_suspended',
        'suspended_at',
        'suspension_reason',
    ];

    /**
     * Items this user has liked/favorited
     */
    public function likedItems()
    {
        return $this->belongsToMany(Item::class, 'user_item_likes')->withTimestamps();
    }

    /**
     * Borrow requests made by this user (as borrower)
     */
    public function borrowRequests()
    {
        return $this->hasMany(\App\Models\BorrowRequest::class, 'borrower_id');
    }

    /**
     * Items owned by this user
     */
    public function items()
    {
        return $this->hasMany(Item::class, 'owner_id');
    }

    /**
     * Penalties received by this user
     */
    public function penalties()
    {
        return $this->hasMany(Penalty::class, 'borrower_id');
    }

    /**
     * Ratings given by this user
     */
    public function ratingsGiven()
    {
        return $this->hasMany(Rating::class, 'rater_id');
    }

    /**
     * Ratings received by this user
     */
    public function ratingsReceived()
    {
        return $this->hasMany(Rating::class, 'rated_user_id');
    }

    /**
     * Get average rating for this user
     */
    public function getAverageRatingAttribute()
    {
        return round($this->ratingsReceived()->avg('rating') ?? 0, 1);
    }

    /**
     * Conversations this user is part of
     */
    public function conversations()
    {
        return Conversation::where('user_one_id', $this->id)
            ->orWhere('user_two_id', $this->id);
    }

    /**
     * Get total unread messages count
     */
    public function getUnreadMessagesCountAttribute()
    {
        return Message::whereHas('conversation', function ($query) {
            $query->where('user_one_id', $this->id)
                  ->orWhere('user_two_id', $this->id);
        })
        ->where('sender_id', '!=', $this->id)
        ->whereNull('read_at')
        ->count();
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'is_suspended' => 'boolean',
            'suspended_at' => 'datetime',
        ];
    }
}
