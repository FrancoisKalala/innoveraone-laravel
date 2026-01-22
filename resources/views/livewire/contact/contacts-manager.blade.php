<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-black" x-data="{ searchExpanded: false, showRecent: false }" @click.away="showRecent = false">
    <div class="px-4 py-8 mx-auto max-w-7xl">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h1 class="flex items-center gap-3 mb-2 text-4xl font-bold text-white">
                        <svg class="w-8 h-8 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                        </svg>
                        Contacts
                    </h1>
                    <p class="text-gray-400">Manage your contacts and requests</p>
                </div>
                <!-- Search Icon Button -->
                <button type="button" @click="searchExpanded = !searchExpanded; searchExpanded && $nextTick(() => $refs.contactsManagerSearch.focus())" class="flex items-center justify-center w-9 h-9 rounded-full bg-slate-700 text-blue-300 hover:bg-slate-600 hover:scale-110 transition shrink-0" aria-label="Toggle search">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="p-4 mb-6 text-green-300 border rounded-lg bg-green-600/20 border-green-500/50">
                {{ session('success') }}
            </div>
        @endif

        <!-- Collapsible Search Bar -->
        <div x-show="searchExpanded" x-transition:enter="transition-all duration-300 ease-out" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition-all duration-200 ease-in" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4" class="mb-8 relative z-50" style="display: none;">
            <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border border-blue-700/20 p-6">
                <div class="relative z-50">
                    <div class="flex items-center gap-3 px-4 py-3 rounded-xl border border-slate-600/50 bg-slate-800/70">
                        <button type="button" @click="showRecent = !showRecent; $refs.contactsManagerSearch.focus();" class="flex items-center justify-center w-9 h-9 rounded-full bg-slate-700 text-blue-300 hover:bg-slate-600 transition" aria-label="Toggle recent searches">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                        <input
                            x-ref="contactsManagerSearch"
                            @focus="showRecent = true"
                            type="text"
                            wire:model.live.debounce.500ms="searchQuery"
                            placeholder="Search users to add as contact..."
                            class="flex-1 px-3 py-2 text-sm text-white placeholder-gray-400 bg-slate-900/40 rounded-xl border border-transparent focus:border-blue-500 focus:outline-none transition"
                        >
                        @if($searchQuery)
                            <button wire:click="$set('searchQuery', '')" class="p-2 rounded-lg text-gray-400 hover:text-white hover:bg-slate-700 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        @endif
                        <button type="button" @click="searchExpanded = false" class="p-2 rounded-lg text-gray-400 hover:text-white hover:bg-slate-700 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                        </button>
                    </div>

                    <!-- Recent Searches Dropdown -->
                    @if (count($recentSearches) > 0)
                        <div x-show="showRecent" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute left-0 right-0 mt-2 bg-slate-800 border border-blue-700/30 rounded-xl shadow-2xl overflow-hidden z-[60]" style="display: none;">
                            <div class="px-4 py-2 bg-slate-900/80 border-b border-blue-700/30">
                                <p class="text-xs font-semibold text-gray-400 uppercase">Recent Searches</p>
                            </div>
                            <div class="max-h-48 overflow-y-auto">
                                @foreach ($recentSearches as $index => $recentSearch)
                                    <button wire:click="useRecentSearch({{ $index }})" class="w-full px-4 py-2.5 text-left text-sm text-gray-300 hover:bg-slate-700/70 hover:text-white transition flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        {{ $recentSearch }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Search Results -->
                @if($searchQuery && strlen($searchQuery) >= 2)
                    @if(count($searchResults) > 0)
                        <div class="mt-4 space-y-2">
                        @foreach($searchResults as $user)
                            <div class="flex items-center justify-between p-3 transition rounded-lg bg-slate-700/50 hover:bg-slate-700">
                                <div class="flex items-center flex-1 gap-3">
                                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-br from-blue-700 to-black ring-2 ring-blue-500/30">
                                        <span class="text-lg font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2">
                                            <p class="font-semibold text-white">{{ $user->name }}</p>
                                            @if($user->category === 'My Contact')
                                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-green-500/20 text-green-400 border border-green-500/30">Contact</span>
                                            @elseif($user->category === 'Sent Request')
                                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-yellow-500/20 text-yellow-400 border border-yellow-500/30">Pending</span>
                                            @elseif($user->category === 'Received Request')
                                                <span class="px-2 py-0.5 text-xs font-semibold rounded-full bg-blue-500/20 text-blue-400 border border-blue-500/30">Requesting</span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-400">{{ '@' . ($user->username ?? strtolower(str_replace(' ', '', $user->name))) }}</p>
                                    </div>
                                </div>
                                @if($user->category === 'Other User')
                                    <button
                                        type="button"
                                        wire:click="sendContactRequest({{ $user->id }})"
                                        class="px-4 py-2 font-semibold text-white transition rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 hover:shadow-lg hover:shadow-blue-500/40"
                                    >
                                        + Add
                                    </button>
                                @elseif($user->category === 'My Contact')
                                    <span class="px-4 py-2 text-sm font-semibold text-green-400">
                                        ✓ Contact
                                    </span>
                                @elseif($user->category === 'Sent Request')
                                    <span class="px-4 py-2 text-sm font-semibold text-yellow-400">
                                        ⏳ Pending
                                    </span>
                                @elseif($user->category === 'Received Request')
                                    <button
                                        type="button"
                                        wire:click="acceptContactRequest({{ $user->id }})"
                                        class="px-4 py-2 font-semibold text-white transition rounded-lg bg-gradient-to-r from-green-600 to-green-700 hover:shadow-lg hover:shadow-green-500/40"
                                    >
                                        Accept
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    @else
                        <div class="mt-4 p-4 bg-slate-700/30 rounded-lg text-center text-gray-400">
                            No users found matching "{{ $searchQuery }}"
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <!-- Tabs -->
        <div class="flex gap-2 mb-8 border-b border-blue-700/20">
            <button
                wire:click="$set('activeTab', 'my-contacts')"
                class="px-6 py-3 font-semibold {{ $activeTab === 'my-contacts' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-white' }} transition"
            >
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    My Contacts ({{ count($myContacts) }})
                </span>
            </button>
            <button
                wire:click="$set('activeTab', 'sent-requests')"
                class="px-6 py-3 font-semibold {{ $activeTab === 'sent-requests' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-white' }} transition"
            >
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M16.6915026,12.4744748 L3.50612381,13.2599618 C3.19218622,13.2599618 3.03521743,13.4170592 3.03521743,13.5741566 L1.15159189,20.0151496 C0.8376543,20.8006365 0.99,21.89 1.77946707,22.52 C2.41,22.99 3.50612381,23.1 4.13399899,22.8429026 L21.714504,14.0454487 C22.6563168,13.5741566 23.1272231,12.6315722 22.9702544,11.6889879 L4.13399899,1.16346272 C3.34915502,0.9 2.40734225,0.9 1.77946707,1.40788954 C0.994623095,2.0 0.837654326,3.0942857 1.15159189,3.88571996 L3.03521743,10.3267149 C3.03521743,10.4838122 3.19218622,10.6409097 3.50612381,10.6409097 L16.6915026,11.4263967 C16.6915026,11.4263967 17.1624089,11.4263967 17.1624089,12.0 C17.1624089,12.5591722 16.6915026,12.4744748 16.6915026,12.4744748 Z"/></svg>
                    Sent Requests ({{ count($sentRequests) }})
                </span>
            </button>
            <button
                wire:click="$set('activeTab', 'received-requests')"
                class="px-6 py-3 font-semibold {{ $activeTab === 'received-requests' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-white' }} transition"
            >
                <span class="flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h14l4 4V6c0-1.1-.9-2-2-2z"/></svg>
                    Received Requests ({{ count($receivedRequests) }})
                </span>
            </button>
        </div>

        <!-- Content -->
        <div>
            <!-- My Contacts Tab -->
            @if($activeTab === 'my-contacts')
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @forelse($myContacts as $contact)
                        <div class="overflow-hidden transition border bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl border-blue-700/20 hover:border-blue-700/40">
                            <div class="p-6">
                                <!-- Contact Avatar -->
                                <div class="flex items-center justify-center mb-4">
                                    <div class="flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-blue-700 to-black ring-4 ring-blue-500/30">
                                        <span class="text-3xl font-bold text-white">{{ substr($contact->name, 0, 1) }}</span>
                                    </div>
                                </div>

                                <!-- Contact Info -->
                                <h3 class="mb-1 text-lg font-bold text-center text-white">{{ $contact->name }}</h3>
                                <p class="mb-4 text-sm text-center text-gray-400">{{ '@' . ($contact->username ?? strtolower(str_replace(' ', '', $contact->name))) }}</p>

                                <!-- Stats -->
                                <div class="grid grid-cols-3 gap-2 py-3 mb-4 border-y border-blue-700/20">
                                    <div class="text-center">
                                        <p class="text-xl font-bold text-blue-400">{{ $contact->posts()->count() }}</p>
                                        <p class="text-xs text-gray-400">Posts</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xl font-bold text-blue-400">{{ $contact->followers()->count() }}</p>
                                        <p class="text-xs text-gray-400">Followers</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-xl font-bold text-blue-400">{{ $contact->albums()->count() }}</p>
                                        <p class="text-xs text-gray-400">Albums</p>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <button
                                    type="button"
                                    wire:click="removeContact({{ $contact->id }})"
                                    class="w-full px-4 py-2 font-semibold text-red-400 transition border rounded-lg bg-red-600/20 hover:bg-red-600/30 border-red-500/30"
                                >
                                    Remove Contact
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center col-span-full">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                            <p class="text-lg text-gray-400">No contacts yet</p>
                            <p class="text-sm text-gray-500">Search and add users to your contacts above</p>
                        </div>
                    @endforelse
                </div>
            @endif

            <!-- Sent Requests Tab -->
            @if($activeTab === 'sent-requests')
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @forelse($sentRequests as $user)
                        <div class="overflow-hidden transition border bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl border-yellow-700/20 hover:border-yellow-700/40">
                            <div class="p-6">
                                <!-- User Avatar -->
                                <div class="flex items-center justify-center mb-4">
                                    <div class="flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-blue-700 to-black ring-4 ring-yellow-500/30">
                                        <span class="text-3xl font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                </div>

                                <!-- User Info -->
                                <h3 class="mb-1 text-lg font-bold text-center text-white">{{ $user->name }}</h3>
                                <p class="mb-4 text-sm text-center text-gray-400">{{ '@' . ($user->username ?? strtolower(str_replace(' ', '', $user->name))) }}</p>

                                <!-- Status -->
                                <div class="p-3 mb-4 text-center border rounded-lg bg-yellow-600/20 border-yellow-500/30">
                                    <p class="text-sm font-semibold text-yellow-400">⏳ Request Pending</p>
                                </div>

                                <!-- Actions -->
                                <button
                                    type="button"
                                    wire:click="cancelSentRequest({{ $user->id }})"
                                    class="w-full px-4 py-2 font-semibold text-gray-400 transition border rounded-lg bg-gray-600/20 hover:bg-gray-600/30 border-gray-500/30"
                                >
                                    Cancel Request
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center col-span-full">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                            </svg>
                            <p class="text-lg text-gray-400">No pending requests</p>
                        </div>
                    @endforelse
                </div>
            @endif

            <!-- Received Requests Tab -->
            @if($activeTab === 'received-requests')
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    @forelse($receivedRequests as $user)
                        <div class="overflow-hidden transition border bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl border-green-700/20 hover:border-green-700/40">
                            <div class="p-6">
                                <!-- User Avatar -->
                                <div class="flex items-center justify-center mb-4">
                                    <div class="flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-blue-700 to-black ring-4 ring-green-500/30">
                                        <span class="text-3xl font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                </div>

                                <!-- User Info -->
                                <h3 class="mb-1 text-lg font-bold text-center text-white">{{ $user->name }}</h3>
                                <p class="mb-4 text-sm text-center text-gray-400">{{ '@' . ($user->username ?? strtolower(str_replace(' ', '', $user->name))) }}</p>

                                <!-- Status -->
                                <div class="p-3 mb-4 text-center border rounded-lg bg-green-600/20 border-green-500/30">
                                    <p class="text-sm font-semibold text-green-400">✉️ Request Received</p>
                                </div>

                                <!-- Actions -->
                                <div class="flex gap-2">
                                    <button
                                        type="button"
                                        wire:click="acceptContactRequest({{ $user->id }})"
                                        class="flex-1 px-4 py-2 font-semibold text-white transition rounded-lg bg-gradient-to-r from-green-600 to-green-700 hover:shadow-lg hover:shadow-green-500/40"
                                    >
                                        ✓ Accept
                                    </button>
                                    <button
                                        type="button"
                                        wire:click="refuseContactRequest({{ $user->id }})"
                                        class="flex-1 px-4 py-2 font-semibold text-red-400 transition border rounded-lg bg-red-600/20 hover:bg-red-600/30 border-red-500/30"
                                    >
                                        ✗ Refuse
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center col-span-full">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-lg text-gray-400">No requests received</p>
                        </div>
                    @endforelse
                </div>
            @endif
        </div>
    </div>
</div>
