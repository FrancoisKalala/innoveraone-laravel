<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\Follower;
use Livewire\Component;
use Livewire\WithPagination;

class Feed extends Component
{
    use WithPagination;

    public $filterType = 'all'; // all, contacts, following, chapters
    public $albumId = null; // filter by specific album/chapter

    protected $listeners = ['postCreated' => 'refreshFeed'];

    public function mount($album = null)
    {
        if ($album) {
            $this->albumId = $album;
            $this->filterType = 'album';
        }
    }

    public function refreshFeed()
    {
        $this->resetPage();
    }

    public function loadFeed()
    {
        $userId = auth()->id();
        $query = Post::with('user', 'album', 'likes', 'comments');

        if ($this->filterType === 'album' && $this->albumId) {
            // Posts in a specific album
            $query->where('album_id', $this->albumId);
        } elseif ($this->filterType === 'contacts') {
            // Posts from accepted contacts
            $contactIds = auth()->user()->contacts()->pluck('users.id');
            $query->whereIn('user_id', $contactIds);
        } elseif ($this->filterType === 'following') {
            // Posts from followed users
            $followingIds = Follower::where('follower_id', $userId)
                ->pluck('following_id');
            $query->whereIn('user_id', $followingIds);
        } elseif ($this->filterType === 'albums') {
            // Posts in user's own albums
            $albumIds = auth()->user()->albums()->pluck('id');
            $query->whereIn('album_id', $albumIds);
        }

        return $query->where('already_deleted', false)
            ->where('already_expired', false)
            ->latest()
            ->paginate(15);
    }

    public function setFilter($type)
    {
        $this->filterType = $type;
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.feed', [
            'posts' => $this->loadFeed(),
        ]);
    }
}
