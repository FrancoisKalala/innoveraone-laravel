<?php

namespace App\Livewire\Post;

use App\Models\Post;
use Livewire\Component;

class PostUpdateModal extends Component
{
    public $show = false;
    public Post $post;
    public $content;

    protected $listeners = ['openPostEditModal' => 'openModal'];

    public function mount(Post $post)
    {
        $this->post = $post;
        $this->content = $post->content;
    }

    public function openModal($postId)
    {
        if ($this->post->id == $postId) {
            $this->show = true;
            $this->content = $this->post->content;
        }
    }

    public function close()
    {
        $this->show = false;
    }

    public function update()
    {
        $this->validate([
            'content' => 'required|string|min:1|max:1000',
        ]);
        $this->post->update([
            'content' => $this->content,
        ]);
        $this->show = false;
        $this->dispatch('postUpdated', postId: $this->post->id);
    }

    public function render()
    {
        return view('livewire.post.post-update-modal');
    }
}
