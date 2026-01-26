<?php

namespace App\Livewire\Post;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class ScheduledPosts extends Component
{
    use WithPagination;

    public $showDeleteModal = false;
    public $postToDelete = null;

    public function openEditModal($postId)
    {
        $this->dispatch('openPostEditModal', postId: $postId);
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
        if ($post) {
            $post->delete();
            session()->flash('success', 'Scheduled post permanently deleted');
        }
        $this->closeDeleteModal();
    }

    public function render()
    {
        $scheduledPosts = Post::where('user_id', auth()->id())
            ->where('status', 'scheduled')
            ->where('already_deleted', false)
            ->where('already_expired', false)
            ->whereNotNull('publish_at')
            ->where('publish_at', '>', now())
            ->with(['user', 'album', 'media'])
            ->orderBy('publish_at')
            ->paginate(10);

        return view('livewire.post.scheduled-posts', [
            'scheduledPosts' => $scheduledPosts,
            'showDeleteModal' => $this->showDeleteModal,
            'postToDelete' => $this->postToDelete,
        ]);
    }
}
