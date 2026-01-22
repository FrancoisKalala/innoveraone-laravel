<?php

namespace App\Livewire\Profile;

use App\Models\Follower;
use App\Models\User;
use Livewire\Component;

class FollowersManager extends Component
{
    public $activeTab = 'followers';
    public $followersSearch = '';
    public $followingSearch = '';

    public function updated($property)
    {
        if (in_array($property, ['followersSearch', 'followingSearch'])) {
            if ($property === 'followersSearch') {
                $this->followersSearch = trim($this->followersSearch);
            } else {
                $this->followingSearch = trim($this->followingSearch);
            }
        }
    }

    public function getFilteredFollowersProperty()
    {
        $followers = auth()->user()->followers()->get();

        if (empty($this->followersSearch)) {
            return $followers;
        }

        return $followers->filter(function ($followerRel) {
            $user = $followerRel->follower;
            $search = strtolower($this->followersSearch);
            return strpos(strtolower($user->name), $search) !== false ||
                   strpos(strtolower($user->username ?? ''), $search) !== false;
        });
    }

    public function getFilteredFollowingProperty()
    {
        $following = auth()->user()->following()->get();

        if (empty($this->followingSearch)) {
            return $following;
        }

        return $following->filter(function ($followingRel) {
            $user = $followingRel->following;
            $search = strtolower($this->followingSearch);
            return strpos(strtolower($user->name), $search) !== false ||
                   strpos(strtolower($user->username ?? ''), $search) !== false;
        });
    }

    public function toggleFollow($userId)
    {
        $user = User::find($userId);
        
        if (!$user || auth()->id() === $user->id) {
            return;
        }

        if (auth()->user()->isFollowing($user)) {
            Follower::where('follower_id', auth()->id())
                ->where('following_id', $userId)
                ->delete();
        } else {
            Follower::updateOrCreate([
                'follower_id' => auth()->id(),
                'following_id' => $userId,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.profile.followers-manager', [
            'activeTab' => $this->activeTab,
            'followers' => $this->getFilteredFollowersProperty(),
            'following' => $this->getFilteredFollowingProperty(),
        ]);
    }
}
