<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'album_id',
        'content',
        'status',
        'expiration_hours',
        'interaction_type',
        'already_expired',
        'already_deleted',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function album()
    {
        return $this->belongsTo(Album::class, 'album_id');
    }

    // Legacy method for backward compatibility
    public function chapter()
    {
        return $this->album();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'notifiable');
    }

    public function files()
    {
        return $this->hasMany(PostFile::class);
    }

    public function views()
    {
        return $this->hasMany(PostView::class);
    }

    public function expiration()
    {
        return $this->hasOne(ExpiredPost::class);
    }

    public function deletion()
    {
        return $this->hasOne(DeletedPost::class);
    }

    public function isLikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function isViewedBy(User $user)
    {
        return $this->views()->where('user_id', $user->id)->where('viewed', true)->exists();
    }

    public function isExpired(): bool
    {
        return $this->already_expired || $this->expiration()->exists();
    }

    public function isDeleted(): bool
    {
        return $this->already_deleted || $this->deletion()->exists();
    }

    public function canLike(): bool
    {
        return in_array($this->interaction_type, ['like', 'like_dislike', 'like_comment', 'all']);
    }

    public function canDislike(): bool
    {
        return in_array($this->interaction_type, ['dislike', 'like_dislike', 'dislike_comment', 'all']);
    }

    public function canComment(): bool
    {
        return in_array($this->interaction_type, ['comment', 'like_comment', 'dislike_comment', 'all']);
    }
}
