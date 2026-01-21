<?php

namespace App\Livewire\Contact;

use App\Models\Contact;
use App\Models\ContactBlock;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ContactManager extends Component
{
    use WithPagination;

    public $searchQuery = '';
    public $contactRequests;
    public $myContacts;
    public $filteredUsers = [];

    public function mount()
    {
        $this->loadContacts();
    }

    public function updatedSearchQuery()
    {
        $this->searchUsers();
    }

    public function loadContacts()
    {
        $userId = auth()->id();
        $this->contactRequests = Contact::where('contact_id', $userId)
            ->where('status', 'pending')
            ->with('user')
            ->get();

        $this->myContacts = Contact::where('user_id', $userId)
            ->orWhere('contact_id', $userId)
            ->where('status', 'accepted')
            ->with(['user', 'contact'])
            ->get()
            ->map(function ($contact) use ($userId) {
                return $contact->user_id === $userId ? $contact->contact : $contact->user;
            });
    }

    public function searchUsers()
    {
        if (strlen($this->searchQuery) < 2) {
            $this->filteredUsers = [];
            return;
        }

        $this->filteredUsers = User::where(function ($query) {
            $query->where('name', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('username', 'like', '%' . $this->searchQuery . '%');
        })
        ->where('id', '!=', auth()->id())
        ->limit(10)
        ->get();
    }

    public function sendContactRequest($userId)
    {
        Contact::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'contact_id' => $userId,
            ],
            ['status' => 'pending']
        );

        session()->flash('success', 'Contact request sent!');
        $this->searchQuery = '';
        $this->filteredUsers = [];
    }

    public function acceptContactRequest($contactId)
    {
        $contact = Contact::where('contact_id', auth()->id())
            ->where('user_id', $contactId)
            ->first();

        if ($contact) {
            $contact->accept();
            $this->loadContacts();
            session()->flash('success', 'Contact request accepted!');
        }
    }

    public function refuseContactRequest($contactId)
    {
        $contact = Contact::where('contact_id', auth()->id())
            ->where('user_id', $contactId)
            ->first();

        if ($contact) {
            $contact->refuse();
            $this->loadContacts();
            session()->flash('success', 'Contact request refused!');
        }
    }

    public function blockUser($userId)
    {
        ContactBlock::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'blocked_user_id' => $userId,
            ],
            [
                'can_message' => false,
                'can_like' => false,
                'can_comment' => false,
            ]
        );

        $this->loadContacts();
        session()->flash('success', 'User blocked!');
    }

    public function unblockUser($userId)
    {
        ContactBlock::where('user_id', auth()->id())
            ->where('blocked_user_id', $userId)
            ->delete();

        $this->loadContacts();
        session()->flash('success', 'User unblocked!');
    }

    public function render()
    {
        return view('livewire.contact.contact-manager');
    }
}
