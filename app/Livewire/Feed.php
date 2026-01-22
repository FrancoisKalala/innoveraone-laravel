<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\Follower;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;

class Feed extends Component
{
    use WithPagination;

    public $filterType = 'all'; // all, contacts, following, albums, mine
    public $albumId = null; // filter by specific album/chapter
    public $search = '';
    public $searchType = 'all'; // all, user, post, album
    public $recentSearches = [];
    public $offset = 0;
    public $perPage = 15;
    public $hasMore = true;

    #[On('postCreated')]
    #[On('postUpdated')]
    #[On('postShared')]
    public function refreshFeed()
    {
        $this->resetPage();
    }

    public function mount($album = null)
    {
        if ($album) {
            $this->albumId = $album;
            $this->filterType = 'album';
        }

        $this->recentSearches = session()->get($this->recentSearchSessionKey(), []);
    }

    public function loadFeed()
    {
        $userId = auth()->id();
        $query = Post::with('user', 'album', 'likes', 'comments');

        // Apply search filters
        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';

            if ($this->searchType === 'all') {
                // Search across all: user name, post content, and album title
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('content', 'like', $searchTerm)
                      ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                          $userQuery->where('name', 'like', $searchTerm)
                                    ->orWhere('username', 'like', $searchTerm);
                      })
                      ->orWhereHas('album', function ($albumQuery) use ($searchTerm) {
                          $albumQuery->where('title', 'like', $searchTerm);
                      });
                });
            } elseif ($this->searchType === 'user') {
                // Search by user only
                $query->whereHas('user', function ($userQuery) use ($searchTerm) {
                    $userQuery->where('name', 'like', $searchTerm)
                              ->orWhere('username', 'like', $searchTerm);
                });
            } elseif ($this->searchType === 'post') {
                // Search by post content only
                $query->where('content', 'like', $searchTerm);
            } elseif ($this->searchType === 'album') {
                // Search by album title only
                $query->whereHas('album', function ($albumQuery) use ($searchTerm) {
                    $albumQuery->where('title', 'like', $searchTerm);
                });
            }
        }

        // Apply filter type
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
        } elseif ($this->filterType === 'mine') {
            // Only the authenticated user's posts
            if ($userId) {
                $query->where('user_id', $userId);
            }
        }

        // Default "all" view should not show the current user's posts
        if ($this->filterType === 'all' && $userId) {
            $query->where('user_id', '!=', $userId);
        }

        return $query->where('already_deleted', false)
            ->where('already_expired', false)
            ->latest()
            ->paginate(15);
    }

    public function setFilter($type)
    {
        $this->filterType = $type;
        $this->offset = 0;
        $this->resetPage();
    }

    public function updatingSearch()
    {
        $this->offset = 0;
        $this->resetPage();
    }

    public function updatedSearch($value)
    {
        $this->offset = 0;
        $this->resetPage();
        $this->addRecentSearch($value);
    }

    public function clearSearch()
    {
        $this->search = '';
        $this->searchType = 'all';
        $this->resetPage();
    }

    public function clearRecentSearches()
    {
        $this->recentSearches = [];
        session()->put($this->recentSearchSessionKey(), []);
    }

    public function useRecentSearch($index)
    {
        if (!isset($this->recentSearches[$index])) {
            return;
        }

        $term = $this->recentSearches[$index];
        $this->search = $term;
        $this->offset = 0;
        $this->resetPage();
        $this->addRecentSearch($term);
    }

    protected function addRecentSearch($term)
    {
        $term = trim($term);

        if (mb_strlen($term) < 2) {
            return;
        }

        // Remove duplicate (case-insensitive) entries and prepend latest
        $filtered = collect($this->recentSearches)
            ->filter(fn($existing) => strcasecmp($existing, $term) !== 0)
            ->values();

        $this->recentSearches = collect([$term])
            ->merge($filtered)
            ->take(10)
            ->values()
            ->toArray();

        session()->put($this->recentSearchSessionKey(), $this->recentSearches);
    }

    protected function recentSearchSessionKey(): string
    {
        $userId = auth()->id();
        return 'recent_searches_' . ($userId ?: 'guest');
    }

    public function setSearchType($type)
    {
        $this->searchType = $type;
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
        $this->hasMore = count($posts) >= $this->perPage;
        return view('livewire.feed', [
            'posts' => $posts,
        ]);
    }

    private function getInfiniteScrollPosts()
    {
        $userId = auth()->id();
        $query = Post::with('user', 'album', 'likes', 'comments');

        if (!empty($this->search)) {
            $searchTerm = '%' . $this->search . '%';
            if ($this->searchType === 'all') {
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('content', 'like', $searchTerm)
                      ->orWhereHas('user', function ($userQuery) use ($searchTerm) {
                          $userQuery->where('name', 'like', $searchTerm)->orWhere('username', 'like', $searchTerm);
                      })
                      ->orWhereHas('album', function ($albumQuery) use ($searchTerm) {
                          $albumQuery->where('title', 'like', $searchTerm);
                      });
                });
            } elseif ($this->searchType === 'user') {
                $query->whereHas('user', function ($userQuery) use ($searchTerm) {
                    $userQuery->where('name', 'like', $searchTerm)->orWhere('username', 'like', $searchTerm);
                });
            } elseif ($this->searchType === 'post') {
                $query->where('content', 'like', $searchTerm);
            } elseif ($this->searchType === 'album') {
                $query->whereHas('album', function ($albumQuery) use ($searchTerm) {
                    $albumQuery->where('title', 'like', $searchTerm);
                });
            }
        }

        if ($this->filterType === 'album' && $this->albumId) {
            $query->where('album_id', $this->albumId);
        } elseif ($this->filterType === 'contacts') {
            $contactIds = auth()->user()->contacts()->pluck('users.id');
            $query->whereIn('user_id', $contactIds);
        } elseif ($this->filterType === 'following') {
            $followingIds = Follower::where('follower_id', $userId)->pluck('following_id');
            $query->whereIn('user_id', $followingIds);
        } elseif ($this->filterType === 'albums') {
            $albumIds = auth()->user()->albums()->pluck('id');
            $query->whereIn('album_id', $albumIds);
        } elseif ($this->filterType === 'mine') {
            if ($userId) {
                $query->where('user_id', $userId);
            }
        } elseif ($this->filterType === 'all' && $userId) {
            $query->where('user_id', '!=', $userId);
        }

        return $query->where('already_deleted', false)
            ->where('already_expired', false)
            ->latest()
            ->offset($this->offset)
            ->limit($this->perPage)
            ->get();
    }
}
