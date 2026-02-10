<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\User;

class Profile extends Component
{
    public User $user;
    public $posts = [];
    public $offset = 0;
    public $limit = 10;
    public $hasMore = true;

    public function mount()
    {
        $this->user = auth()->user();
        $this->loadPosts();
    }

    public function loadMore()
    {
        $this->offset += $this->limit;
        $this->loadPosts();
    }

    protected function loadPosts()
    {
        $query = $this->user->posts()->latest();
        $posts = $query->skip($this->offset)->take($this->limit + 1)->get();
        $this->hasMore = $posts->count() > $this->limit;
        $this->posts = $posts->take($this->limit);
    }

    public function toggleFollow()
    {
        $authUser = auth()->user();
        if (!$authUser || $authUser->id === $this->user->id) return;

        $isFollowing = $authUser->following()->where('following_id', $this->user->id)->exists();
        if ($isFollowing) {
            $authUser->following()->where('following_id', $this->user->id)->delete();
        } else {
            $authUser->following()->create(['following_id' => $this->user->id]);
        }
    }

    public function render()
    {
        return view('livewire.profile.profile', [
            'user' => $this->user,
            'posts' => $this->posts,
            'hasMore' => $this->hasMore,
        ]);
    }
}
