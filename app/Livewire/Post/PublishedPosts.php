<?php

namespace App\Livewire\Post;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;

class PublishedPosts extends Component
{
    use WithPagination;

    public function render()
    {
        $publishedPosts = Post::where('user_id', auth()->id())
            ->where(function($q) {
                $q->where('status', 'published')
                  ->orWhere('status', 'active');
            })
            ->where('already_deleted', false)
            ->where('already_expired', false)
            ->where(function($q) {
                $q->whereNull('publish_at')->orWhere('publish_at', '<=', now());
            })
            ->with(['user', 'album', 'media'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('livewire.post.published-posts', [
            'publishedPosts' => $publishedPosts,
        ]);
    }
}
