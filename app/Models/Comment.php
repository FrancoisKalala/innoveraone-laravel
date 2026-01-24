<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'post_id', 'content', 'is_pinned', 'is_highlighted', 'likes_count'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'is_pinned' => 'boolean',
        'is_highlighted' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function likes()
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function commentLikes()
    {
        return $this->hasMany(CommentLike::class);
    }

    public function commentDislikes()
    {
        return $this->hasMany(CommentDislike::class);
    }

    public function answers()
    {
        return $this->hasMany(CommentAnswer::class);
    }

    public function isLikedBy(User $user)
    {
        return $this->likes()->where('user_id', $user->id)->exists();
    }

    public function isCommentLikedBy(User $user)
    {
        return $this->commentLikes()->where('user_id', $user->id)->exists();
    }

    public function isDislikedBy(User $user)
    {
        return $this->commentDislikes()->where('user_id', $user->id)->exists();
    }
}
