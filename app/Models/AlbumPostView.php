<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlbumPostView extends Model
{
    protected $fillable = [
        'user_id', 'album_id', 'post_id', 'viewed_at'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    public function album() {
        return $this->belongsTo(Album::class);
    }
    public function post() {
        return $this->belongsTo(Post::class);
    }
}
