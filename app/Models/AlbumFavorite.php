<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AlbumFavorite extends Model
{
    protected $table = 'album_favorites';

    protected $fillable = [
        'album_id',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function album(): BelongsTo
    {
        return $this->belongsTo(Album::class, 'album_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
