<div class="grid md:grid-cols-3 gap-6 h-[700px]">
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

