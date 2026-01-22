<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-black">
    <div class="flex">
        <!-- Sidebar -->
        @livewire('layout.sidebar')
        
        <!-- Main Content -->
        <div class="flex-1 px-4 py-8 mx-auto max-w-7xl">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="flex items-center gap-3 mb-2 text-4xl font-bold text-white">
                    <svg class="w-8 h-8 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                    Followers & Following
                </h1>
                <p class="text-gray-400">Manage who follows you and who you follow</p>
            </div>

            <!-- Tabs -->
            <div class="flex gap-2 mb-8 border-b border-blue-700/20">
                <button
                    wire:click="$set('activeTab', 'followers')"
                    class="px-6 py-3 font-semibold {{ $activeTab === 'followers' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-white' }} transition"
                >
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        Followers ({{ auth()->user()->followers()->count() }})
                    </span>
                </button>
                <button
                    wire:click="$set('activeTab', 'following')"
                    class="px-6 py-3 font-semibold {{ $activeTab === 'following' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-white' }} transition"
                >
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.89 1.97 1.74 1.97 2.95V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                        Following ({{ auth()->user()->following()->count() }})
                    </span>
                </button>
            </div>

            <!-- Content -->
            <div>
                <!-- Followers Tab -->
                @if($activeTab === 'followers')
                    <!-- Search Bar -->
                    <div class="mb-6">
                        <div class="relative">
                            <input 
                                type="text" 
                                wire:model.live="followersSearch"
                                placeholder="Search followers..." 
                                class="w-full px-4 py-3 bg-slate-700/50 border border-blue-700/30 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition"
                            >
                            <svg class="absolute right-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                        @forelse($followers as $followerRel)
                            @php
                                $user = $followerRel->follower;
                            @endphp
                            @if ($user)
                                <div class="overflow-hidden transition border bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl border-blue-700/20 hover:border-blue-700/40">
                                    <div class="p-6">
                                        <!-- User Avatar -->
                                        <div class="flex items-center justify-center mb-4">
                                            <div class="flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-blue-700 to-black ring-4 ring-blue-500/30">
                                                <span class="text-3xl font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                        </div>

                                        <!-- User Info -->
                                        <h3 class="mb-1 text-lg font-bold text-center text-white">{{ $user->name }}</h3>
                                        <p class="mb-4 text-sm text-center text-gray-400">{{ '@' . ($user->username ?? strtolower(str_replace(' ', '', $user->name))) }}</p>

                                        <!-- Stats -->
                                        <div class="grid grid-cols-3 gap-2 py-3 mb-4 border-y border-blue-700/20">
                                            <a href="{{ route('user.posts', $user->id) }}" class="text-center hover:opacity-80 transition">
                                                <p class="text-xl font-bold text-blue-400">{{ $user->posts()->count() }}</p>
                                                <p class="text-xs text-blue-400 font-semibold hover:underline">Posts</p>
                                            </a>
                                            <div class="text-center">
                                                <p class="text-xl font-bold text-blue-400">{{ $user->followers()->count() }}</p>
                                                <p class="text-xs text-gray-400">Followers</p>
                                            </div>
                                            <div class="text-center relative">
                                                <a href="{{ route('user.albums', $user->id) }}" class="block hover:opacity-80 transition">
                                                    <p class="text-xl font-bold text-blue-400">{{ $user->albums()->count() }}</p>
                                                    <p class="text-xs text-blue-400 font-semibold hover:underline">Albums</p>
                                                </a>
                                                <!-- Message Icon Button -->
                                                <div class="absolute top-0 right-0">
                                                    @if ($user->isFollowing(auth()->user()))
                                                        <button 
                                                            type="button"
                                                            class="start-conversation p-1 text-green-400 transition hover:text-green-300"
                                                            data-user-id="{{ $user->id }}"
                                                            title="Send Message">
                                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
                                                            </svg>
                                                        </button>
                                                    @else
                                                        <button 
                                                            type="button"
                                                            class="p-1 text-gray-500 transition cursor-not-allowed"
                                                            disabled
                                                            title="User must follow you to message">
                                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                                                <path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/>
                                                            </svg>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Status Badge -->
                                        @if (auth()->user()->isFollowing($user))
                                            <div class="p-3 mb-4 text-center border rounded-lg bg-green-600/20 border-green-500/30">
                                                <p class="text-sm font-semibold text-green-400">✓ You follow them</p>
                                            </div>
                                        @endif

                                        <!-- Actions -->
                                        <button
                                            type="button"
                                            wire:click="toggleFollow({{ $user->id }})"
                                            class="w-full px-4 py-2 font-semibold text-white transition rounded-lg {{ !auth()->user()->isFollowing($user) ? 'bg-gradient-to-r from-green-600 to-green-700 hover:shadow-lg hover:shadow-green-500/40' : 'text-gray-400 bg-gray-600/20 hover:bg-gray-600/30 border border-gray-500/30' }}"
                                        >
                                            {{ auth()->user()->isFollowing($user) ? 'Unfollow' : 'Follow' }}
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="py-12 text-center col-span-full">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <p class="text-lg text-gray-400">You have no followers yet</p>
                                <p class="text-sm text-gray-500">Start creating great content to attract followers</p>
                            </div>
                        @endforelse
                    </div>
                @endif

                <!-- Following Tab -->
                @if($activeTab === 'following')
                    <!-- Search Bar -->
                    <div class="mb-6">
                        <div class="relative">
                            <input 
                                type="text" 
                                wire:model.live="followingSearch"
                                placeholder="Search following..." 
                                class="w-full px-4 py-3 bg-slate-700/50 border border-purple-700/30 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition"
                            >
                            <svg class="absolute right-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                        @forelse($following as $followingRel)
                            @php
                                $user = $followingRel->following;
                                $followsBack = auth()->user()->followers()->where('follower_id', $user->id)->exists();
                            @endphp
                            @if ($user)
                                <div class="overflow-hidden transition border bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl border-purple-700/20 hover:border-purple-700/40">
                                    <div class="p-6">
                                        <!-- User Avatar -->
                                        <div class="flex items-center justify-center mb-4">
                                            <div class="flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-purple-700 to-black ring-4 ring-purple-500/30">
                                                <span class="text-3xl font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                        </div>

                                        <!-- User Info -->
                                        <h3 class="mb-1 text-lg font-bold text-center text-white">{{ $user->name }}</h3>
                                        <p class="mb-4 text-sm text-center text-gray-400">{{ '@' . ($user->username ?? strtolower(str_replace(' ', '', $user->name))) }}</p>

                                        <!-- Stats -->
                                        <div class="grid grid-cols-3 gap-2 py-3 mb-4 border-y border-purple-700/20">
                                            <a href="{{ route('user.posts', $user->id) }}" class="text-center hover:opacity-80 transition">
                                                <p class="text-xl font-bold text-purple-400">{{ $user->posts()->count() }}</p>
                                                <p class="text-xs text-purple-400 font-semibold hover:underline">Posts</p>
                                            </a>
                                            <div class="text-center">
                                                <p class="text-xl font-bold text-purple-400">{{ $user->followers()->count() }}</p>
                                                <p class="text-xs text-gray-400">Followers</p>
                                            </div>
                                            <div class="text-center">
                                                <a href="{{ route('user.albums', $user->id) }}" class="block hover:opacity-80 transition">
                                                    <p class="text-xl font-bold text-purple-400">{{ $user->albums()->count() }}</p>
                                                    <p class="text-xs text-purple-400 font-semibold hover:underline">Albums</p>
                                                </a>
                                            </div>
                                        </div>

                                        <!-- Status Badge -->
                                        @if ($followsBack)
                                            <div class="p-3 mb-4 text-center border rounded-lg bg-green-600/20 border-green-500/30">
                                                <p class="text-sm font-semibold text-green-400">✓ Follows you back</p>
                                            </div>
                                        @endif

                                        <!-- Actions -->
                                        <button
                                            type="button"
                                            wire:click="toggleFollow({{ $user->id }})"
                                            class="w-full px-4 py-2 font-semibold text-white transition rounded-lg text-gray-400 bg-gray-600/20 hover:bg-gray-600/30 border border-gray-500/30"
                                        >
                                            Unfollow
                                        </button>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="py-12 text-center col-span-full">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                                <p class="text-lg text-gray-400">You are not following anyone yet</p>
                                <p class="text-sm text-gray-500">Explore and follow users to stay updated</p>
                            </div>
                        @endforelse
                    </div>
                @endif
            </div>
        </div>
    </div>

    @once
        @push('scripts')
            <script>
                document.addEventListener('livewire:navigated', function () {
                    // Message button handler
                    document.querySelectorAll('.start-conversation').forEach(function (btn) {
                        btn.addEventListener('click', function () {
                            const userId = this.getAttribute('data-user-id');
                            fetch('/api/conversations/create', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({ user_id: userId })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success && data.conversation_id) {
                                    window.location.href = `/conversations/${data.conversation_id}`;
                                }
                            });
                        });
                    });
                });
            </script>
        @endpush
    @endonce
</div>
