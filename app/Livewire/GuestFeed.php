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
    public $offset = 0;
    public $perPage = 10;
    public $hasMore = true;

    protected $queryString = ['filter' => ['except' => 'all']];

    public function setFilter($filter)
    {
        $this->filter = $filter;
        $this->offset = 0;
        $this->resetPage();
    }

    public function loadMore()
    {
        $this->offset += $this->perPage;
    }

    public function render()
    {
        $posts = $this->getInfiniteScrollPosts();
        $this->hasMore = count($posts) === $this->perPage;
        return view('livewire.guest-feed', [
            'posts' => $posts,
            'hasMore' => $this->hasMore,
        ]);
    }

    private function getInfiniteScrollPosts()
    {
        $publicAlbums = Album::where('visibility', 'public')->pluck('id');
        $query = Post::whereIn('album_id', $publicAlbums)
            ->where('already_deleted', false)
            ->where('already_expired', false)
            ->with(['user', 'album', 'likes', 'comments', 'media']);

        if ($this->filter === 'trending') {
            $query->withCount('likes')->orderByDesc('likes_count');
        } elseif ($this->filter === 'recent') {
            $query->orderByDesc('created_at');
        } else {
            $query->orderByDesc('created_at');
        }

        return $query->offset($this->offset)->limit($this->perPage)->get();
    }
}
