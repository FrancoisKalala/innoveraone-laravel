<?php

namespace App\Livewire\Message;

use App\Models\Message;
use App\Models\MessageConversation;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class ChatDetail extends Component
{
    public $editingMessageId = null;
    public $editedContent = '';
    public $showMessageInfo = null;
    public $infoMessageDetails = [];

    public function startEditMessage($messageId, $content)
    {
        $message = Message::find($messageId);
        if (!$message || (Auth::check() && Auth::id() !== $message->sender_id)) {
            return;
        }
        $this->editingMessageId = $messageId;
        $this->editedContent = $content;
    }

    public function cancelEditMessage()
    {
        $this->editingMessageId = null;
        $this->editedContent = '';
    }

    public function saveEditedMessage()
    {
        if (empty(trim($this->editedContent)) || !$this->editingMessageId) {
            return;
        }
        $message = Message::find($this->editingMessageId);
        if (!$message || (Auth::check() && Auth::id() !== $message->sender_id)) {
            return;
        }
        $message->update([
            'content' => $this->editedContent,
        ]);
        $this->cancelEditMessage();
        $this->loadMessages();
    }

    public function showMessageDetails($messageId)
    {
        $message = Message::find($messageId);
        if (!$message) {
            return;
        }
        $this->showMessageInfo = $messageId;
        $this->infoMessageDetails = [
            'id' => $message->id,
            'sender' => $message->sender->name ?? '',
            'content' => $message->content,
            'created_at' => $message->created_at->format('M d, Y - H:i:s'),
            'updated_at' => $message->updated_at->format('M d, Y - H:i:s'),
            'is_edited' => $message->updated_at && $message->updated_at->notEqualTo($message->created_at),
        ];
    }

    public function closeMessageInfo()
    {
        $this->showMessageInfo = null;
        $this->infoMessageDetails = [];
    }
    public $conversations = [];
    public $selectedConversationId = null;
    public $selectedConversation = null;
    public $messages = [];
    public $messageContent = '';
    public $searchQuery = '';
    public $recentSearches = [];
    public $showNewMessageModal = false;
    public $newContactSearch = '';
    public $availableContacts = [];
    public $recipientSearch = '';
    public $recipientResults = [];

    public function mount()
    {
        $this->recentSearches = session()->get($this->recentSearchSessionKey(), []);
        $this->loadConversations();
    }

    public function updatedSearchQuery()
    {
        if (!empty($this->searchQuery)) {
            $this->addRecentSearch($this->searchQuery);
        }
    }

    public function useRecentSearch($index)
    {
        if (!isset($this->recentSearches[$index])) {
            return;
        }
        $this->searchQuery = $this->recentSearches[$index];
        $this->loadConversations();
    }

    protected function addRecentSearch($term)
    {
        $term = trim($term);
        if (mb_strlen($term) < 2) {
            return;
        }
        $filtered = collect($this->recentSearches)
            ->filter(fn($existing) => strcasecmp($existing, $term) !== 0)
            ->values();
        $this->recentSearches = collect([$term])
            ->merge($filtered)
            ->take(10)
            ->values()
            ->toArray();
        session()->put($this->recentSearchSessionKey(), $this->recentSearches);
    }

    protected function recentSearchSessionKey(): string
    {
        $userId = Auth::id();
        return 'recent_searches_messages_' . ($userId ?: 'guest');
    }

    #[On('start-conversation')]
    public function handleStartConversation($contactId)
    {
        $this->startConversation($contactId);
    }

    public function loadConversations()
    {
        $userId = Auth::id();

        // Get all conversations for the user (messages they sent or received)
        $query = Message::where(function($query) use ($userId) {
            $query->where('sender_id', $userId)
                  ->orWhere('receiver_id', $userId);
        })
        ->with(['sender', 'receiver']);

        // Apply search filter
        if (!empty($this->searchQuery)) {
            $searchTerm = '%' . $this->searchQuery . '%';
            $query->where(function($q) use ($searchTerm, $userId) {
                // Search in message content
                $q->where('content', 'like', $searchTerm)
                  // Or search in sender/receiver names
                  ->orWhereHas('sender', function($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', $searchTerm)
                                ->orWhere('username', 'like', $searchTerm);
                  })
                  ->orWhereHas('receiver', function($userQuery) use ($searchTerm) {
                      $userQuery->where('name', 'like', $searchTerm)
                                ->orWhere('username', 'like', $searchTerm);
                  });
            });
        }

        $this->conversations = $query
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
        ->where('id', '!=', Auth::id())
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

        $userId = Auth::id();
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
            'sender_id' => Auth::id(),
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
        $this->availableContacts = User::where('id', '!=', Auth::id())
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
        if ($this->editingMessageId === $messageId) {
            $this->cancelEditMessage();
        }
        if ($this->showMessageInfo === $messageId) {
            $this->closeMessageInfo();
        }
        $this->loadMessages();
        $this->loadConversations();
        // If no messages remain, reset selected conversation to avoid 404
        if ($this->messages->isEmpty()) {
            $this->selectedConversationId = null;
            $this->selectedConversation = null;
        }
    }

    public function render()
    {
        return view('livewire.message.chat-detail');
    }
}
