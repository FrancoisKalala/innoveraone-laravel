<?php

namespace App\Livewire\Contact;

use App\Models\Contact;
use App\Models\User;
use Livewire\Component;

class ContactDetail extends Component
{
    public $contacts = [];
    public $selectedContactId = null;
    public $selectedContact = null;
    public $searchQuery = '';
    public $recentSearches = [];
    public $showSearchModal = false;
    public $searchResults = [];

    public function mount()
    {
        $this->recentSearches = session()->get($this->recentSearchSessionKey(), []);
        $this->loadContacts();
    }

    public function useRecentSearch($index)
    {
        if (!isset($this->recentSearches[$index])) {
            return;
        }
        $term = $this->recentSearches[$index];
        $this->searchQuery = $term;
        $this->addRecentSearch($term);
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
        $userId = auth()->id();
        return 'recent_searches_contacts_' . ($userId ?: 'guest');
    }

    public function loadContacts()
    {
        $userId = auth()->id();

        $this->contacts = Contact::where(function($query) use ($userId) {
            $query->where('user_id', $userId)
                  ->orWhere('contact_id', $userId);
        })
        ->where('status', 'accepted')
        ->with(['user', 'contact'])
        ->get()
        ->map(function ($contact) use ($userId) {
            return $contact->user_id === $userId ? $contact->contact : $contact->user;
        })
        ->unique('id')
        ->values();
    }

    public function selectContact($contactId)
    {
        $this->selectedContactId = $contactId;
        $this->selectedContact = User::find($contactId);
    }

    public function openSearchModal()
    {
        $this->showSearchModal = true;
    }

    public function closeSearchModal()
    {
        $this->showSearchModal = false;
        $this->searchQuery = '';
        $this->searchResults = [];
    }

    public function updatedSearchQuery()
    {
        if (strlen($this->searchQuery) < 2) {
            $this->searchResults = [];
            return;
        }

        $this->addRecentSearch($this->searchQuery);

        $this->searchResults = User::where(function ($query) {
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
        $this->closeSearchModal();
    }

    public function startConversation()
    {
        if (!$this->selectedContactId) {
            return;
        }

        $this->dispatch('start-conversation', contactId: $this->selectedContactId);
    }

    public function removeContact($contactId)
    {
        Contact::where(function($query) use ($contactId) {
            $query->where('user_id', auth()->id())
                  ->where('contact_id', $contactId);
        })
        ->orWhere(function($query) use ($contactId) {
            $query->where('contact_id', auth()->id())
                  ->where('user_id', $contactId);
        })
        ->delete();

        $this->selectedContactId = null;
        $this->selectedContact = null;
        $this->loadContacts();
        session()->flash('success', 'Contact removed successfully!');
    }

    public function blockContact($contactId)
    {
        // Add block logic if needed
        $this->removeContact($contactId);
    }

    public function render()
    {
        return view('livewire.contact.contact-detail');
    }
}
