<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemPhoto extends Model
{
    protected $fillable = [
        'item_id',
        'photo_path',
        'is_primary',
        'order',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * Get the full URL to the photo
     */
    public function getUrlAttribute(): string
    {
        return asset('storage/' . $this->photo_path);
    }
}
