<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Album extends Model
{
    use HasFactory;

    protected $table = 'albums';

    protected $fillable = [
        'user_id',
        'title',
        'image',
        'description',
        'slug',
        'visibility',
        'category',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($album) {
            if (empty($album->slug)) {
                $album->slug = Str::slug($album->title . '-' . uniqid());
            }
        });
    }

    // Creator of the album
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Publications in this album
    public function posts()
    {
        return $this->hasMany(Post::class, 'album_id');
    }

    // Contacts who can view this private album
    public function contacts()
    {
        return $this->belongsToMany(User::class, 'album_contacts')
            ->withTimestamps();
    }

    // View tracking
    public function views()
    {
        return $this->hasMany(AlbumView::class, 'album_id');
    }

    // Favorites
    public function favorites()
    {
        return $this->hasMany(AlbumFavorite::class, 'album_id');
    }

    // Check if user can view this album
    public function canBeViewedBy(User $user)
    {
        // Creator can always view
        if ($this->user_id === $user->id) {
            return true;
        }

        // Public albums can be viewed by all
        if ($this->visibility === 'public') {
            return true;
        }

        // Private albums only by added contacts
        return $this->contacts()->where('user_id', $user->id)->exists();
    }

    // Check if album is public
    public function isPublic()
    {
        return $this->visibility === 'public';
    }

    // Check if album is private
    public function isPrivate()
    {
        return $this->visibility === 'private';
    }
}
