<?php

namespace App\Livewire\Post;

use App\Models\Post;
use App\Models\ExpiredPost;
use Livewire\Component;
use Livewire\WithPagination;

class ExpiredPosts extends Component
{
    use WithPagination;

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

    public function permanentlyDelete($postId)
    {
        $post = Post::where('id', $postId)
            ->where('user_id', auth()->id())
            ->first();

        if (!$post) {
            return;
        }

        // Delete associated expired post record
        ExpiredPost::where('post_id', $postId)->delete();

        // Delete the post
        $post->delete();

        session()->flash('success', 'Post permanently deleted');
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
        ]);
    }
}
