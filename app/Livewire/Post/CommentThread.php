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
    public $replyContent = '';
    public $editContent = '';
    public $editingReplyId = null;
    public $editingReplyContent = '';
    public $showReplies = false;
    public $replies;
    public $likeCount;
    public $dislikeCount;
    public $isLiked = false;
    public $isDisliked = false;
    public $isEditing = false;
    public $isDeleted = false;

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
            'replyContent' => 'required|string|min:1|max:1000',
        ]);

        CommentAnswer::create([
            'comment_id' => $this->comment->id,
            'user_id' => auth()->id(),
            'content' => $this->replyContent,
        ]);

        $this->replies = $this->comment->answers()->latest()->get();
        $this->replyContent = '';
        $this->showReplies = true;
    }

    public function startReplyEdit($replyId)
    {
        $reply = $this->comment->answers()->where('id', $replyId)->first();

        if (!$reply || $reply->user_id !== auth()->id()) {
            return;
        }

        $this->editingReplyId = $replyId;
        $this->editingReplyContent = $reply->content;
        $this->showReplies = true;
    }

    public function cancelReplyEdit()
    {
        $this->editingReplyId = null;
        $this->editingReplyContent = '';
    }

    public function updateReply()
    {
        if (!$this->editingReplyId) {
            return;
        }

        $this->validate([
            'editingReplyContent' => 'required|string|min:1|max:1000',
        ]);

        $reply = $this->comment->answers()->where('id', $this->editingReplyId)->first();

        if (!$reply || $reply->user_id !== auth()->id()) {
            return;
        }

        $reply->update([
            'content' => $this->editingReplyContent,
        ]);

        $this->replies = $this->comment->answers()->latest()->get();
        $this->editingReplyId = null;
        $this->editingReplyContent = '';
    }

    public function deleteReply($replyId)
    {
        $reply = $this->comment->answers()->where('id', $replyId)->first();

        if (!$reply || $reply->user_id !== auth()->id()) {
            return;
        }

        $reply->delete();
        $this->replies = $this->comment->answers()->latest()->get();

        if ($this->editingReplyId === $replyId) {
            $this->cancelReplyEdit();
        }
    }

    public function startEdit()
    {
        if ($this->comment->user_id !== auth()->id()) {
            return;
        }

        $this->editContent = $this->comment->content;
        $this->isEditing = true;
    }

    public function cancelEdit()
    {
        $this->isEditing = false;
        $this->editContent = '';
    }

    public function updateComment()
    {
        if ($this->comment->user_id !== auth()->id()) {
            return;
        }

        $this->validate([
            'editContent' => 'required|string|min:1|max:1000',
        ]);

        $this->comment->update([
            'content' => $this->editContent,
        ]);

        $this->isEditing = false;
    }

    public function deleteComment()
    {
        if ($this->comment->user_id !== auth()->id()) {
            return;
        }

        $this->comment->delete();
        $this->isDeleted = true;
        $this->dispatch('commentDeleted', commentId: $this->comment->id, postId: $this->comment->post_id);
    }

    public function render()
    {
        return view('livewire.post.comment-thread');
    }
}
