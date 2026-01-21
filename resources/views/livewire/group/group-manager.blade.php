<div class="bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 rounded-2xl border border-blue-700/20 p-6 space-y-6">
    @if(session()->has('success'))
        <div class="p-3 bg-green-500/20 border border-green-500/50 rounded-lg text-green-400 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid md:grid-cols-3 gap-6">
        <!-- Groups List -->
        <div class="md:col-span-1">
            <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                </svg>
                My Groups
            </h3>

            <div class="space-y-2 mb-4 max-h-96 overflow-y-auto">
                @forelse($groups as $group)
                    <button wire:click="selectGroup({{ $group->id }})" class="w-full text-left p-3 rounded-lg transition {{ $selectedGroup && $selectedGroup->id === $group->id ? 'bg-gradient-to-r from-blue-700 to-black' : 'bg-slate-700/50 hover:bg-slate-700' }}">
                        <p class="font-semibold text-white text-sm">{{ $group->name }}</p>
                        <p class="text-xs text-gray-400">{{ $group->members->count() }} members</p>
                    </button>
                @empty
                    <p class="text-gray-400 text-sm text-center py-4">No groups yet</p>
                @endforelse
            </div>

            <button wire:click="$toggle('showCreateForm')" class="w-full py-2 px-4 bg-gradient-to-r from-blue-700 to-black text-white rounded-lg font-semibold hover:shadow-lg transition text-sm">
                + New Group
            </button>

            @if($showCreateForm)
                <form wire:submit.prevent="createGroup" class="mt-4 space-y-3 bg-slate-700/50 p-4 rounded-lg">
                    <input type="text" wire:model="groupName" placeholder="Group name..." class="w-full px-3 py-2 rounded-lg bg-slate-800 text-white placeholder-gray-500 border border-blue-700/20 focus:border-blue-700 focus:outline-none transition text-sm">
                    <textarea wire:model="groupDescription" placeholder="Description (optional)..." class="w-full px-3 py-2 rounded-lg bg-slate-800 text-white placeholder-gray-500 border border-blue-700/20 focus:border-blue-700 focus:outline-none transition text-sm" rows="2"></textarea>
                    <button type="submit" class="w-full py-2 bg-green-500/20 text-green-400 rounded-lg font-semibold hover:bg-green-500/30 transition text-sm">
                        Create
                    </button>
                </form>
            @endif
        </div>

        <!-- Group Chat Area -->
        @if($selectedGroup)
            <div class="md:col-span-2 flex flex-col bg-slate-800/50 rounded-2xl border border-blue-700/20 overflow-hidden">
                <!-- Group Header -->
                <div class="bg-slate-700/50 border-b border-blue-700/20 p-4">
                    <h3 class="font-bold text-white mb-2">{{ $selectedGroup->name }}</h3>
                    <p class="text-xs text-gray-400">{{ $selectedGroup->members->count() }} members</p>
                </div>

                <!-- Messages -->
                <div class="flex-1 overflow-y-auto p-4 space-y-3">
                    @forelse($groupMessages as $message)
                        <div class="flex gap-2">
                            <img src="{{ $message->user->profile_photo_path ? asset('storage/' . $message->user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($message->user->name) . '&background=8b5cf6&color=fff' }}" alt="{{ $message->user->name }}" class="w-8 h-8 rounded-full object-cover flex-shrink-0">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <p class="font-semibold text-white text-sm">{{ $message->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $message->created_at->format('H:i') }}</p>
                                </div>
                                <div class="bg-slate-700 px-3 py-2 rounded-lg inline-block">
                                    <p class="text-gray-100 text-sm">{{ $message->content }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400">
                            <p>No messages yet. Say hello!</p>
                        </div>
                    @endforelse
                </div>

                <!-- Message Input -->
                <div class="bg-slate-700/50 border-t border-blue-700/20 p-4">
                    <form wire:submit.prevent="sendGroupMessage" class="flex gap-3">
                        <input type="text" wire:model="messageContent" placeholder="Type a message..." class="flex-1 px-4 py-2 rounded-full bg-slate-800 text-white placeholder-gray-500 border border-blue-700/20 focus:border-blue-700 focus:outline-none transition text-sm">
                        <button type="submit" class="p-2 bg-gradient-to-r from-blue-700 to-black text-white rounded-full hover:shadow-lg transition">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M16.6915026,12.4744748 L3.50612381,13.2599618 C3.19218622,13.2599618 3.03521743,13.4170592 3.03521743,13.5741566 L1.15159189,20.0151496 C0.8376543,20.8006365 0.99,21.89 1.77946707,22.52 C2.41,22.99 3.50612381,23.1 4.13399899,22.8429026 L21.714504,14.0454487 C22.6563168,13.5741566 23.1272231,12.6315722 22.9702544,11.6889879 L4.13399899,1.16346272 C3.34915502,0.9 2.40734225,1.00636533 1.77946707,1.4776575 C0.994623095,2.10604706 0.837654326,3.0486314 1.15159189,3.99021575 L3.03521743,10.4310088 C3.03521743,10.5881061 3.34915502,10.7452035 3.50612381,10.7452035 L16.6915026,11.5306905 C16.6915026,11.5306905 17.1624089,11.5306905 17.1624089,12.0019827 C17.1624089,12.4744748 16.6915026,12.4744748 16.6915026,12.4744748 Z"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="md:col-span-2 flex items-center justify-center bg-slate-800/50 rounded-2xl border border-blue-700/20">
                <div class="text-center">
                    <div class="w-20 h-20 bg-gradient-to-br from-blue-700 to-black rounded-full mx-auto mb-4 opacity-20"></div>
                    <p class="text-gray-400">Select or create a group to start chatting</p>
                </div>
            </div>
        @endif
    </div>
</div>

