<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\Album;
use Livewire\Component;
use Livewire\WithPagination;

class GuestFeed extends Component
{
    use WithPagination;

    public $filter = 'all';

    protected $queryString = ['filter' => ['except' => 'all']];

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->resetPage();
    }

    public function render()
    {
        // Get only public albums
        $publicAlbums = Album::where('visibility', 'public')->pluck('id');

        // Get posts from public albums only
        $posts = Post::whereIn('album_id', $publicAlbums)
            ->with(['user', 'album', 'likes', 'comments'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.guest-feed', [
            'posts' => $posts,
        ]);
    }
}
