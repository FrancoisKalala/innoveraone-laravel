<div class="space-y-6" x-data="{ searchExpanded: false, showRecent: false }" @click.away="showRecent = false">
    @if(session()->has('success'))
        <div class="p-4 text-green-400 border rounded-lg bg-green-500/20 border-green-500/50">{{ session('success') }}</div>
    @endif

    <div class="grid gap-6 md:grid-cols-3">
        <!-- Contact List -->
        <div class="md:col-span-1">
            <div class="p-6 border bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border-blue-700/20">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="flex items-center gap-2 text-2xl font-bold text-white">
                        <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        My Contacts
                    </h2>
                    <!-- Search Icon Button -->
                    <button type="button" @click="searchExpanded = !searchExpanded; searchExpanded && $nextTick(() => $refs.contactsSearch.focus())" class="flex items-center justify-center text-blue-300 transition rounded-full w-9 h-9 bg-slate-700 hover:bg-slate-600 hover:scale-110 shrink-0" aria-label="Toggle search">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </button>
                </div>

                <!-- Collapsible Search -->
                <div x-show="searchExpanded" x-transition:enter="transition-all duration-300 ease-out" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition-all duration-200 ease-in" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4" class="relative z-50 mb-4" style="display: none;">
                    <div class="flex items-center gap-3 px-4 py-3 border rounded-xl border-slate-600/50 bg-slate-800/70">
                        <button type="button" @click="showRecent = !showRecent; $refs.contactsSearch.focus();" class="flex items-center justify-center text-blue-300 transition rounded-full w-9 h-9 bg-slate-700 hover:bg-slate-600" aria-label="Toggle recent searches">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                        <input
                            x-ref="contactsSearch"
                            @focus="showRecent = true"
                            type="text"
                            wire:model.live.debounce.500ms="searchQuery"
                            placeholder="Search contacts..."
                            class="flex-1 px-3 py-2 text-sm text-white placeholder-gray-400 transition border border-transparent bg-slate-900/40 rounded-xl focus:border-blue-500 focus:outline-none"
                        >
                        @if($searchQuery)
                            <button wire:click="$set('searchQuery', '')" class="p-2 text-gray-400 transition rounded-lg hover:text-white hover:bg-slate-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        @endif
                        <button type="button" @click="searchExpanded = false" class="p-2 text-gray-400 transition rounded-lg hover:text-white hover:bg-slate-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                        </button>
                    </div>

                    <!-- Recent Searches Dropdown -->
                    @if (count($recentSearches) > 0)
                        <div x-show="showRecent" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute left-0 right-0 mt-2 bg-slate-800 border border-blue-700/30 rounded-xl shadow-2xl overflow-hidden z-[60]" style="display: none;">
                            <div class="px-4 py-2 border-b bg-slate-900/80 border-blue-700/30">
                                <p class="text-xs font-semibold text-gray-400 uppercase">Recent Searches</p>
                            </div>
                            <div class="overflow-y-auto max-h-48">
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

                <!-- Contact List -->
                <div class="space-y-2 overflow-y-auto max-h-96">
                    @forelse($contacts as $contact)
                        <button wire:click="selectContact({{ $contact->id }})" class="w-full text-left p-3 rounded-lg {{ $selectedContactId === $contact->id ? 'bg-gradient-to-r from-blue-700 to-black' : 'bg-slate-700 hover:bg-slate-600' }} transition">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-full bg-gradient-to-br from-blue-700 to-black">
                                    <span class="text-xs font-bold text-white">{{ substr($contact->name, 0, 1) }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-white truncate">{{ $contact->name }}</p>
                                    <p class="text-xs {{ $selectedContactId === $contact->id ? 'text-white/90' : 'text-gray-400' }}">{{ '@' . ($contact->username ?? strtolower(str_replace(' ', '', $contact->name))) }}</p>
                                </div>
                            </div>
                        </button>
                    @empty
                        <p class="py-8 text-sm text-center text-gray-400">No contacts yet</p>
                    @endforelse
                </div>

                <button wire:click="openSearchModal" class="w-full py-2 mt-4 text-sm font-semibold text-white transition rounded-lg bg-gradient-to-r from-blue-700 to-black hover:shadow-lg">
                    + Add Contact
                </button>
            </div>
        </div>

        <!-- Contact Detail -->
        <div class="md:col-span-2">
            @if($selectedContact)
                <div class="p-6 border bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border-blue-700/20">
                    <!-- Contact Header -->
                    <div class="flex items-center gap-4 mb-6">
                        <div class="flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-blue-700 to-black">
                            <span class="text-3xl font-bold text-white">{{ substr($selectedContact->name, 0, 1) }}</span>
                        </div>
                        <div>
                            <h3 class="text-2xl font-bold text-white">{{ $selectedContact->name }}</h3>
                            <p class="text-blue-600">{{ '@' . ($selectedContact->username ?? strtolower(str_replace(' ', '', $selectedContact->name))) }}</p>
                            <p class="mt-1 text-sm text-gray-400">{{ $selectedContact->bio ?? 'No bio' }}</p>
                        </div>
                    </div>

                    <!-- Contact Stats -->
                    <div class="grid grid-cols-3 gap-4 pb-6 mb-6 border-b border-blue-700/20">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-blue-600">{{ $selectedContact->posts()->count() }}</p>
                            <p class="text-sm text-gray-400">Posts</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-blue-600">{{ $selectedContact->followers()->count() }}</p>
                            <p class="text-sm text-gray-400">Followers</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-blue-600">{{ $selectedContact->albums()->count() }}</p>
                            <p class="text-sm text-gray-400">Albums</p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2 mb-6">
                        <button wire:click="startConversation" class="flex-1 px-4 py-2 font-semibold text-white transition rounded-lg bg-gradient-to-r from-blue-700 to-black hover:shadow-lg">
                            Send Message
                        </button>
                        <button wire:click="removeContact({{ $selectedContact->id }})" class="flex-1 px-4 py-2 font-semibold text-red-400 transition border rounded-lg bg-red-500/20 border-red-500/50 hover:bg-red-500/30">
                            Remove
                        </button>
                    </div>

                    <!-- Recent Posts -->
                    <div>
                        <h4 class="flex items-center gap-2 mb-4 text-lg font-bold text-white">
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                            Recent Posts
                        </h4>
                        <div class="space-y-3 overflow-y-auto max-h-64">
                            @forelse($selectedContact->posts()->orderByDesc('created_at')->limit(5)->get() as $post)
                                @livewire('post.post-card', ['post' => $post], key($post->id))
                            @empty
                                <p class="py-4 text-sm text-center text-gray-400">No posts yet</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            @else
                <div class="flex items-center justify-center h-96">
                    <div class="text-center">
                        <div class="mb-3 text-5xl">👥</div>
                        <p class="text-gray-400">Select a contact to view details</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Search & Add Contact Modal -->
    @if($showSearchModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
            <div class="w-full max-w-md p-8 mx-4 border bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border-blue-700/20">
                <h2 class="mb-6 text-2xl font-bold text-white">Find & Add Contacts</h2>
                <form class="space-y-4">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-300">Search Users</label>
                        <input type="text" wire:model.live="searchQuery" placeholder="Name or username..." class="w-full px-4 py-2 text-white placeholder-gray-500 transition border rounded-lg bg-slate-700 border-blue-700/20 focus:border-blue-700 focus:outline-none">
                    </div>

                    @if($searchQuery)
                        <div class="p-4 overflow-y-auto rounded-lg bg-slate-700/50 max-h-48">
                            @forelse($searchResults as $user)
                                <button type="button" wire:click="sendContactRequest({{ $user->id }})" class="w-full p-3 mb-2 text-left transition rounded hover:bg-slate-600">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="font-semibold text-white">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-400">{{ '@' . ($user->username ?? strtolower(str_replace(' ', '', $user->name))) }}</p>
                                        </div>
                                        <span class="text-sm text-blue-600">Add</span>
                                    </div>
                                </button>
                            @empty
                                <p class="py-4 text-sm text-center text-gray-400">No users found</p>
                            @endforelse
                        </div>
                    @endif

                    <button type="button" wire:click="closeSearchModal" class="w-full py-2 font-semibold text-white transition rounded-lg bg-slate-700 hover:bg-slate-600">
                        Close
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>

