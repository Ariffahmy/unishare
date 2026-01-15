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
}
