<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $fillable = [
        'user_one_id',
        'user_two_id',
    ];

    public function userOne(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }

    public function userTwo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    /**
     * Get the other user in the conversation
     */
    public function getOtherUser($userId)
    {
        return $this->user_one_id == $userId ? $this->userTwo : $this->userOne;
    }

    /**
     * Check if a user is part of this conversation
     */
    public function hasUser($userId): bool
    {
        return $this->user_one_id == $userId || $this->user_two_id == $userId;
    }

    /**
     * Get or create a conversation between two users
     */
    public static function findOrCreateBetween($userOneId, $userTwoId)
    {
        // Always store the smaller ID first for consistency
        $ids = [$userOneId, $userTwoId];
        sort($ids);

        return static::firstOrCreate([
            'user_one_id' => $ids[0],
            'user_two_id' => $ids[1],
        ]);
    }

    /**
     * Get unread messages count for a user
     */
    public function unreadCountFor($userId): int
    {
        return $this->messages()
            ->where('sender_id', '!=', $userId)
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Get the latest message
     */
    public function latestMessage()
    {
        return $this->messages()->latest()->first();
    }
}
