
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-black pb-28">
    <livewire:layout.sidebar />

    <div class="px-4 py-8 mx-auto max-w-3xl" x-data="{ searchExpanded: false }">
        <!-- Tabs with Search State -->
        <div class="sticky top-0 z-40 bg-gradient-to-r from-slate-900/95 to-black/95 border-b border-blue-700/20 backdrop-blur">
            <div class="flex items-center justify-between gap-2 px-4 py-4">
                <div class="flex gap-2 overflow-x-auto">
                    <button wire:click="$set('activeTab', 'users')"
                        class="px-6 py-3 font-semibold {{ $activeTab === 'users' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-white' }} transition whitespace-nowrap">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                            Users
                        </span>
                    </button>
                    <button wire:click="$set('activeTab', 'albums')"
                        class="px-6 py-3 font-semibold {{ $activeTab === 'albums' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-white' }} transition whitespace-nowrap">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                            Albums
                        </span>
                    </button>
                </div>
                <!-- Search Icon Button -->
                <button type="button" @click="searchExpanded = !searchExpanded; searchExpanded && $nextTick(() => $refs.exploreSearch.focus())" class="flex items-center justify-center w-9 h-9 rounded-full bg-slate-700 text-blue-300 hover:bg-slate-600 hover:scale-110 transition shrink-0" aria-label="Toggle search">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </div>
            <div x-show="searchExpanded" x-transition:enter="transition-all duration-300 ease-out" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition-all duration-200 ease-in" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4" class="overflow-hidden border-b border-blue-700/20" style="display: none;">
                <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border border-blue-700/20 p-6">
                    <div class="relative z-50">
                        <div class="flex items-center gap-3 px-4 py-3 rounded-xl border border-slate-600/50 bg-slate-800/70">
                            <button type="button" @click="showRecent = !showRecent; $refs.exploreSearch.focus();" class="flex items-center justify-center w-9 h-9 rounded-full bg-slate-700 text-blue-300 hover:bg-slate-600 transition" aria-label="Toggle recent searches">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </button>
                            <input
                                x-ref="exploreSearch"
                                @focus="showRecent = true"
                                type="text"
                                wire:model.live.debounce.500ms="searchQuery"
                                placeholder="Search users, albums, categories..."
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
                </div>
            </div>
        </div>
        <!-- Content: separate section below tab bar -->
        <div class="mt-6">
            @if($activeTab === 'users')
                @if($users->count() > 0)
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($users as $user)
                            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-blue-700/20 p-6 hover:border-blue-700/50 transition group">
                                <!-- User Avatar -->
                                <div class="w-24 h-24 rounded-full mx-auto mb-4 bg-gradient-to-br from-blue-700 to-black flex items-center justify-center">
                                    <span class="text-4xl font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                                <!-- User Info -->
                                <div class="text-center mb-4">
                                    <h3 class="text-lg font-bold text-white group-hover:text-blue-600 transition">{{ $user->name }}</h3>
                                    <p class="text-blue-600">{{ '@' . ($user->username ?? strtolower(str_replace(' ', '', $user->name))) }}</p>
                                    <p class="text-sm text-gray-400 mt-2">{{ $user->bio ?? 'No bio' }}</p>
                                </div>
                                <!-- Stats -->
                                <div class="grid grid-cols-3 gap-2 mb-4 py-4 border-y border-blue-700/20">
                                    <div class="text-center">
                                        <p class="text-lg font-bold text-blue-600">{{ $user->posts()->count() }}</p>
                                        <p class="text-xs text-gray-400">Posts</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-lg font-bold text-blue-600">{{ $user->followers()->count() }}</p>
                                        <p class="text-xs text-gray-400">Followers</p>
                                    </div>
                                    <div class="text-center">
                                        <p class="text-lg font-bold text-blue-600">{{ $user->albums()->count() }}</p>
                                        <p class="text-xs text-gray-400">Albums</p>
                                    </div>
                                </div>
                                <!-- Action Buttons -->
                                <div class="flex gap-2">
                                    <button wire:click="toggleFollow({{ $user->id }})" class="flex-1 py-2 px-4 rounded-lg font-semibold text-sm transition cursor-pointer
                                        {{ auth()->user()->isFollowing($user)
                                            ? 'bg-slate-700 text-gray-200 hover:bg-slate-600'
                                            : 'bg-gradient-to-r from-blue-700 to-black text-white hover:shadow-lg' }}">
                                        {{ auth()->user()->isFollowing($user) ? 'Following' : 'Follow' }}
                                    </button>
                                    <a href="{{ route('profile') }}" class="flex-1 py-2 px-4 bg-slate-700 text-white rounded-lg font-semibold text-sm hover:bg-slate-600 transition text-center">
                                        View
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-5xl mb-3">🔍</div>
                        <p class="text-gray-400">No users found</p>
                    </div>
                @endif
            @endif
            @if($activeTab === 'albums')
                @if($albums->count() > 0)
                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($albums as $album)
                            <a href="{{ route('dashboard', ['album' => $album->id]) }}" class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-blue-700/20 p-4 hover:border-blue-700/50 transition group">
                                <div class="aspect-square rounded-lg bg-gradient-to-r from-blue-700 to-black mb-4 flex items-center justify-center overflow-hidden relative">
                                    <div class="absolute inset-0 opacity-20" style="background: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.1\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E'); background-repeat: repeat;"></div>
                                    <span class="text-3xl">🖼️</span>
                                </div>
                                <h4 class="font-bold text-white group-hover:text-blue-600 transition truncate">{{ $album->title }}</h4>
                                <p class="text-sm text-blue-600">by {{ $album->user->name }}</p>
                                <p class="text-xs text-gray-400 mt-2">{{ $album->posts()->count() }} posts</p>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <p class="text-gray-400">No public albums yet</p>
                    </div>
                @endif
            @endif
        </div>
    </div>

