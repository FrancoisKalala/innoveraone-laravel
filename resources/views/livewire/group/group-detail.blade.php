<div x-data="{ activeTab: 'my-groups' }">
    <!-- Tabs Navigation -->
    <div class="flex gap-2 border-b border-blue-700/20 mb-6">
        <button @click="activeTab = 'my-groups'" :class="activeTab === 'my-groups' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-white'" class="px-6 py-3 font-semibold transition">
            <span class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                My Groups
            </span>
        </button>
        <button @click="activeTab = 'discover'" :class="activeTab === 'discover' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-white'" class="px-6 py-3 font-semibold transition">
            <span class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Discover
            </span>
        </button>
        <button @click="activeTab = 'invitations'" :class="activeTab === 'invitations' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-white'" class="px-6 py-3 font-semibold transition">
            <span class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h14l4 4V6c0-1.1-.9-2-2-2z"/></svg>
                Invitations
            </span>
        </button>
    </div>

    <!-- My Groups Tab Content -->
    <div x-show="activeTab === 'my-groups'" class="grid md:grid-cols-3 gap-6 space-y-6 md:space-y-0">
    <!-- Main Chat Area -->
    <div class="md:col-span-2 bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border border-blue-700/20 p-6 flex flex-col h-[600px]">
        @if($selectedGroup)
            <!-- Group Header -->
            <div class="pb-4 border-b border-blue-700/20 flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-white mb-2">{{ $selectedGroup->name }}</h2>
                    <p class="text-gray-400 text-sm">{{ $selectedGroup->members()->count() }} members • {{ ucfirst($selectedGroup->privacy) }}</p>
                </div>
                <div class="flex gap-2">
                    @if(auth()->id() === $selectedGroup->created_by)
                        <button wire:click="openManageMembersModal" class="p-2 hover:bg-green-700/20 rounded-lg transition" title="Manage members">
                            <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M15 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm-9-2c1.66 0 2.99-1.34 2.99-3S7.66 4 6 4 3 5.34 3 7s1.34 3 3 3zm0 4c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm9 0c-.29 0-.62.02-.97.05 1.16.89 1.97 1.74 1.97 2.95V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
                            </svg>
                        </button>
                    @endif
                    @if(auth()->id() === $selectedGroup->created_by)
                        <button wire:click="openEditGroupModal" class="p-2 hover:bg-blue-700/20 rounded-lg transition" title="Manage group">
                            <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.14 12.94c.4-1.16.4-2.72 0-3.88l2.85-2.22c.25-.2.31-.55.12-.85l-2.69-4.66c-.19-.34-.57-.41-.87-.22l-3.35 2.65c-.71-.56-1.58-1.03-2.55-1.37L14.41 1.35c-.04-.4-.41-.71-.84-.71h-5.3c-.42 0-.8.31-.84.71l-.49 3.39c-.98.34-1.84.81-2.55 1.37L2.96 3.07c-.29-.19-.68-.12-.87.22L0 7.95c-.19.33-.13.67.12.85l2.85 2.22c-.4 1.16-.4 2.72 0 3.88l-2.85 2.22c-.25.2-.31.55-.12.85l2.69 4.66c.19.34.57.41.87.22l3.35-2.65c.71.56 1.58 1.03 2.55 1.37l.49 3.39c.05.4.42.71.84.71h5.3c.42 0 .8-.31.84-.71l.49-3.39c.98-.34 1.84-.81 2.55-1.37l3.35 2.65c.29.19.68.12.87-.22l2.69-4.66c.19-.34.13-.67-.12-.85l-2.85-2.22zM12 15.6c-1.98 0-3.6-1.62-3.6-3.6s1.62-3.6 3.6-3.6 3.6 1.62 3.6 3.6-1.62 3.6-3.6 3.6z"/>
                            </svg>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Messages -->
            <div class="flex-1 overflow-y-auto my-4 space-y-4">
                @forelse($messages as $message)
                    <div class="flex gap-3 {{ $message->user_id === auth()->id() ? 'flex-row-reverse' : '' }} group">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gradient-to-br from-blue-700 to-black flex items-center justify-center">
                            <span class="text-sm font-bold text-white">{{ substr($message->user->name, 0, 1) }}</span>
                        </div>
                        <div class="flex-1 {{ $message->user_id === auth()->id() ? 'text-right' : '' }}">
                            <p class="font-semibold text-white text-sm">{{ $message->user->name }}</p>
                            <div class="mt-1 inline-block relative">
                                @if($editingMessageId === $message->id)
                                    <div class="px-4 py-2 rounded-lg bg-yellow-500/20 border border-yellow-500/50">
                                        <input type="text" wire:model="editedContent" class="w-full px-2 py-1 bg-slate-700 text-white border border-blue-700/30 rounded text-sm" autofocus>
                                        <div class="flex gap-2 mt-2">
                                            <button wire:click="saveEditedMessage" class="px-3 py-1 bg-green-500/30 text-green-400 rounded text-xs hover:bg-green-500/50">Save</button>
                                            <button wire:click="cancelEditMessage" class="px-3 py-1 bg-red-500/30 text-red-400 rounded text-xs hover:bg-red-500/50">Cancel</button>
                                        </div>
                                    </div>
                                @else
                                    <div class="px-4 py-2 rounded-lg {{ $message->user_id === auth()->id() ? 'bg-blue-700/30 text-white/90 border border-blue-700/50' : 'bg-slate-700 text-gray-200' }}">
                                        <p class="text-sm">{{ $message->content }}</p>
                                        @if($message->updated_at->notEqualTo($message->created_at))
                                            <p class="text-xs text-gray-400 mt-1">(edited)</p>
                                        @endif
                                    </div>
                                @endif

                                @if($editingMessageId !== $message->id && (auth()->id() === $message->user_id || auth()->id() === $selectedGroup->created_by))
                                    <div class="absolute -right-24 top-1/2 -translate-y-1/2 {{ $message->user_id === auth()->id() ? '-left-24 -right-auto' : '' }} opacity-0 group-hover:opacity-100 transition-opacity flex gap-1">
                                        <button wire:click="showMessageDetails({{ $message->id }})" class="p-2 hover:bg-blue-500/20 rounded" title="Message info">
                                            <svg class="w-4 h-4 text-blue-400 hover:text-blue-300" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                                            </svg>
                                        </button>
                                        @if(auth()->id() === $message->user_id)
                                            <button wire:click="startEditMessage({{ $message->id }}, '{{ addslashes($message->content) }}')" class="p-2 hover:bg-yellow-500/20 rounded" title="Edit message">
                                                <svg class="w-4 h-4 text-yellow-400 hover:text-yellow-300" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25z"/>
                                                    <path d="M20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                                </svg>
                                            </button>
                                        @endif
                                        <button wire:click="deleteMessage({{ $message->id }})" class="p-2 hover:bg-red-500/20 rounded" title="Delete message">
                                            <svg class="w-4 h-4 text-red-400 hover:text-red-300" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/>
                                            </svg>
                                        </button>
                                    </div>
                                @endif
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
                    @forelse($groupMembers as $member)
                        <div class="flex items-center justify-between p-2 rounded bg-slate-700/50 group">
                            <div class="flex items-center gap-2 flex-1">
                                <p class="text-sm font-semibold text-white">{{ $member['user']['name'] ?? 'Unknown' }}</p>
                                @if($member['user']['id'] === $selectedGroup->created_by)
                                    <span class="px-2 py-0.5 bg-blue-500/30 text-blue-300 text-xs rounded">Creator</span>
                                @elseif($member['role'] === 'admin')
                                    <span class="px-2 py-0.5 bg-purple-500/30 text-purple-300 text-xs rounded">Admin</span>
                                @endif
                            </div>
                            @if(auth()->id() === $selectedGroup->created_by && auth()->id() !== $member['user']['id'])
                                <div class="flex gap-1 opacity-0 group-hover:opacity-100 transition">
                                    @if($member['role'] === 'member')
                                        <button wire:click="promoteToAdmin({{ $member['user']['id'] }})" class="px-2 py-1 bg-purple-500/20 text-purple-400 hover:bg-purple-500/30 text-xs rounded transition">Admin</button>
                                    @else
                                        <button wire:click="demoteFromAdmin({{ $member['user']['id'] }})" class="px-2 py-1 bg-slate-600 text-gray-300 hover:bg-slate-500 text-xs rounded transition">Demote</button>
                                    @endif
                                    <button wire:click="removeMember({{ $member['user']['id'] }})" class="px-2 py-1 bg-red-500/20 text-red-400 hover:bg-red-500/30 text-xs rounded transition">Remove</button>
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-gray-400 text-sm text-center py-4">No members yet</p>
                    @endforelse
                </div>
            </div>

            <!-- Group Info -->
            <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border border-blue-700/20 p-6">
                <h3 class="text-lg font-bold text-white mb-4">Info</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-xs text-gray-400 uppercase">Created by</p>
                        <p class="text-white font-semibold">{{ $selectedGroup->creator->name ?? 'Unknown' }}</p>
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

    <!-- Message Info Modal -->
    @if($showMessageInfo)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-blue-700/20 p-8 max-w-md w-full mx-4">
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
                        <p class="text-white font-semibold">{{ $infoMessageDetails['sender'] ?? 'Unknown' }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 uppercase">Message</p>
                        <p class="text-white bg-slate-700/50 p-3 rounded-lg break-words">{{ $infoMessageDetails['content'] ?? '' }}</p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-400 uppercase">Sent</p>
                        <p class="text-white">{{ $infoMessageDetails['created_at'] ?? '' }}</p>
                    </div>

                    @if($infoMessageDetails['is_edited'] ?? false)
                        <div>
                            <p class="text-xs text-gray-400 uppercase">Last Edited</p>
                            <p class="text-white">{{ $infoMessageDetails['updated_at'] ?? '' }}</p>
                        </div>
                    @endif

                    <button wire:click="closeMessageInfo" class="w-full mt-6 px-4 py-2 bg-gradient-to-r from-blue-700 to-black text-white rounded-lg font-semibold hover:shadow-lg transition">
                        Close
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Edit Group Modal -->
    @if($showEditGroupModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50">
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-blue-700/20 p-8 max-w-md w-full mx-4">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-white">Manage Group</h2>
                    <button wire:click="closeEditGroupModal" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit="saveGroupInfo" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Group Name</label>
                        <input type="text" wire:model="editGroupName" class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white placeholder-gray-500 border border-blue-700/20 focus:border-blue-700 focus:outline-none transition" required>
                        @error('editGroupName') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                        <textarea wire:model="editGroupDescription" rows="3" class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white placeholder-gray-500 border border-blue-700/20 focus:border-blue-700 focus:outline-none transition"></textarea>
                        @error('editGroupDescription') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Privacy</label>
                        <select wire:model="editGroupPrivacy" class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white border border-blue-700/20 focus:border-blue-700 focus:outline-none transition">
                            <option value="public">Public</option>
                            <option value="private">Private</option>
                        </select>
                        @error('editGroupPrivacy') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="flex gap-2 pt-4">
                        <button type="button" wire:click="closeEditGroupModal" class="flex-1 px-4 py-2 bg-slate-700 text-white rounded-lg font-semibold hover:bg-slate-600 transition">
                            Cancel
                        </button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-gradient-to-r from-blue-700 to-black text-white rounded-lg font-semibold hover:shadow-lg transition">
                            Save Changes
                        </button>
                    </div>

                    <button type="button" wire:click="deleteGroup" onclick="return confirm('Are you sure? This will delete the entire group.')" class="w-full px-4 py-2 bg-red-500/20 text-red-400 border border-red-500/50 rounded-lg font-semibold hover:bg-red-500/30 transition text-sm mt-4">
                        Delete Group
                    </button>
                </form>
            </div>
        </div>
    @endif

    <!-- Manage Members Modal -->
    @if($showManageMembersModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" wire:key="manage-members-modal">
            <div wire:click="closeManageMembersModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-blue-700/30 p-8 max-w-4xl w-full max-h-[90vh] overflow-y-auto relative z-10">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-white">Manage Members</h2>
                    <button wire:click="closeManageMembersModal" class="text-gray-400 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Add Members Section -->
                <div class="mb-6 pb-6 border-b border-blue-700/20">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Add Members</label>
                    <input type="text" wire:model.live="searchMemberQuery" placeholder="Search users..." class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white placeholder-gray-500 border border-blue-700/20 focus:border-blue-700 focus:outline-none transition">

                    @if($searchMemberResults)
                        <div class="mt-2 bg-slate-700/50 rounded-lg p-3 space-y-2 max-h-32 overflow-y-auto">
                            @forelse($searchMemberResults as $user)
                                <button type="button" wire:click="addMember({{ $user->id }})" class="w-full text-left p-2 rounded hover:bg-slate-600 transition">
                                    <p class="font-semibold text-white text-sm">{{ $user->name }}</p>
                                    <p class="text-xs text-gray-400">{{ '@' . ($user->username ?? strtolower(str_replace(' ', '', $user->name))) }}</p>
                                </button>
                            @empty
                                <p class="text-gray-400 text-sm text-center py-2">No users found</p>
                            @endforelse
                        </div>
                    @endif
                </div>

                <!-- Current Members Section -->
                <div>
                    <h3 class="text-sm font-bold text-white mb-3">Current Members</h3>
                    <div class="space-y-2 max-h-40 overflow-y-auto">
                        @forelse($groupMembers as $member)
                            <div class="flex items-center justify-between p-2 rounded bg-slate-700/50">
                                <div class="flex items-center gap-2 flex-1">
                                    <div>
                                        <p class="text-sm font-semibold text-white">{{ $member['user']['name'] ?? 'Unknown' }}</p>
                                        @if($member['user']['id'] === $selectedGroup->created_by)
                                            <span class="text-xs text-blue-400">Creator</span>
                                        @elseif($member['role'] === 'admin')
                                            <span class="text-xs text-purple-400">Admin</span>
                                        @else
                                            <span class="text-xs text-gray-400">Member</span>
                                        @endif
                                    </div>
                                </div>
                                @if(auth()->id() !== $member['user']['id'] && $member['user']['id'] !== $selectedGroup->created_by)
                                    <div class="flex gap-1">
                                        @if($member['role'] === 'member')
                                            <button wire:click="promoteToAdmin({{ $member['user']['id'] }})" class="px-2 py-1 bg-purple-500/20 text-purple-300 hover:bg-purple-500/30 text-xs rounded transition">Admin</button>
                                        @else
                                            <button wire:click="demoteFromAdmin({{ $member['user']['id'] }})" class="px-2 py-1 bg-slate-600 text-gray-300 hover:bg-slate-500 text-xs rounded transition">Demote</button>
                                        @endif
                                        <button wire:click="removeMember({{ $member['user']['id'] }})" class="px-2 py-1 bg-red-500/20 text-red-300 hover:bg-red-500/30 text-xs rounded transition">Remove</button>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p class="text-gray-400 text-sm text-center py-4">No members</p>
                        @endforelse
                    </div>
                </div>

                <button wire:click="closeManageMembersModal" class="w-full mt-6 px-4 py-2 bg-gradient-to-r from-blue-700 to-black text-white rounded-lg font-semibold hover:shadow-lg transition">
                    Close
                </button>
            </div>
        </div>
    @endif
</div>
</div>

