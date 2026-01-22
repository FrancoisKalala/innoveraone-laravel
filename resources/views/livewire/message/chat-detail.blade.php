<div x-data="{ activeTab: 'all-messages', searchExpanded: false, showRecent: false }" @click.away="showRecent = false">
    <!-- Tabs Navigation with Search -->
    <div class="border-b border-blue-700/20 mb-6">
        <div class="flex items-center justify-between gap-2">
            <div class="flex gap-2">
                <button @click="activeTab = 'all-messages'" :class="activeTab === 'all-messages' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-white'" class="px-6 py-3 font-semibold transition">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm13 8H6v-2h13v2zm0-4H6v-2h13v2z"/></svg>
                        All Messages
                    </span>
                </button>
                <button @click="activeTab = 'contacts'" :class="activeTab === 'contacts' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-white'" class="px-6 py-3 font-semibold transition">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        Contacts
                    </span>
                </button>
                <button @click="activeTab = 'archived'" :class="activeTab === 'archived' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-white'" class="px-6 py-3 font-semibold transition">
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                        Archived
                    </span>
                </button>
            </div>
            <!-- Search Icon Button -->
            <button type="button" @click="searchExpanded = !searchExpanded; searchExpanded && $nextTick(() => $refs.messagesSearch.focus())" class="flex items-center justify-center w-9 h-9 rounded-full bg-slate-700 text-blue-300 hover:bg-slate-600 hover:scale-110 transition shrink-0" aria-label="Toggle search">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Collapsible Search Bar -->
    <div x-show="searchExpanded" x-transition:enter="transition-all duration-300 ease-out" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition-all duration-200 ease-in" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4" class="mb-6 overflow-hidden" style="display: none;">
        <div class="relative">
            <div class="flex items-center gap-3 px-4 py-3 rounded-xl border border-slate-600/50 bg-slate-800/70">
                <button type="button" @click="showRecent = !showRecent; $refs.messagesSearch.focus();" class="flex items-center justify-center w-9 h-9 rounded-full bg-slate-700 text-blue-300 hover:bg-slate-600 transition" aria-label="Toggle recent searches">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
                <input
                    x-ref="messagesSearch"
                    @focus="showRecent = true"
                    type="text"
                    wire:model.live.debounce.500ms="searchQuery"
                    placeholder="Search conversations or messages..."
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
                <div x-show="showRecent" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute left-0 right-0 mt-2 bg-slate-800 border border-blue-700/30 rounded-xl shadow-2xl overflow-hidden z-10" style="display: none;">
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
    </div>

    <!-- All Messages Tab Content -->
    <div x-show="activeTab === 'all-messages'" class="grid md:grid-cols-3 gap-6 h-[700px]">
    <!-- Conversations List -->
    <div class="md:col-span-1 bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border border-blue-700/20 p-6 flex flex-col">
        <h2 class="text-2xl font-bold text-white mb-4 flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm13 8H6v-2h13v2zm0-4H6v-2h13v2z"/></svg>
            Messages
        </h2>

        <div class="flex-1 overflow-y-auto space-y-2">
            @forelse($conversations as $conversation)
                <button wire:click="selectConversation({{ $conversation->id }})" class="w-full text-left p-3 rounded-lg {{ $selectedConversationId === $conversation->id ? 'bg-gradient-to-r from-blue-700 to-black' : 'bg-slate-700 hover:bg-slate-600' }} transition">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-700 to-black flex items-center justify-center flex-shrink-0">
                            <span class="text-sm font-bold text-white">{{ substr($conversation->user->name ?? 'Chat', 0, 1) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-white truncate">{{ $conversation->user->name ?? 'Private Chat' }}</p>
                            <p class="text-xs {{ $selectedConversationId === $conversation->id ? 'text-white/90' : 'text-gray-400' }} truncate">
                                {{ $conversation->lastMessage->content ?? 'No messages yet' }}
                            </p>
                        </div>
                    </div>
                </button>
            @empty
                <p class="text-gray-400 text-sm text-center py-8">No conversations yet</p>
            @endforelse
        </div>

        <button wire:click="openNewMessageModal" class="mt-4 w-full py-2 bg-gradient-to-r from-blue-700 to-black text-white rounded-lg font-semibold text-sm hover:shadow-lg transition">
            + New Message
        </button>
    </div>

    <!-- Chat Area -->
    <div class="md:col-span-2 bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border border-blue-700/20 p-6 flex flex-col">
        @if($selectedConversation)
            <!-- Chat Header -->
            <div class="pb-4 border-b border-blue-700/20 mb-4">
                <h3 class="text-xl font-bold text-white">{{ $selectedConversation->name }}</h3>
                <p class="text-sm text-gray-400">{{ '@' . ($selectedConversation->username ?? strtolower(str_replace(' ', '', $selectedConversation->name))) }}</p>
            </div>

            <!-- Messages -->
            <div class="flex-1 overflow-y-auto space-y-4 mb-4" wire:poll.keep-alive="loadMessages">
                @forelse($messages as $message)
                    <div class="flex gap-3 {{ $message->sender_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-blue-700 to-black flex items-center justify-center">
                            <span class="text-sm font-bold text-white">{{ substr($message->sender->name ?? 'User', 0, 1) }}</span>
                        </div>
                        <div class="flex-1 {{ $message->sender_id === auth()->id() ? 'text-right' : '' }}">
                            <p class="font-semibold text-white text-sm">{{ $message->sender->name ?? 'User' }}</p>
                            <div class="mt-1 inline-block max-w-xs px-4 py-2 rounded-lg {{ $message->sender_id === auth()->id() ? 'bg-blue-700/30 text-white/90 border border-blue-700/50' : 'bg-slate-700 text-gray-200' }}">
                                <p class="text-sm break-words">{{ $message->content }}</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $message->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="flex items-center justify-center h-full">
                        <div class="text-center">
                            <div class="text-4xl mb-2">💬</div>
                            <p class="text-gray-400">No messages yet. Start the conversation!</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Message Input -->
            <form wire:submit="sendMessage" class="flex gap-2 pt-4 border-t border-blue-700/20">
                <input type="text" wire:model.live="messageContent" placeholder="Type a message..." class="flex-1 px-4 py-2 rounded-lg bg-slate-700 text-white placeholder-gray-500 border border-blue-700/20 focus:border-blue-700 focus:outline-none transition">
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-700 to-black text-white rounded-lg font-semibold hover:shadow-lg transition">
                    Send
                </button>
            </form>
        @else
            <div class="flex items-center justify-center h-full">
                <div class="text-center">
                    <div class="text-5xl mb-3">✉️</div>
                    <p class="text-gray-400">Select a conversation or start a new message</p>
                </div>
            </div>
        @endif
    </div>

    <!-- New Message Modal -->
    @if($showNewMessageModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-blue-700/20 p-8 max-w-md w-full mx-4">
                <h2 class="text-2xl font-bold text-white mb-6">Start New Conversation</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Search Users</label>
                        <input type="text" wire:model.live="recipientSearch" placeholder="Search contacts..." class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white placeholder-gray-500 border border-blue-700/20 focus:border-blue-700 focus:outline-none transition">
                    </div>

                    @if($recipientSearch && $recipientResults)
                        <div class="bg-slate-700/50 rounded-lg p-4 max-h-32 overflow-y-auto">
                            @forelse($recipientResults as $user)
                                <button type="button" wire:click="selectRecipient({{ $user->id }})" class="w-full text-left p-2 rounded hover:bg-slate-600 transition">
                                    <p class="font-semibold text-white">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ '@' . ($user->username ?? strtolower(str_replace(' ', '', $user->name))) }}</p>
                                </button>
                            @empty
                                <p class="text-gray-400 text-sm text-center">No users found</p>
                            @endforelse
                        </div>
                    @endif

                    <div class="flex gap-2 pt-4">
                        <button type="button" wire:click="closeNewMessageModal" class="flex-1 px-4 py-2 bg-slate-700 text-white rounded-lg font-semibold hover:bg-slate-600 transition">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
</div>

