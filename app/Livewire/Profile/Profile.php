<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\User;

class Profile extends Component
{
    public User $user;

    public function mount()
    {
        $this->user = auth()->user();
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
        ]);
    }
}
