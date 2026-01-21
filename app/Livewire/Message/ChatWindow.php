<?php

namespace App\Livewire\Message;

use App\Models\Message;
use App\Models\MessageConversation;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ChatWindow extends Component
{
    use WithPagination;

    public $contact_id;
    public $contact;
    public $messageContent = '';
    public $messages;
    public $conversations;
    public $selectedConversation = null;

    public function mount()
    {
        $this->loadConversations();
    }

    public function loadConversations()
    {
        $this->conversations = MessageConversation::where('user_id', auth()->id())
            ->with('contact')
            ->orderBy('updated_at', 'desc')
            ->get();
    }

    public function selectConversation($contactId)
    {
        $this->contact_id = $contactId;
        $this->contact = User::find($contactId);
        $this->selectedConversation = $contactId;
        $this->loadMessages();
    }

    public function loadMessages()
    {
        if (!$this->contact_id) {
            $this->messages = collect([]);
            return;
        }

        $this->messages = Message::where(function ($query) {
            $query->where('sender_id', auth()->id())
                  ->where('receiver_id', $this->contact_id);
        })->orWhere(function ($query) {
            $query->where('sender_id', $this->contact_id)
                  ->where('receiver_id', auth()->id());
        })
        ->orderBy('created_at', 'asc')
        ->get();

        Message::where('sender_id', $this->contact_id)
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
    }

    public function sendMessage()
    {
        $this->validate([
            'messageContent' => 'required|string|min:1|max:5000',
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $this->contact_id,
            'content' => $this->messageContent,
            'is_read' => false,
        ]);

        MessageConversation::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'contact_id' => $this->contact_id,
            ],
            ['last_message_id' => $message->id]
        );

        $this->messageContent = '';
        $this->loadMessages();
        $this->dispatch('messageSent', messageId: $message->id);
    }

    public function deleteMessage($messageId)
    {
        $message = Message::find($messageId);

        if ($message && $message->sender_id === auth()->id()) {
            $message->delete();
            $this->loadMessages();
            session()->flash('success', 'Message deleted!');
        }
    }

    public function render()
    {
        return view('livewire.message.chat-window');
    }
}
