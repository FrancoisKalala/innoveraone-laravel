<?php

namespace App\Livewire\Post;

use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use App\Models\ExpiredPost;
use Livewire\Component;

class PostCard extends Component
{
    public Post $post;
    public $likeCount;
    public $commentCount;
    public $isLiked;
    public $showComments = false;
    public $showEditModal = false;
    public $editContent = '';

    public function canInteract()
    {
        if ($this->post->isExpired() || $this->post->isDeleted()) {
            return false;
        }

        $blockedByCreator = auth()->user()->blockedByUsers()
            ->where('blocked_user_id', $this->post->user_id)
            ->exists();

        return !$blockedByCreator && auth()->check();
    }

    public function mount()
    {
        $this->likeCount = $this->post->likes()->count();
        $this->commentCount = $this->post->comments()->count();
        $this->isLiked = $this->post->isLikedBy(auth()->user());
    }

    public function toggleLike()
    {
        if (!$this->canInteract() || !$this->post->canLike()) {
            return;
        }

        if ($this->isLiked) {
            $this->post->likes()->where('user_id', auth()->id())->delete();
            $this->isLiked = false;
            $this->likeCount--;
        } else {
            Like::create([
                'user_id' => auth()->id(),
                'likeable_type' => Post::class,
                'likeable_id' => $this->post->id,
            ]);
            $this->isLiked = true;
            $this->likeCount++;
        }

        $this->dispatch('likeToggled', postId: $this->post->id);
    }

    public function toggleComments()
    {
        $this->showComments = !$this->showComments;
    }

    public function openEditModal()
    {
        if ($this->post->user_id !== auth()->id()) {
            return;
        }
        
        $this->editContent = $this->post->content;
        $this->showEditModal = true;
    }

    public function closeEditModal()
    {
        $this->showEditModal = false;
        $this->editContent = '';
    }

    public function updatePost()
    {
        if ($this->post->user_id !== auth()->id()) {
            return;
        }

        $this->validate([
            'editContent' => 'required|string|min:1|max:5000',
        ]);

        $this->post->update([
            'content' => $this->editContent,
        ]);

        $this->closeEditModal();
        session()->flash('success', 'Post updated successfully');
    }

    public function deletePost()
    {
        if ($this->post->user_id !== auth()->id()) {
            return;
        }

        $expiredAt = $this->post->created_at->addHours($this->post->expiration_hours ?? 24);
        ExpiredPost::updateOrCreate(
            ['post_id' => $this->post->id],
            ['expired_at' => $expiredAt]
        );
        $this->post->update(['already_deleted' => true]);
        $this->dispatch('postDeleted', postId: $this->post->id);
    }

    public function render()
    {
        return view('livewire.post.post-card', [
            'user' => $this->post->user,
            'files' => $this->post->files,
            'comments' => $this->showComments ? $this->post->comments()->latest()->paginate(10) : [],
        ]);
    }
}
