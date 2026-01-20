<div class="grid md:grid-cols-3 gap-6 space-y-6 md:space-y-0">
    <!-- Main Chat Area -->
    <div class="md:col-span-2 bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border border-blue-700/20 p-6 flex flex-col h-[600px]">
        @if($selectedGroup)
            <!-- Group Header -->
            <div class="pb-4 border-b border-blue-700/20">
                <h2 class="text-2xl font-bold text-white mb-2">{{ $selectedGroup->name }}</h2>
                <p class="text-gray-400 text-sm">{{ $selectedGroup->members()->count() }} members</p>
            </div>

            <!-- Messages -->
            <div class="flex-1 overflow-y-auto my-4 space-y-4">
                @forelse($messages as $message)
                    <div class="flex gap-3 {{ $message->user_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-blue-700 to-black flex items-center justify-center">
                            <span class="text-sm font-bold text-white">{{ substr($message->user->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1 {{ $message->user_id === auth()->id() ? 'text-right' : '' }}">
                            <p class="font-semibold text-white text-sm">{{ $message->user->name }}</p>
                            <div class="mt-1 inline-block px-4 py-2 rounded-lg {{ $message->user_id === auth()->id() ? 'bg-blue-700/30 text-white/90 border border-blue-700/50' : 'bg-slate-700 text-gray-200' }}">
                                <p class="text-sm">{{ $message->content }}</p>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">{{ $message->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="text-4xl mb-2">💬</div>
                        <p class="text-gray-400">No messages yet. Start a conversation!</p>
                    </div>
                @endforelse
            </div>

            <!-- Message Input -->
            <div class="pt-4 border-t border-blue-700/20">
                <form wire:submit="sendGroupMessage" class="flex gap-2">
                    <input type="text" wire:model="newMessage" placeholder="Type a message..." class="flex-1 px-4 py-2 rounded-lg bg-slate-700 text-white placeholder-gray-500 border border-blue-700/20 focus:border-blue-700 focus:outline-none transition">
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-700 to-black text-white rounded-lg font-semibold hover:shadow-lg transition">
                        Send
                    </button>
                </form>
            </div>
        @else
            <div class="flex items-center justify-center h-full">
                <div class="text-center">
                    <div class="text-5xl mb-3">👥</div>
                    <p class="text-gray-400">Select a group to start chatting</p>
                </div>
            </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Group List -->
        <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border border-blue-700/20 p-6">
            <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.89 1.97 1.74 1.97 2.95V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                Groups
            </h3>
            <div class="space-y-2 max-h-96 overflow-y-auto">
                @forelse(auth()->user()->groups()->orderByDesc('created_at')->get() as $group)
                    <button wire:click="selectGroup({{ $group->id }})" class="w-full text-left p-3 rounded-lg {{ $selectedGroup?->id === $group->id ? 'bg-gradient-to-r from-blue-700 to-black' : 'bg-slate-700 hover:bg-slate-600' }} transition">
                        <p class="font-semibold text-white">{{ $group->name }}</p>
                        <p class="text-xs {{ $selectedGroup?->id === $group->id ? 'text-white/90' : 'text-gray-400' }}">{{ $group->members()->count() }} members</p>
                    </button>
                @empty
                    <p class="text-gray-400 text-sm text-center py-8">No groups yet</p>
                @endforelse
            </div>
            <button wire:click="openCreateModal" class="w-full mt-4 py-2 bg-gradient-to-r from-blue-700 to-black text-white rounded-lg font-semibold text-sm hover:shadow-lg transition">
                + New Group
            </button>
        </div>

        @if($selectedGroup)
            <!-- Group Members -->
            <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border border-blue-700/20 p-6">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                    Members
                </h3>
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @forelse($selectedGroup->members as $member)
                        <div class="flex items-center justify-between p-2 rounded bg-slate-700/50">
                            <p class="text-sm font-semibold text-white">{{ $member->name }}</p>
                            @if(auth()->id() === $selectedGroup->user_id && auth()->id() !== $member->id)
                                <button wire:click="removeMember({{ $member->id }})" class="text-red-400 hover:text-red-300 text-xs">Remove</button>
                            @endif
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Group Info -->
            <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border border-blue-700/20 p-6">
                <h3 class="text-lg font-bold text-white mb-4">Info</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-400 uppercase">Created by</p>
                        <p class="text-white font-semibold">{{ $selectedGroup->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-400 uppercase">Created</p>
                        <p class="text-white">{{ $selectedGroup->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Create Group Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-blue-700/20 p-8 max-w-md w-full mx-4">
                <h2 class="text-2xl font-bold text-white mb-6">Create New Group</h2>
                <form wire:submit="createGroup" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Group Name</label>
                        <input type="text" wire:model="groupName" placeholder="Group name..." class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white placeholder-gray-500 border border-blue-700/20 focus:border-blue-700 focus:outline-none transition">
                    </div>
                    <div class="flex gap-2 pt-4">
                        <button type="button" wire:click="closeCreateModal" class="flex-1 px-4 py-2 bg-slate-700 text-white rounded-lg font-semibold hover:bg-slate-600 transition">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-gradient-to-r from-blue-700 to-black text-white rounded-lg font-semibold hover:shadow-lg transition">
                            Create
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>

