<?php
namespace App\Livewire\Album;

use Livewire\Component;
use App\Models\Album;
use App\Models\AlbumFavorite;
use Illuminate\Support\Facades\Auth;

class AlbumFavoriteButton extends Component
{
    public Album $album;
    public $isFavorited = false;

    public function mount(Album $album)
    {
        $this->album = $album;
        $this->isFavorited = AlbumFavorite::where('album_id', $album->id)
            ->where('user_id', Auth::id())
            ->exists();
    }

    public function toggleFavorite()
    {
        $userId = Auth::id();
        $favorite = AlbumFavorite::where('album_id', $this->album->id)
            ->where('user_id', $userId)
            ->first();
        if ($favorite) {
            $favorite->delete();
            $this->isFavorited = false;
        } else {
            AlbumFavorite::create([
                'album_id' => $this->album->id,
                'user_id' => $userId,
                'favorited_at' => now(),
            ]);
            $this->isFavorited = true;
        }
    }
}
