<?php
namespace App\Livewire\Album;

use Livewire\Component;
use App\Models\Album;
use Illuminate\Support\Facades\Auth;

class FavoriteButton extends Component
{
    public $albumId;
    public $album;
    public $isFavorited = false;

    public function mount($albumId)
    {
        $this->albumId = $albumId;
        $this->album = Album::find($albumId);
        if ($this->album) {
            $this->isFavorited = $this->album->favorites()->where('user_id', Auth::id())->exists();
        }
    }

    public function toggleFavorite()
    {
        $userId = Auth::id();
        if (!$this->album) return;
        $favorite = $this->album->favorites()->where('user_id', $userId)->first();
        if ($favorite) {
            $favorite->delete();
            $this->isFavorited = false;
        } else {
            $this->album->favorites()->create([
                'user_id' => $userId,
                'album_id' => $this->albumId,
                'favorited_at' => now(),
            ]);
            $this->isFavorited = true;
        }
    }

    public function render()
    {
        return view('livewire.album.favorite-button', [
            'isFavorited' => $this->isFavorited,
        ]);
    }
}
