<?php

namespace App\Livewire\Album;

use App\Models\Album;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class AlbumPost extends Component
{
    use WithPagination;

    public $albumId;
    public $album;
    public $perPage = 15;
    public $hasMore = true;
    public $offset = 0;

    public function mount($album)
    {
        $this->albumId = $album;
        $this->album = Album::with('user')->findOrFail($album);
    }

    public function loadMore()
    {
        $this->offset += $this->perPage;
    }

    public function render()
    {
        $posts = Post::where('album_id', $this->albumId)
            ->where('already_deleted', false)
            ->where('already_expired', false)
            ->with(['user', 'album', 'likes', 'comments', 'media'])
            ->orderByDesc('created_at')
            ->offset($this->offset)
            ->limit($this->perPage)
            ->get();
        $this->hasMore = $posts->count() === $this->perPage;
        return view('livewire.album.album-post', [
            'album' => $this->album,
            'posts' => $posts,
            'hasMore' => $this->hasMore,
        ]);
    }
}
