<div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900">
    <div class="flex">
        @livewire('layout.sidebar')
        <div class="flex-1 px-4 py-8 mx-auto max-w-3xl" x-data="{ activeTab: 'followers', followersSearch: '', followingSearch: '' }">
            <div class="mb-6">
                <a href="{{ url()->previous() }}" class="inline-flex items-center px-3 py-2 text-sm font-semibold text-blue-400 bg-slate-800 rounded-full shadow hover:bg-blue-700 hover:text-white transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Back
                </a>
            </div>
            <div class="z-40 bg-gradient-to-r from-slate-900/95 to-black/95 border-b border-blue-700/20 backdrop-blur">
                <div class="flex gap-2 px-4 py-4">
                    <button @click="activeTab = 'followers'" :class="activeTab === 'followers' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-white'" class="px-6 py-3 font-semibold transition whitespace-nowrap">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                            Followers ({{ $follower->followers()->count() }})
                        </span>
                    </button>
                    <button @click="activeTab = 'following'" :class="activeTab === 'following' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-white'" class="px-6 py-3 font-semibold transition whitespace-nowrap">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.89 1.97 1.74 1.97 2.95V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                            Following ({{ $follower->following()->count() }})
                        </span>
                    </button>
                </div>
            </div>
            <div class="mt-6">
                <!-- Followers Tab -->
                <div x-show="activeTab === 'followers'">
                    <div class="mb-6">
                        <div class="relative">
                            <input type="text" wire:model.live="followersSearch" placeholder="Search followers..." class="w-full px-4 py-3 bg-slate-700/50 border border-blue-700/30 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition">
                            <svg class="absolute right-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        @forelse($this->filteredFollowers as $followerRel)
                            @php $user = $followerRel->follower; @endphp
                            @if ($user)
                                <div class="overflow-hidden transition border bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl border-blue-700/20 hover:border-blue-700/40 shadow-2xl backdrop-blur-xl">
                                    <div class="p-6">
                                        <div class="flex items-center justify-center mb-4">
                                            <a href="{{ route('user.profile', $user->id) }}" class="flex items-center justify-center rounded-full shadow-lg w-14 h-14 bg-gradient-to-br from-blue-700 to-black hover:scale-105 transition">
                                                <span class="text-3xl font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                                            </a>
                                        </div>
                                        <h3 class="mb-1 text-lg font-bold text-center text-white">{{ $user->name }}</h3>
                                        <p class="mb-4 text-sm text-center text-gray-400">{{ '@' . ($user->username ?? strtolower(str_replace(' ', '', $user->name))) }}</p>
                                        <div class="grid grid-cols-3 gap-2 py-3 mb-4 border-y border-blue-700/20">
                                            <a href="{{ route('user.posts', $user->id) }}" class="text-center hover:opacity-80 transition">
                                                <p class="text-xl font-bold text-blue-400">{{ $user->posts()->count() }}</p>
                                                <p class="text-xs text-blue-400 font-semibold hover:underline">Posts</p>
                                            </a>
                                            <div class="text-center">
                                                <p class="text-xl font-bold text-blue-400">{{ $user->followers()->count() }}</p>
                                                <p class="text-xs text-gray-400">Followers</p>
                                            </div>
                                            <div class="text-center">
                                                <a href="{{ route('user.albums', $user->id) }}" class="block hover:opacity-80 transition">
                                                    <p class="text-xl font-bold text-blue-400">{{ $user->albums()->count() }}</p>
                                                    <p class="text-xs text-blue-400 font-semibold hover:underline">Albums</p>
                                                </a>
                                            </div>
                                        </div>
                                        @if(auth()->user()->following()->where('following_id', $user->id)->exists())
                                            <button type="button" wire:click="toggleFollow({{ $user->id }})" class="w-full px-4 py-2 font-semibold transition rounded-lg text-purple-400 border border-purple-400 bg-transparent hover:bg-purple-700 hover:text-white">
                                                Unfollow
                                            </button>
                                        @else
                                            <button type="button" wire:click="toggleFollow({{ $user->id }})" class="w-full px-4 py-2 font-semibold transition rounded-lg text-white bg-blue-600 hover:bg-blue-700 shadow">
                                                Follow
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="py-12 text-center col-span-full">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <p class="text-lg text-gray-400">No followers yet</p>
                                <p class="text-sm text-gray-500">Start creating great content to attract followers</p>
                            </div>
                        @endforelse
                    </div>
                </div>
                <!-- Following Tab -->
                <div x-show="activeTab === 'following'">
                    <div class="mb-6">
                        <div class="relative">
                            <input type="text" wire:model.live="followingSearch" placeholder="Search following..." class="w-full px-4 py-3 bg-slate-700/50 border border-purple-700/30 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition">
                            <svg class="absolute right-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        @forelse($this->filteredFollowing as $followingRel)
                            @php $user = $followingRel->following; @endphp
                            @if ($user)
                                <div class="overflow-hidden transition border bg-gradient-to-br from-slate-800 to-slate-900 rounded-xl border-purple-700/20 hover:border-purple-700/40 shadow-2xl backdrop-blur-xl">
                                    <div class="p-6">
                                        <div class="flex items-center justify-center mb-4">
                                            <a href="{{ route('user.posts', $user->id) }}" class="flex items-center justify-center rounded-full shadow-lg w-14 h-14 bg-gradient-to-br from-purple-700 to-black hover:scale-105 transition">
                                                <span class="text-3xl font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                                            </a>
                                        </div>
                                        <h3 class="mb-1 text-lg font-bold text-center text-white">{{ $user->name }}</h3>
                                        <p class="mb-4 text-sm text-center text-gray-400">{{ '@' . ($user->username ?? strtolower(str_replace(' ', '', $user->name))) }}</p>
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
                                        @if(auth()->user()->following()->where('following_id', $user->id)->exists())
                                            <button type="button" wire:click="toggleFollow({{ $user->id }})" class="w-full px-4 py-2 font-semibold transition rounded-lg text-purple-400 border border-purple-400 bg-transparent hover:bg-purple-700 hover:text-white">
                                                Unfollow
                                            </button>
                                        @else
                                            <button type="button" wire:click="toggleFollow({{ $user->id }})" class="w-full px-4 py-2 font-semibold transition rounded-lg text-white bg-blue-600 hover:bg-blue-700 shadow">
                                                Follow
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="py-12 text-center col-span-full">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                                <p class="text-lg text-gray-400">Not following anyone yet</p>
                                <p class="text-sm text-gray-500">Explore and follow users to stay updated</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="h-32"></div>
</div>
