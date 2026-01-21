<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeletedMessage extends Model
{
    protected $fillable = [
        'message_id',
        'user_id',
        'contact_id',
        'deleted_forever',
    ];

    protected $casts = [
        'deleted_forever' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function message(): BelongsTo
    {
        return $this->belongsTo(Message::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function contact(): BelongsTo
    {
        return $this->belongsTo(User::class, 'contact_id');
    }

    public function deleteForever(): bool
    {
        return $this->update(['deleted_forever' => true]);
    }
}
