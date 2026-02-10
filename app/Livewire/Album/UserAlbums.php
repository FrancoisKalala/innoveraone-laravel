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
        return view('livewire.album.user-albums', [
            'user' => $this->user,
            'albums' => $this->user->albums()->withCount('posts')->get(),
        ]);
    }
}
