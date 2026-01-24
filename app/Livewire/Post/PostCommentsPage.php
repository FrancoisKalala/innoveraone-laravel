<?php

namespace App\Livewire\Post;

use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class PostCommentsPage extends Component
{
    protected $listeners = ['commentLikeUpdated' => 'refreshComments'];
    public function refreshComments()
    {
        // This will force Livewire to re-render and reload the comments list
    }
    protected $updatesQueryString = ['commentView', 'commentKeyword'];
    use WithPagination;

    public Post $post;
    public $commentView = 'all';
    public $commentKeyword = '';
    protected $paginationTheme = 'tailwind';

    public function mount(Post $post)
    {
        $this->post = $post;
    }

    public function render()
    {
        $query = $this->post->comments();

        // Ensure likes_count is available for sorting

        if ($this->commentView === 'pinned') {
            $query->where('is_pinned', true)->orderByDesc('created_at');
        } elseif ($this->commentView === 'highlighted') {
            $query->where('is_highlighted', true)->orderByDesc('created_at');
        } elseif ($this->commentView === 'mine') {
            $userId = Auth::id();
            if ($userId) {
                $query->where('user_id', $userId)->orderByDesc('created_at');
            } else {
                $query->whereRaw('1 = 0'); // No comments if not logged in
            }
        } elseif ($this->commentView === 'keyword' && $this->commentKeyword) {
            $query->where('content', 'like', '%' . $this->commentKeyword . '%')->orderByDesc('created_at');
            $this->resetPage();
        } elseif ($this->commentView === 'most_liked') {
            $query->orderByDesc('likes_count')->orderByDesc('created_at');
        } elseif ($this->commentView === 'most_replied') {
            $query->withCount('answers')->orderByDesc('answers_count');
        } elseif ($this->commentView === 'oldest') {
            $query->orderBy('created_at');
        } else {
            $query->orderByDesc('is_pinned')
                  ->orderByDesc('is_highlighted')
                  ->orderByDesc('created_at');
        }

        $comments = $query->paginate(10);

        return view('livewire.post.post-comments-page', [
            'post' => $this->post,
            'comments' => $comments,
            'commentView' => $this->commentView,
            'commentKeyword' => $this->commentKeyword,
        ]);
    }
}
