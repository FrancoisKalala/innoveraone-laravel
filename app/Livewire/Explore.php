<?php

namespace App\Livewire;

use App\Models\User;

use App\Models\Follower;
use App\Models\Album;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;


class Explore extends Component
{
    public $searchQuery = '';
    public $recentSearches = [];
    public $activeTab = 'users';

    public function mount()
    {
        $this->recentSearches = session()->get($this->recentSearchSessionKey(), []);
        if (!$this->activeTab) {
            $this->activeTab = 'users';
        }
    }

    public function updatedSearchQuery()
    {
        if (!empty($this->searchQuery)) {
            $this->addRecentSearch($this->searchQuery);
        }
    }

    public function useRecentSearch($index)
    {
        if (!isset($this->recentSearches[$index])) {
            return;
        }
        $term = $this->recentSearches[$index];
        $this->searchQuery = $term;
        $this->addRecentSearch($term);
    }

    protected function addRecentSearch($term)
    {
        $term = trim($term);
        if (mb_strlen($term) < 2) {
            return;
        }
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
        $userId = Auth::id();
        return 'recent_searches_explore_' . ($userId ?: 'guest');
    }

    #[On('user-followed')]
    #[On('user-unfollowed')]
    public function refresh()
    {
        // Re-render component to reflect changes
    }

    public function toggleFollow($userId)
    {
        $user = User::find($userId);

        if (!$user || Auth::id() === $user->id) {
            return;
        }

        if (Auth::user()->isFollowing($user)) {
            Follower::where('follower_id', Auth::id())
                ->where('following_id', $userId)
                ->delete();
        } else {
            Follower::updateOrCreate([
                'follower_id' => Auth::id(),
                'following_id' => $userId,
            ]);
        }
    }

    public function render()
    {
        // Unified search across users and albums
        $searchTerm = trim($this->searchQuery);

        $users = User::where('id', '!=', Auth::id())
            ->when($searchTerm, fn($query) =>
                $query->where('name', 'like', "%{$searchTerm}%")
                    ->orWhere('username', 'like', "%{$searchTerm}%")
            )
            ->orderByDesc('created_at')
            ->limit(12)
            ->get();

        $albums = Album::where('visibility', 'public')
            ->when($searchTerm, fn($query) =>
                $query->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('description', 'like', "%{$searchTerm}%")
                    ->orWhere('category', 'like', "%{$searchTerm}%")
            )
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        return view('livewire.explore', [
            'users' => $users,
            'albums' => $albums,
            'activeTab' => $this->activeTab,
        ]);
    }
}
