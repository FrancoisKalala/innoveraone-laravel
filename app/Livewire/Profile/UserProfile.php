<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\User;

class UserProfile extends Component
{
    public User $user;

    public function mount(User $user)
    {
        $this->user = $user;
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
        return view('livewire.profile.user-profile', [
            'user' => $this->user,
        ]);
    }
}
