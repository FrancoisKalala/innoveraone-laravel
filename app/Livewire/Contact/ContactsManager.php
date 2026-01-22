<?php

namespace App\Livewire\Contact;

use App\Models\Contact;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ContactsManager extends Component
{
    use WithPagination;

    public $searchQuery = '';
    public $activeTab = 'my-contacts'; // my-contacts, sent-requests, received-requests
    public $recentSearches = [];

    public function mount()
    {
        $this->recentSearches = session()->get($this->recentSearchSessionKey(), []);
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
        return 'recent_searches_contacts_manager_' . ($userId ?: 'guest');
    }

    public function getMyContacts()
    {
        return Contact::where(function($query) {
            $query->where('user_id', auth()->id())
                  ->orWhere('contact_id', auth()->id());
        })
        ->where('status', 'accepted')
        ->with(['user', 'contact'])
        ->get()
        ->map(function ($contact) {
            return $contact->user_id === auth()->id() ? $contact->contact : $contact->user;
        })
        ->unique('id')
        ->values();
    }

    public function getSentRequests()
    {
        return Contact::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->with('contact')
            ->get()
            ->map(fn($c) => $c->contact);
    }

    public function getReceivedRequests()
    {
        return Contact::where('contact_id', auth()->id())
            ->where('status', 'pending')
            ->with('user')
            ->get()
            ->map(fn($c) => $c->user);
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

        $this->searchQuery = '';
        session()->flash('success', 'Contact request sent!');
    }

    public function acceptContactRequest($userId)
    {
        $contact = Contact::where('contact_id', auth()->id())
            ->where('user_id', $userId)
            ->first();

        if ($contact) {
            $contact->accept();
            session()->flash('success', 'Contact request accepted!');
        }
    }

    public function refuseContactRequest($userId)
    {
        $contact = Contact::where('contact_id', auth()->id())
            ->where('user_id', $userId)
            ->first();

        if ($contact) {
            $contact->refuse();
            session()->flash('success', 'Contact request refused!');
        }
    }

    public function removeContact($contactId)
    {
        $contact = Contact::where(function($query) use ($contactId) {
            $query->where('user_id', auth()->id())->where('contact_id', $contactId)
                  ->orWhere('user_id', $contactId)->where('contact_id', auth()->id());
        })
        ->first();

        if ($contact) {
            $contact->delete();
            session()->flash('success', 'Contact removed!');
        }
    }

    public function shareContact($contactId)
    {
        session()->flash('success', 'Contact shared successfully!');
    }

    public function cancelSentRequest($contactId)
    {
        $contact = Contact::where('user_id', auth()->id())
            ->where('contact_id', $contactId)
            ->where('status', 'pending')
            ->first();

        if ($contact) {
            $contact->delete();
            session()->flash('success', 'Request cancelled!');
        }
    }

    public function render()
    {
        $searchTerm = trim($this->searchQuery);

        $myContacts = $this->getMyContacts();
        $sentRequests = $this->getSentRequests();
        $receivedRequests = $this->getReceivedRequests();

        if (strlen($searchTerm) >= 1) {
            $myContacts = $myContacts->filter(function($user) use ($searchTerm) {
                return stripos($user->name, $searchTerm) !== false || stripos($user->username ?? '', $searchTerm) !== false;
            })->values();
            $sentRequests = $sentRequests->filter(function($user) use ($searchTerm) {
                return stripos($user->name, $searchTerm) !== false || stripos($user->username ?? '', $searchTerm) !== false;
            })->values();
            $receivedRequests = $receivedRequests->filter(function($user) use ($searchTerm) {
                return stripos($user->name, $searchTerm) !== false || stripos($user->username ?? '', $searchTerm) !== false;
            })->values();
        }

        return view('livewire.contact.contacts-manager', [
            'myContacts' => $myContacts,
            'sentRequests' => $sentRequests,
            'receivedRequests' => $receivedRequests,
        ]);
    }
}
