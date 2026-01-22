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
        // Perform search if query exists
        $searchResults = collect([]);
        $searchTerm = trim($this->searchQuery);

        if (strlen($searchTerm) >= 2) {
            $searchPattern = '%' . $searchTerm . '%';
            $userId = auth()->id();

            // Get my contacts
            $myContactIds = Contact::where(function($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->orWhere('contact_id', $userId);
            })
            ->where('status', 'accepted')
            ->get()
            ->map(function ($contact) use ($userId) {
                return $contact->user_id === $userId ? $contact->contact_id : $contact->user_id;
            })
            ->toArray();

            // Get sent request IDs
            $sentRequestIds = Contact::where('user_id', $userId)
                ->where('status', 'pending')
                ->pluck('contact_id')
                ->toArray();

            // Get received request IDs
            $receivedRequestIds = Contact::where('contact_id', $userId)
                ->where('status', 'pending')
                ->pluck('user_id')
                ->toArray();

            // Search in my contacts first
            $myContactsResults = collect([]);
            if (count($myContactIds) > 0) {
                $myContactsResults = User::whereIn('id', $myContactIds)
                    ->where(function ($query) use ($searchPattern) {
                        $query->where('name', 'like', $searchPattern)
                              ->orWhere('username', 'like', $searchPattern);
                    })
                    ->get()
                    ->map(function($user) {
                        $user->category = 'My Contact';
                        return $user;
                    });
            }

            // Search in sent requests
            $sentRequestsResults = collect([]);
            if (count($sentRequestIds) > 0) {
                $sentRequestsResults = User::whereIn('id', $sentRequestIds)
                    ->where(function ($query) use ($searchPattern) {
                        $query->where('name', 'like', $searchPattern)
                              ->orWhere('username', 'like', $searchPattern);
                    })
                    ->get()
                    ->map(function($user) {
                        $user->category = 'Sent Request';
                        return $user;
                    });
            }

            // Search in received requests
            $receivedRequestsResults = collect([]);
            if (count($receivedRequestIds) > 0) {
                $receivedRequestsResults = User::whereIn('id', $receivedRequestIds)
                    ->where(function ($query) use ($searchPattern) {
                        $query->where('name', 'like', $searchPattern)
                              ->orWhere('username', 'like', $searchPattern);
                    })
                    ->get()
                    ->map(function($user) {
                        $user->category = 'Received Request';
                        return $user;
                    });
            }

            // Get all excluded IDs (contacts + requests + current user)
            $excludedIds = array_merge($myContactIds, $sentRequestIds, $receivedRequestIds, [$userId]);

            // Search in all other users
            $otherUsersResults = User::whereNotIn('id', $excludedIds)
                ->where(function ($query) use ($searchPattern) {
                    $query->where('name', 'like', $searchPattern)
                          ->orWhere('username', 'like', $searchPattern);
                })
                ->limit(10)
                ->get()
                ->map(function($user) {
                    $user->category = 'Other User';
                    return $user;
                });

            // Combine results in priority order
            $searchResults = $myContactsResults
                ->concat($sentRequestsResults)
                ->concat($receivedRequestsResults)
                ->concat($otherUsersResults)
                ->take(20)
                ->values();
        }

        return view('livewire.contact.contacts-manager', [
            'myContacts' => $this->getMyContacts(),
            'sentRequests' => $this->getSentRequests(),
            'receivedRequests' => $this->getReceivedRequests(),
            'searchResults' => $searchResults,
        ]);
    }
}
