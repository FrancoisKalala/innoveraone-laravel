<?php

namespace App\Livewire\Album;

use Livewire\Component;
use App\Models\User;

class UserAlbums extends Component
{
    public User $user;

    public function mount(User $user)
    {
        $this->user = $user;
    }

    public function render()
    {
        $albums = $this->user->albums()->with('posts')->withCount('posts')->get();
        $unviewedCounts = [];
        $userId = auth()->id();
        foreach ($albums as $album) {
            $postIds = $album->posts->pluck('id');
            $viewedPostIds = \DB::table('album_post_views')
                ->where('album_id', $album->id)
                ->where('user_id', $userId)
                ->pluck('post_id');
            $unviewedCounts[$album->id] = $postIds->diff($viewedPostIds)->count();
        }
        return view('livewire.album.user-albums', [
            'user' => $this->user,
            'albums' => $albums,
            'unviewedCounts' => $unviewedCounts,
        ]);
    }
}
