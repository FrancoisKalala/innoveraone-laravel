<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\User;

class FollowerUser extends Component
{
    public $follower;
    public $mutualFollowers;
    public $activeTab = 'followers';
    public $followersSearch = '';
    public $followingSearch = '';

    public function mount($id)
    {
        $this->follower = User::findOrFail($id);
        $this->mutualFollowers = $this->follower->mutualFollowers();
    }

    public function toggleFollow($userId)
    {
        $authUser = auth()->user();
        $targetUser = User::find($userId);
        if (!$targetUser || !$authUser) return;

        // Check if already following
        $isFollowing = $authUser->following()->where('following_id', $userId)->exists();
        if ($isFollowing) {
            $authUser->following()->where('following_id', $userId)->delete();
        } else {
            $authUser->following()->create(['following_id' => $userId]);
        }
    }

    public function getFilteredFollowersProperty()
    {
        $followers = $this->follower->followers;
        if ($this->followersSearch) {
            $followers = $followers->filter(function ($rel) {
                $user = $rel->follower;
                return $user && (stripos($user->name, $this->followersSearch) !== false || stripos($user->username, $this->followersSearch) !== false);
            });
        }
        return $followers;
    }

    public function getFilteredFollowingProperty()
    {
        $following = $this->follower->following;
        if ($this->followingSearch) {
            $following = $following->filter(function ($rel) {
                $user = $rel->following;
                return $user && (stripos($user->name, $this->followingSearch) !== false || stripos($user->username, $this->followingSearch) !== false);
            });
        }
        return $following;
    }

    public function render()
    {
        return view('livewire.profile.follower-user');
    }
}
