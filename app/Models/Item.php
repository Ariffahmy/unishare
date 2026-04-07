<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Item extends Model
{
    // These fields can be mass-assigned (e.g., Item::create($data))
    protected $fillable = [
        'owner_id',
        'title',
        'category',
        'description',
        'condition',
        'points_per_day',
        'max_days',
        'is_active',
    ];

    // Each Item belongs to one User (the owner)
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Users who have liked this item
     */
    public function likedByUsers()
    {
        return $this->belongsToMany(User::class, 'user_item_likes')->withTimestamps();
    }

    /**
     * Photos of this item
     */
    public function photos()
    {
        return $this->hasMany(ItemPhoto::class)->orderBy('order');
    }

    /**
     * Get the primary photo
     */
    public function primaryPhoto()
    {
        return $this->hasOne(ItemPhoto::class)->where('is_primary', true);
    }

    /**
     * Reviews for this item
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get average rating for this item
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Borrow requests for this item
     */
    public function borrowRequests()
    {
        return $this->hasMany(BorrowRequest::class);
    }
}
