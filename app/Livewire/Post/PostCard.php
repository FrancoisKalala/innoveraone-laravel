<?php

namespace App\Livewire\Post;

use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use App\Models\ExpiredPost;
use App\Models\Follower;
use Livewire\Component;

class PostCard extends Component
{
    public $commentView = 'all'; // all, pinned, highlighted, mine, keyword, most_liked, most_replied, oldest
    public $commentKeyword = '';
    public $reactions = [];
    public Post $post;
    public $likeCount;
    public $commentCount;
    public $isLiked;
    public $showComments = false;
    public $showDeleteModal = false;
    public $isFollowing = false;
    public $newComment = '';
    public $shareCount = 0;
    public $showShareOptions = false;
    public $shareMessage = '';
    public $showForwardOptions = false;

    protected $listeners = ['commentDeleted' => 'handleCommentDeleted'];

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
        $this->isFollowing = auth()->check() ? auth()->user()->isFollowing($this->post->user) : false;
        $this->shareCount = $this->post->shares ?? 0;
        // Load emoji reactions for this post
        $this->reactions = $this->post->reactions()
            ->select('emoji')
            ->selectRaw('count(*) as count')
            ->groupBy('emoji')
            ->pluck('count', 'emoji')
            ->toArray();
    }

    public function reactEmoji($emoji)
    {
        if (!auth()->check()) return;
        $userId = auth()->id();
        // Remove previous reaction for this user and post
        \App\Models\PostReaction::where('post_id', $this->post->id)
            ->where('user_id', $userId)
            ->delete();
        // Add new reaction
        \App\Models\PostReaction::create([
            'post_id' => $this->post->id,
            'user_id' => $userId,
            'emoji' => $emoji,
        ]);
        // Refresh reactions
        $this->reactions = $this->post->reactions()
            ->select('emoji')
            ->selectRaw('count(*) as count')
            ->groupBy('emoji')
            ->pluck('count', 'emoji')
            ->toArray();
    }

    public function toggleFollow()
    {
        if (!auth()->check() || $this->post->user_id === auth()->id()) {
            return;
        }

        if ($this->isFollowing) {
            Follower::where('follower_id', auth()->id())
                ->where('following_id', $this->post->user_id)
                ->delete();
            $this->isFollowing = false;
        } else {
            Follower::updateOrCreate([
                'follower_id' => auth()->id(),
                'following_id' => $this->post->user_id,
            ]);
            $this->isFollowing = true;
        }
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

    public function addComment()
    {
        if (!$this->canInteract() || !in_array($this->post->interaction_type, ['comment', 'like_comment', 'all'])) {
            return;
        }

        $this->validate([
            'newComment' => 'required|string|min:1|max:1000',
        ]);

        Comment::create([
            'post_id' => $this->post->id,
            'user_id' => auth()->id(),
            'content' => $this->newComment,
        ]);

        $this->newComment = '';
        $this->commentCount++;
        $this->showComments = true;
    }

    public function openEditModal()
    {
        if ($this->post->user_id !== auth()->id()) {
            return;
        }

        // Fire edit event; modal listens globally
        $this->dispatch('openPostEditModal', postId: $this->post->id);
    }

    public function openDeleteModal()
    {
        if ($this->post->user_id !== auth()->id()) {
            return;
        }

        $this->showDeleteModal = true;
    }

    public function toggleForwardOptions()
    {
        if (!auth()->check()) {
            return;
        }

        $this->showForwardOptions = !$this->showForwardOptions;
    }

    public function forwardToContact($contactId)
    {
        if (!auth()->check()) {
            return;
        }

        $contact = auth()->user()->contacts()->find($contactId);
        if (!$contact) {
            return;
        }

        // Create message with forwarded post
        \App\Models\Message::create([
            'sender_id' => auth()->id(),
            'recipient_id' => $contact->id,
            'content' => 'Forwarded a post',
            'is_forwarded_post' => true,
            'forwarded_post_id' => $this->post->id,
        ]);

        $this->showForwardOptions = false;
        $this->dispatch('postForwarded', postId: $this->post->id);
    }

    public function closeDeleteModal()
    {
        $this->showDeleteModal = false;
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
        $this->closeDeleteModal();
        $this->dispatch('postDeleted', postId: $this->post->id);
    }

    public function handleCommentDeleted($commentId, $postId)
    {
        if ($postId !== $this->post->id || $this->commentCount === 0) {
            return;
        }

        $this->commentCount--;
    }

    public function toggleShareOptions()
    {
        $this->showShareOptions = !$this->showShareOptions;
    }

    public function sharePost($method = 'link')
    {
        if (!$this->canInteract()) {
            return;
        }

        // Increment share count
        $this->post->increment('shares');
        $this->shareCount++;

        // Log share activity
        activity()
            ->performedOn($this->post)
            ->causedBy(auth()->user())
            ->withProperties(['method' => $method])
            ->log('shared');

        // Set success message based on method
        $messages = [
            'link' => '🔗 Link copied to clipboard!',
            'facebook' => '📘 Opening Facebook...',
            'twitter' => '🐦 Opening Twitter...',
            'linkedin' => '💼 Opening LinkedIn...',
            'whatsapp' => '💚 Opening WhatsApp...',
        ];

        $this->shareMessage = $messages[$method] ?? '✅ Shared successfully!';
        $this->showShareOptions = false;

        // Dispatch event for feed refresh
        $this->dispatch('postShared', postId: $this->post->id);

        // Clear message after 3 seconds
        $this->dispatch('clearShareMessage');
    }

    public function copyShareLink()
    {
        $this->sharePost('link');
    }

    public function render()
    {
        $query = $this->post->comments();

        // Combined filter/sort logic
        if ($this->commentView === 'pinned') {
            $query->where('is_pinned', true)->orderByDesc('created_at');
        } elseif ($this->commentView === 'highlighted') {
            $query->where('is_highlighted', true)->orderByDesc('created_at');
        } elseif ($this->commentView === 'mine') {
            $query->where('user_id', auth()->id())->orderByDesc('created_at');
        } elseif ($this->commentView === 'keyword' && $this->commentKeyword) {
            $query->where('content', 'like', '%' . $this->commentKeyword . '%')->orderByDesc('created_at');
        } elseif ($this->commentView === 'most_liked') {
            $query->orderByDesc('likes_count');
        } elseif ($this->commentView === 'most_replied') {
            $query->withCount('answers')->orderByDesc('answers_count');
        } elseif ($this->commentView === 'oldest') {
            $query->orderBy('created_at');
        } else {
            $query->orderByDesc('is_pinned')
                  ->orderByDesc('is_highlighted')
                  ->orderByDesc('created_at');
        }

        $comments = $this->showComments ? $query->take(5)->get() : collect();

        return view('livewire.post.post-card', [
            'user' => $this->post->user,
            'files' => $this->post->files,
            'comments' => $comments,
        ]);
    }
}
