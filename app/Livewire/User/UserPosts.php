<?php

namespace App\Livewire\User;

use App\Models\User;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class UserPosts extends Component
{
    use WithPagination;

    public $userId;
    public $user;
    public $perPage = 15;
    public $hasMore = true;
    public $offset = 0;

    public function mount($user)
    {
        $this->userId = $user;
        $this->user = User::findOrFail($user);
    }

    public function loadMore()
    {
        $this->offset += $this->perPage;
    }

    public function render()
    {
        $posts = Post::where('user_id', $this->userId)
            ->where('already_deleted', false)
            ->where('already_expired', false)
            ->with(['user', 'album', 'likes', 'comments', 'media'])
            ->orderByDesc('created_at')
            ->offset($this->offset)
            ->limit($this->perPage)
            ->get();
        $this->hasMore = $posts->count() === $this->perPage;
        return view('livewire.user.user-posts', [
            'user' => $this->user,
            'posts' => $posts,
            'hasMore' => $this->hasMore,
        ]);
    }
}
