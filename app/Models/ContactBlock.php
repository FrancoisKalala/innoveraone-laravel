<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactBlock extends Model
{
    protected $fillable = [
        'user_id',
        'blocked_user_id',
        'can_message',
        'can_like',
        'can_comment',
    ];

    protected $casts = [
        'can_message' => 'boolean',
        'can_like' => 'boolean',
        'can_comment' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function blockedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'blocked_user_id');
    }

    public function blockAll(): bool
    {
        return $this->update([
            'can_message' => false,
            'can_like' => false,
            'can_comment' => false,
        ]);
    }

    public function unblockAll(): bool
    {
        return $this->update([
            'can_message' => true,
            'can_like' => true,
            'can_comment' => true,
        ]);
    }
}
