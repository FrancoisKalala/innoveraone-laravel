<?php

namespace App\Livewire\Post;

use App\Models\Comment;
use App\Models\CommentLike;
use App\Models\CommentDislike;
use App\Models\CommentAnswer;
use Livewire\Component;

class CommentThread extends Component
{
    public Comment $comment;
    public $content = '';
    public $showReplies = false;
    public $replies;
    public $likeCount;
    public $dislikeCount;
    public $isLiked = false;
    public $isDisliked = false;

    public function mount()
    {
        $this->likeCount = $this->comment->commentLikes()->count();
        $this->dislikeCount = $this->comment->commentDislikes()->count();
        $this->isLiked = $this->comment->isCommentLikedBy(auth()->user());
        $this->isDisliked = $this->comment->isDislikedBy(auth()->user());
        $this->replies = $this->comment->answers()->latest()->get();
    }

    public function toggleLike()
    {
        if ($this->isLiked) {
            $this->comment->commentLikes()
                ->where('user_id', auth()->id())
                ->delete();
            $this->isLiked = false;
            $this->likeCount--;
        } else {
            CommentLike::create([
                'comment_id' => $this->comment->id,
                'user_id' => auth()->id(),
            ]);
            $this->isLiked = true;
            $this->likeCount++;

            if ($this->isDisliked) {
                $this->comment->commentDislikes()
                    ->where('user_id', auth()->id())
                    ->delete();
                $this->isDisliked = false;
                $this->dislikeCount--;
            }
        }
    }

    public function toggleDislike()
    {
        if ($this->isDisliked) {
            $this->comment->commentDislikes()
                ->where('user_id', auth()->id())
                ->delete();
            $this->isDisliked = false;
            $this->dislikeCount--;
        } else {
            CommentDislike::create([
                'comment_id' => $this->comment->id,
                'user_id' => auth()->id(),
            ]);
            $this->isDisliked = true;
            $this->dislikeCount++;

            if ($this->isLiked) {
                $this->comment->commentLikes()
                    ->where('user_id', auth()->id())
                    ->delete();
                $this->isLiked = false;
                $this->likeCount--;
            }
        }
    }

    public function addReply()
    {
        $this->validate([
            'content' => 'required|string|min:1|max:1000',
        ]);

        CommentAnswer::create([
            'comment_id' => $this->comment->id,
            'user_id' => auth()->id(),
            'content' => $this->content,
        ]);

        $this->replies = $this->comment->answers()->latest()->get();
        $this->content = '';
        $this->showReplies = true;
    }

    public function deleteComment()
    {
        if ($this->comment->user_id !== auth()->id()) {
            return;
        }

        $this->comment->delete();
        $this->dispatch('commentDeleted', commentId: $this->comment->id);
    }

    public function render()
    {
        return view('livewire.post.comment-thread');
    }
}
