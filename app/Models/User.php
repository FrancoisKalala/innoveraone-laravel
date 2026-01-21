<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'bio',
        'profile_photo_path',
        'cover_photo_path',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function albums()
    {
        return $this->hasMany(Album::class);
    }

    // Legacy method for backward compatibility
    public function chapters()
    {
        return $this->albums();
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function media()
    {
        return $this->hasMany(Media::class);
    }

    public function followers()
    {
        return $this->hasMany(Follower::class, 'following_id');
    }

    public function following()
    {
        return $this->hasMany(Follower::class, 'follower_id');
    }

    public function groupsCreated()
    {
        return $this->hasMany(Group::class, 'created_by');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_members')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // Contact relationships
    public function sentContactRequests()
    {
        return $this->hasMany(Contact::class, 'user_id');
    }

    public function receivedContactRequests()
    {
        return $this->hasMany(Contact::class, 'contact_id');
    }

    public function contacts()
    {
        return $this->belongsToMany(User::class, 'contact_user', 'user_id', 'contact_id')
            ->withPivot('is_deleted')
            ->withTimestamps();
    }

    public function blockedUsers()
    {
        return $this->hasMany(ContactBlock::class, 'user_id');
    }

    public function blockedByUsers()
    {
        return $this->hasMany(ContactBlock::class, 'blocked_user_id');
    }

    // View tracking relationships
    public function postViews()
    {
        return $this->hasMany(PostView::class);
    }

    public function albumViews()
    {
        return $this->hasMany(AlbumView::class);
    }

    public function favoriteAlbums()
    {
        return $this->hasMany(AlbumFavorite::class);
    }

    // Legacy methods for backward compatibility
    public function chapterViews()
    {
        return $this->albumViews();
    }

    public function favoriteChapters()
    {
        return $this->favoriteAlbums();
    }

    public function groupMessages()
    {
        return $this->hasMany(GroupMessage::class);
    }

    public function isFollowedBy(User $user)
    {
        return $this->followers()
            ->where('follower_id', $user->id)
            ->exists();
    }

    public function isFollowing(User $user)
    {
        return $this->following()
            ->where('following_id', $user->id)
            ->exists();
    }
}

