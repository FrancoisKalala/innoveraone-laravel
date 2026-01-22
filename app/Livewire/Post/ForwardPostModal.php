<?php

namespace App\Livewire\Post;

use App\Models\Post;
use App\Models\Message;
use Livewire\Component;

class ForwardPostModal extends Component
{
    public $show = false;
    public ?Post $post = null;
    public $selectedContacts = [];
    public $contacts = [];
    public $searchTerm = '';
    public $forwardMessage = '';
    public $isForwarding = false;

    protected $listeners = ['openPostForwardModal' => 'openForward'];

    public function openForward(int $postId): void
    {
        $post = Post::find($postId);

        if (!$post || !auth()->check()) {
            return;
        }

        $this->post = $post;
        $this->selectedContacts = [];
        $this->forwardMessage = '';
        $this->loadContacts();
        $this->show = true;
    }

    public function loadContacts(): void
    {
        $user = auth()->user();
        
        // Get contacts (excluding deleted ones)
        $this->contacts = $user->contacts()
            ->wherePivot('is_deleted', false)
            ->get()
            ->map(function ($contact) {
                return [
                    'id' => $contact->id,
                    'name' => $contact->name,
                    'username' => $contact->username ?? strtolower(str_replace(' ', '', $contact->name)),
                ];
            })
            ->toArray();
    }

    public function toggleContact($contactId): void
    {
        if (in_array($contactId, $this->selectedContacts)) {
            $this->selectedContacts = array_filter(
                $this->selectedContacts,
                fn($id) => $id !== $contactId
            );
        } else {
            $this->selectedContacts[] = $contactId;
        }
    }

    public function forwardPost(): void
    {
        if (!$this->post || empty($this->selectedContacts)) {
            return;
        }

        $this->isForwarding = true;

        try {
            $postUrl = route('post.show', $this->post->id);
            $forwardContent = "Check out this post:\n\n{$this->post->content}\n\n{$postUrl}";

            if ($this->forwardMessage) {
                $forwardContent = "{$this->forwardMessage}\n\n---\n\n{$forwardContent}";
            }

            foreach ($this->selectedContacts as $contactId) {
                Message::create([
                    'sender_id' => auth()->id(),
                    'receiver_id' => $contactId,
                    'content' => $forwardContent,
                    'is_forwarded_post' => true,
                    'forwarded_post_id' => $this->post->id,
                ]);
            }

            $this->dispatch('notify', type: 'success', message: 'Post forwarded successfully!');
            $this->close();
        } catch (\Exception $e) {
            $this->dispatch('notify', type: 'error', message: 'Failed to forward post: ' . $e->getMessage());
        } finally {
            $this->isForwarding = false;
        }
    }

    public function close(): void
    {
        $this->show = false;
        $this->post = null;
        $this->selectedContacts = [];
        $this->forwardMessage = '';
    }

    public function render()
    {
        $filteredContacts = $this->searchTerm
            ? array_filter(
                $this->contacts,
                fn($contact) => str_contains(
                    strtolower($contact['name'] . ' ' . $contact['username']),
                    strtolower($this->searchTerm)
                )
            )
            : $this->contacts;

        return view('livewire.post.forward-post-modal', [
            'filteredContacts' => $filteredContacts,
        ]);
    }
}
