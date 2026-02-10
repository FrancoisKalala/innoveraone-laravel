<div class="min-h-screen pb-32 bg-gradient-to-br from-slate-900 via-slate-800 to-black">
    <div class="flex">
        <main class="flex-1 p-4 mb-8 overflow-y-auto border shadow-2xl md:p-8 backdrop-blur-xl rounded-2xl border-blue-700/20" style="scrollbar-width: thin; scrollbar-color: rgba(168, 85, 247, 0.5) transparent;">
            <div class="mx-auto max-w-7xl">
                <h1 class="flex items-center gap-3 mb-8 text-4xl font-bold text-white">
                    <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm13 8H6v-2h13v2zm0-4H6v-2h13v2z"/></svg>
                    Messages
                </h1>
                <div x-data="{ activeTab: 'all-messages', searchExpanded: false, showRecent: false }" @click.away="showRecent = false">
                    <!-- Tabs Navigation with Search -->
                    <div class="mb-6 border-b border-blue-700/20">
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
                            <button type="button" @click="searchExpanded = !searchExpanded; searchExpanded && $nextTick(() => $refs.messagesSearch.focus())" class="flex items-center justify-center text-blue-300 transition rounded-full w-9 h-9 bg-slate-700 hover:bg-slate-600 hover:scale-110 shrink-0" aria-label="Toggle search">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Collapsible Search Bar -->
                    <div x-show="searchExpanded" x-transition:enter="transition-all duration-300 ease-out" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition-all duration-200 ease-in" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4" class="mb-6 overflow-hidden" style="display: none;">
                        <div class="relative">
                            <div class="flex items-center gap-3 px-4 py-3 border rounded-xl border-slate-600/50 bg-slate-800/70">
                                <button type="button" @click="showRecent = !showRecent; $refs.messagesSearch.focus();" class="flex items-center justify-center text-blue-300 transition rounded-full w-9 h-9 bg-slate-700 hover:bg-slate-600" aria-label="Toggle recent searches">
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
                                <div x-show="showRecent" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute left-0 right-0 z-10 mt-2 overflow-hidden border shadow-2xl bg-slate-800 border-blue-700/30 rounded-xl" style="display: none;">
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
                    </div>

                    <!-- All Messages Tab Content -->
                    <div x-show="activeTab === 'all-messages'" class="grid md:grid-cols-3 gap-6 h-[700px]">
                    <!-- Conversations List -->
                    <div class="flex flex-col p-6 border md:col-span-1 bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border-blue-700/20">
                        <h2 class="flex items-center gap-2 mb-4 text-2xl font-bold text-white">
                            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm13 8H6v-2h13v2zm0-4H6v-2h13v2z"/></svg>
                            Messages
                        </h2>

                        <div class="flex-1 space-y-2 overflow-y-auto">
                            @forelse($conversations as $conversation)
                                <button wire:click="selectConversation({{ $conversation->id }})" class="w-full text-left p-3 rounded-lg {{ $selectedConversationId === $conversation->id ? 'bg-gradient-to-r from-blue-700 to-black' : 'bg-slate-700 hover:bg-slate-600' }} transition">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-blue-700 to-black">
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
                                <p class="py-8 text-sm text-center text-gray-400">No conversations yet</p>
                            @endforelse
                        </div>

                        <button wire:click="openNewMessageModal" class="w-full py-2 mt-4 text-sm font-semibold text-white transition rounded-lg bg-gradient-to-r from-blue-700 to-black hover:shadow-lg">
                            + New Message
                        </button>
                    </div>

                    <!-- Chat Area -->
                    <div class="flex flex-col p-6 border md:col-span-2 bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border-blue-700/20">
                        @if($selectedConversation)
                            <!-- Chat Header -->
                            <div class="pb-4 mb-4 border-b border-blue-700/20">
                                <h3 class="text-xl font-bold text-white">{{ $selectedConversation->name }}</h3>
                                <p class="text-sm text-gray-400">{{ '@' . ($selectedConversation->username ?? strtolower(str_replace(' ', '', $selectedConversation->name))) }}</p>
                            </div>

                            <!-- Messages -->
                            <div class="flex-1 mb-4 space-y-4 overflow-y-auto" wire:poll.keep-alive="loadMessages">
                                @forelse($messages as $message)
                                    <div class="flex gap-3 {{ $message->sender_id === auth()->id() ? 'flex-row-reverse' : '' }} group">
                                        <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-blue-700 to-black">
                                            <span class="text-sm font-bold text-white">{{ substr($message->sender->name ?? 'User', 0, 1) }}</span>
                                        </div>
                                        <div class="flex-1 {{ $message->sender_id === auth()->id() ? 'text-right' : '' }}">
                                            <p class="text-sm font-semibold text-white">{{ $message->sender->name ?? 'User' }}</p>
                                            <div class="relative inline-block mt-1">
                                                @if($editingMessageId === $message->id)
                                                    <div class="px-4 py-2 border rounded-lg bg-yellow-500/20 border-yellow-500/50">
                                                        <input type="text" wire:model="editedContent" class="w-full px-2 py-1 text-sm text-white border rounded bg-slate-700 border-blue-700/30" autofocus>
                                                        <div class="flex gap-2 mt-2">
                                                            <button wire:click="saveEditedMessage" class="px-3 py-1 text-xs text-green-400 rounded bg-green-500/30 hover:bg-green-500/50">Save</button>
                                                            <button wire:click="cancelEditMessage" class="px-3 py-1 text-xs text-red-400 rounded bg-red-500/30 hover:bg-red-500/50">Cancel</button>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="px-4 py-2 rounded-lg {{ $message->sender_id === auth()->id() ? 'bg-blue-700/30 text-white/90 border border-blue-700/50' : 'bg-slate-700 text-gray-200' }}">
                                                        <p class="text-sm break-words">{{ $message->content }}</p>
                                                        @if($message->updated_at && $message->updated_at->notEqualTo($message->created_at))
                                                            <p class="mt-1 text-xs text-gray-400">(edited)</p>
                                                        @endif
                                                    </div>
                                                @endif

                                                @if($editingMessageId !== $message->id && $message->sender_id === auth()->id())
                                                    <div class="absolute flex gap-1 transition-opacity -translate-y-1/2 opacity-0 -left-24 top-1/2 group-hover:opacity-100">
                                                        <button wire:click="showMessageDetails({{ $message->id }})" class="p-2 rounded hover:bg-blue-500/20" title="Message info">
                                                            <svg class="w-4 h-4 text-blue-400 hover:text-blue-300" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                                                            </svg>
                                                        </button>
                                                        <button wire:click="startEditMessage({{ $message->id }}, '{{ addslashes($message->content) }}')" class="p-2 rounded hover:bg-yellow-500/20" title="Edit message">
                                                            <svg class="w-4 h-4 text-yellow-400 hover:text-yellow-300" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25z"/>
                                                                <path d="M20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                                            </svg>
                                                        </button>
                                                        <button wire:click="deleteMessage({{ $message->id }})" class="p-2 rounded hover:bg-red-500/20" title="Delete message">
                                                            <svg class="w-4 h-4 text-red-400 hover:text-red-300" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                            <p class="mt-1 text-xs text-gray-500">{{ $message->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <div class="flex items-center justify-center h-full">
                                        <div class="text-center">
                                            <div class="mb-2 text-4xl">💬</div>
                                            <p class="text-gray-400">No messages yet. Start the conversation!</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>

                            <!-- Message Input with Emoji Picker -->
                            <form wire:submit="sendMessage" class="relative flex gap-2 pt-4 border-t border-blue-700/20">
                                <input id="messageInput" type="text" wire:model.live="messageContent" placeholder="Type a message..." class="flex-1 px-4 py-2 text-white placeholder-gray-500 transition border rounded-lg bg-slate-700 border-blue-700/20 focus:border-blue-700 focus:outline-none">
                                <button id="emojiBtn" type="button" class="px-2 py-2 text-yellow-400 transition rounded-lg bg-slate-700 hover:bg-slate-600" title="Add emoji">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 22c5.52 0 10-4.48 10-10S17.52 2 12 2 2 6.48 2 12s4.48 10 10 10zm0-2c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-4-5c.2-2.67 5.33-2.67 5.53 0H8zm6.5-3c.83 0 1.5-.67 1.5-1.5S15.33 9 14.5 9s-1.5.67-1.5 1.5S13.67 14 14.5 14zm-5 0c.83 0 1.5-.67 1.5-1.5S10.33 9 9.5 9 8 9.67 8 10.5 8.67 14 9.5 14z"/></svg>
                                </button>
                                <div id="emojiPickerContainer" class="absolute left-0 z-50 bottom-14"></div>
                                <button type="submit" class="px-6 py-2 font-semibold text-white transition rounded-lg bg-gradient-to-r from-blue-700 to-black hover:shadow-lg">
                                    Send
                                </button>
                            </form>
                            <!-- Emoji Mart v2 CDN -->
                            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/emoji-mart@2.11.2/css/emoji-mart.css">
                            <script src="https://cdn.jsdelivr.net/npm/emoji-mart@2.11.2/dist/emoji-mart.js"></script>
                            <script>
                                function attachEmojiPicker() {
                                    var emojiBtn = document.getElementById('emojiBtn');
                                    if (!emojiBtn) return;
                                    // Remove previous listeners
                                    emojiBtn.onclick = null;
                                    emojiBtn.addEventListener('click', function(e) {
                                        e.preventDefault();
                                        var pickerContainer = document.getElementById('emojiPickerContainer');
                                        if (!window.EmojiMart || !window.EmojiMart.Picker) {
                                            alert('Emoji picker failed to load.');
                                            return;
                                        }
                                        if (pickerContainer.childElementCount > 0) {
                                            pickerContainer.innerHTML = '';
                                            return;
                                        }
                                        var picker = window.EmojiMart.Picker({
                                            set: 'apple',
                                            onClick: function(emoji) {
                                                var input = document.getElementById('messageInput');
                                                input.value += emoji.native;
                                                input.dispatchEvent(new Event('input', { bubbles: true }));
                                            }
                                        });
                                        pickerContainer.appendChild(picker);
                                    });
                                }
                                function closeEmojiPickerOnClickOutside(e) {
                                    var pickerContainer = document.getElementById('emojiPickerContainer');
                                    var emojiBtn = document.getElementById('emojiBtn');
                                    if (pickerContainer && pickerContainer.childElementCount > 0 && !pickerContainer.contains(e.target) && e.target !== emojiBtn) {
                                        pickerContainer.innerHTML = '';
                                    }
                                }
                                document.addEventListener('DOMContentLoaded', function() {
                                    attachEmojiPicker();
                                });
                                document.addEventListener('livewire:load', function() {
                                    setTimeout(attachEmojiPicker, 100);
                                });
                                document.addEventListener('livewire:navigated', function() {
                                    setTimeout(attachEmojiPicker, 100);
                                });
                                document.addEventListener('click', closeEmojiPickerOnClickOutside);
                            </script>
                        @else
                            <div class="flex items-center justify-center h-full">
                                <div class="text-center">
                                    <div class="mb-3 text-5xl">✉️</div>
                                    <p class="text-gray-400">Select a conversation or start a new message</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- New Message Modal -->
                    @if($showNewMessageModal)
                        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
                            <div class="w-full max-w-md p-8 mx-4 border bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border-blue-700/20">
                                <h2 class="mb-6 text-2xl font-bold text-white">Start New Conversation</h2>
                                <div class="space-y-4">
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-300">Search Users</label>
                                        <input type="text" wire:model.live="recipientSearch" placeholder="Search contacts..." class="w-full px-4 py-2 text-white placeholder-gray-500 transition border rounded-lg bg-slate-700 border-blue-700/20 focus:border-blue-700 focus:outline-none">
                                    </div>

                                    @if($recipientSearch && $recipientResults)
                                        <div class="p-4 overflow-y-auto rounded-lg bg-slate-700/50 max-h-32">
                                            @forelse($recipientResults as $user)
                                                <button type="button" wire:click="selectRecipient({{ $user->id }})" class="w-full p-2 text-left transition rounded hover:bg-slate-600">
                                                    <p class="font-semibold text-white">{{ $user->name }}</p>
                                                    <p class="text-xs text-gray-400">{{ '@' . ($user->username ?? strtolower(str_replace(' ', '', $user->name))) }}</p>
                                                </button>
                                            @empty
                                                <p class="text-sm text-center text-gray-400">No users found</p>
                                            @endforelse
                                        </div>
                                    @endif

                                    <div class="flex gap-2 pt-4">
                                        <button type="button" wire:click="closeNewMessageModal" class="flex-1 px-4 py-2 font-semibold text-white transition rounded-lg bg-slate-700 hover:bg-slate-600">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Message Info Modal -->
                    @if($showMessageInfo)
                        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
                            <div class="w-full max-w-md p-8 mx-4 border bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border-blue-700/20">
                                <div class="flex items-center justify-between mb-6">
                                    <h2 class="text-2xl font-bold text-white">Message Details</h2>
                                    <button wire:click="closeMessageInfo" class="text-gray-400 hover:text-white">
                                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/>
                                        </svg>
                                    </button>
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <p class="text-xs text-gray-400 uppercase">Sent by</p>
                                        <p class="font-semibold text-white">{{ $infoMessageDetails['sender'] ?? 'Unknown' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 uppercase">Message</p>
                                        <p class="p-3 text-white break-words rounded-lg bg-slate-700/50">{{ $infoMessageDetails['content'] ?? '' }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-400 uppercase">Sent</p>
                                        <p class="text-white">{{ $infoMessageDetails['created_at'] ?? '' }}</p>
                                    </div>
                                    @if($infoMessageDetails['is_edited'] ?? false)
                                        <div>
                                            <p class="text-xs text-gray-400 uppercase">Edited</p>
                                            <p class="text-white">{{ $infoMessageDetails['updated_at'] ?? '' }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </main>
    </div>
</div>