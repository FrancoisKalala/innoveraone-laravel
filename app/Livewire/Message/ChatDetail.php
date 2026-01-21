<?php

namespace App\Livewire\Message;

use App\Models\Message;
use App\Models\MessageConversation;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;

class ChatDetail extends Component
{
    public $conversations = [];
    public $selectedConversationId = null;
    public $selectedConversation = null;
    public $messages = [];
    public $messageContent = '';
    public $searchQuery = '';
    public $showNewMessageModal = false;
    public $newContactSearch = '';
    public $availableContacts = [];
    public $recipientSearch = '';
    public $recipientResults = [];

    public function mount()
    {
        $this->loadConversations();
    }

    #[On('start-conversation')]
    public function handleStartConversation($contactId)
    {
        $this->startConversation($contactId);
    }

    public function loadConversations()
    {
        $userId = auth()->id();
        
        // Get all conversations for the user (messages they sent or received)
        $this->conversations = Message::where(function($query) use ($userId) {
            $query->where('sender_id', $userId)
                  ->orWhere('receiver_id', $userId);
        })
        ->with(['sender', 'receiver'])
        ->orderBy('created_at', 'desc')
        ->distinct()
        ->get()
        ->groupBy(function($message) use ($userId) {
            // Group by the other person in the conversation
            return $message->sender_id === $userId ? $message->receiver_id : $message->sender_id;
        })
        ->map(function($messages) use ($userId) {
            // Return the latest message for each conversation
            $latest = $messages->first();
            $otherUserId = $latest->sender_id === $userId ? $latest->receiver_id : $latest->sender_id;
            return (object)[
                'id' => $otherUserId,
                'user' => $latest->sender_id === $userId ? $latest->receiver : $latest->sender,
                'lastMessage' => $latest,
            ];
        })
        ->values();
    }

    public function updatedRecipientSearch()
    {
        if (strlen($this->recipientSearch) < 2) {
            $this->recipientResults = [];
            return;
        }

        $this->recipientResults = User::where(function ($query) {
            $query->where('name', 'like', '%' . $this->recipientSearch . '%')
                  ->orWhere('username', 'like', '%' . $this->recipientSearch . '%');
        })
        ->where('id', '!=', auth()->id())
        ->limit(10)
        ->get();
    }

    public function selectConversation($conversationId)
    {
        $this->selectedConversationId = $conversationId;
        $this->selectedConversation = User::find($conversationId);
        $this->loadMessages();
    }

    public function loadMessages()
    {
        if (!$this->selectedConversationId) {
            $this->messages = collect([]);
            return;
        }

        $userId = auth()->id();
        $conversationId = $this->selectedConversationId;
        
        // Get messages between current user and selected contact
        $this->messages = Message::where(function($query) use ($userId, $conversationId) {
            $query->where(function($q) use ($userId, $conversationId) {
                $q->where('sender_id', $userId)
                  ->where('receiver_id', $conversationId);
            })
            ->orWhere(function($q) use ($userId, $conversationId) {
                $q->where('sender_id', $conversationId)
                  ->where('receiver_id', $userId);
            });
        })
        ->orderBy('created_at', 'asc')
        ->with('sender')
        ->get();
    }

    public function sendMessage()
    {
        if (empty(trim($this->messageContent))) {
            return;
        }

        if (!$this->selectedConversationId) {
            return;
        }

        Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $this->selectedConversationId,
            'content' => $this->messageContent,
        ]);

        $this->messageContent = '';
        $this->loadMessages();
        $this->loadConversations();
    }

    public function openNewMessageModal()
    {
        $this->showNewMessageModal = true;
        $this->loadAvailableContacts();
    }

    public function closeNewMessageModal()
    {
        $this->showNewMessageModal = false;
        $this->newContactSearch = '';
        $this->availableContacts = [];
    }

    public function loadAvailableContacts()
    {
        // Load contacts that user can message
        $this->availableContacts = User::where('id', '!=', auth()->id())
            ->limit(10)
            ->get();
    }

    public function selectRecipient($contactId)
    {
        $this->startConversation($contactId);
    }

    public function startConversation($contactId)
    {
        $this->selectedConversationId = $contactId;
        $this->selectedConversation = User::find($contactId);
        $this->closeNewMessageModal();
        $this->loadConversations();
        $this->loadMessages();
    }

    public function deleteMessage($messageId)
    {
        Message::findOrFail($messageId)->delete();
        $this->loadMessages();
    }

    public function render()
    {
        return view('livewire.message.chat-detail');
    }
}
