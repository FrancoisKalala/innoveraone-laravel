<?php

namespace App\Livewire\Post;

use App\Models\Post;
use App\Models\ExpiredPost;
use Livewire\Component;
use Livewire\WithPagination;

class ExpiredPosts extends Component
{
    use WithPagination;

    public $showDeleteModal = false;
    public $postToDelete = null;

    public function restorePost($postId)
    {
        $post = Post::where('id', $postId)
            ->where('user_id', auth()->id())
            ->first();

        if (!$post) {
            return;
        }

        $post->update(['already_deleted' => false]);
        ExpiredPost::where('post_id', $postId)->delete();

        session()->flash('success', 'Post restored successfully');
    }

    public function openDeleteModal($postId)
    {
        $this->postToDelete = $postId;
        $this->showDeleteModal = true;
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
        $this->postToDelete = null;
    }

    public function permanentlyDelete()
    {
        $post = Post::where('id', $this->postToDelete)
            ->where('user_id', auth()->id())
            ->first();

        if (!$post) {
            return;
        }

        // Delete associated expired post record
        ExpiredPost::where('post_id', $this->postToDelete)->delete();

        // Delete the post
        $post->delete();

        session()->flash('success', 'Post permanently deleted');
        $this->closeDeleteModal();
    }

    public function render()
    {
        $expiredPosts = Post::where('user_id', auth()->id())
            ->where('already_deleted', true)
            ->with(['user', 'album', 'media'])
            ->orderByDesc('updated_at')
            ->paginate(10);

        return view('livewire.post.expired-posts', [
            'expiredPosts' => $expiredPosts,
            'showDeleteModal' => $this->showDeleteModal,
            'postToDelete' => $this->postToDelete,
        ]);
    }
}
