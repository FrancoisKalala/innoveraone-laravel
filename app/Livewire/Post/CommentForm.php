<?php

namespace App\Livewire\Post;

use App\Models\Comment;
use App\Models\Post;
use Livewire\Component;

class CommentForm extends Component
{
    public Post $post;
    public $content = '';

    public function addComment()
    {
        $this->validate([
            'content' => 'required|string|min:1|max:1000',
        ]);

        Comment::create([
            'post_id' => $this->post->id,
            'user_id' => auth()->id(),
            'content' => $this->content,
        ]);

        $this->content = '';

        return redirect()->route('posts.comments', $this->post->id);
    }

    public function render()
    {
        return view('livewire.post.comment-form');
    }
}
