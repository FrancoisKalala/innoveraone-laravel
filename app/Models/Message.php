<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'replied_to_message_id',
        'content',
        'category',
        'category_id',
        'is_read',
        'read_at',
        'is_deleted',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_deleted' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Sender of the message
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // Receiver of the message
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    // Parent message if this is a reply
    public function repliedToMessage()
    {
        return $this->belongsTo(Message::class, 'replied_to_message_id');
    }

    // Replies to this message
    public function replies()
    {
        return $this->hasMany(Message::class, 'replied_to_message_id');
    }

    // Conversation tracking
    public function conversations()
    {
        return $this->hasMany(MessageConversation::class, 'last_message_id');
    }

    // Deleted messages
    public function deletions()
    {
        return $this->hasMany(DeletedMessage::class);
    }

    // Mark message as read
    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    // Mark message as deleted
    public function markAsDeleted()
    {
        $this->update(['is_deleted' => true]);
    }

    // Scope for unread messages
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    // Scope for conversation between two users
    public function scopeConversation($query, $userId1, $userId2)
    {
        return $query->where(function ($q) use ($userId1, $userId2) {
            $q->where('sender_id', $userId1)->where('receiver_id', $userId2);
        })->orWhere(function ($q) use ($userId1, $userId2) {
            $q->where('sender_id', $userId2)->where('receiver_id', $userId1);
        })->orderBy('created_at', 'asc');
    }
}
