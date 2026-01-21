<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;

class Explore extends Component
{
    public $searchQuery = '';
    public $categoryFilter = '';
    public $followingIds = [];

    public function mount()
    {
        $this->followingIds = auth()->user()->following()->pluck('id')->toArray();
    }

    #[On('user-followed')]
    public function updateFollowing($userId)
    {
        if (! in_array($userId, $this->followingIds)) {
            $this->followingIds[] = $userId;
        }
    }

    #[On('user-unfollowed')]
    public function removeFollowing($userId)
    {
        $this->followingIds = array_filter($this->followingIds, fn($id) => $id !== $userId);
    }

    public function toggleFollow($userId)
    {
        $user = User::find($userId);
        if (! $user) return;

        if (in_array($userId, $this->followingIds)) {
            auth()->user()->following()->detach($userId);
            $this->removeFollowing($userId);
        } else {
            auth()->user()->following()->attach($userId);
            $this->updateFollowing($userId);
        }
    }

    public function render()
    {
        $users = User::where('id', '!=', auth()->id())
            ->when($this->searchQuery, fn($query) =>
                $query->where('name', 'like', "%{$this->searchQuery}%")
                    ->orWhere('username', 'like', "%{$this->searchQuery}%")
            )
            ->orderByDesc('created_at')
            ->limit(12)
            ->get();

        $albums = \App\Models\Album::where('visibility', 'public')
            ->when($this->categoryFilter, fn($query) =>
                $query->where('category', $this->categoryFilter)
            )
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        return view('livewire.explore', [
            'users' => $users,
            'albums' => $albums,
        ]);
    }
}
