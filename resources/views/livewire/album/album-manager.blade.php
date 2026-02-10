<div class="min-h-screen pb-32 bg-gradient-to-br from-slate-900 via-slate-800 to-black">
    <div class="flex">
        <main class="flex-1 p-4 mb-8 overflow-y-auto md:p-8">
            <div class="max-w-6xl mx-auto">
                <div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-black" x-data="{ activeTab: 'my-albums', searchExpanded: false, showRecent: false }" @click.away="showRecent = false">
                    <div class="px-6 py-8 mx-auto max-w-7xl">
                        @if(session()->has('success'))
                            <div class="p-4 mb-6 text-green-400 border rounded-lg bg-green-500/20 border-green-500/50">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Header with Tabs -->
                        <div class="mb-8">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex-1">
                                    <h1 class="text-2xl font-bold text-transparent md:text-3xl bg-gradient-to-r from-blue-400 to-black bg-clip-text">Your Albums</h1>
                                    <p class="text-sm text-gray-400">Organize posts into albums (formerly chapters)</p>
                                </div>
                                <!-- Search Icon Button -->
                                <button type="button" @click="searchExpanded = !searchExpanded; searchExpanded && $nextTick(() => $refs.albumsSearch.focus())" class="flex items-center justify-center mr-4 text-blue-300 transition rounded-full w-9 h-9 bg-slate-700 hover:bg-slate-600 hover:scale-110 shrink-0" aria-label="Toggle search">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </button>
                                <button wire:click="openCreateModal" class="px-4 py-2 text-sm font-semibold text-white transition rounded-lg bg-gradient-to-r from-blue-600 to-black hover:shadow-lg hover:shadow-blue-500/50">+ New Album</button>
                            </div>

                            <!-- Collapsible Search Bar -->
                            <div x-show="searchExpanded" x-transition:enter="transition-all duration-300 ease-out" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition-all duration-200 ease-in" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4" class="mb-6 overflow-hidden" style="display: none;">
                                <div class="relative">
                                    <div class="flex items-center gap-3 px-4 py-3 border rounded-xl border-slate-600/50 bg-slate-800/70">
                                        <button type="button" @click="showRecent = !showRecent; $refs.albumsSearch.focus();" class="flex items-center justify-center text-blue-300 transition rounded-full w-9 h-9 bg-slate-700 hover:bg-slate-600" aria-label="Toggle recent searches">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                            </svg>
                                        </button>
                                        <input
                                            x-ref="albumsSearch"
                                            @focus="showRecent = true"
                                            type="text"
                                            wire:model.live.debounce.500ms="searchQuery"
                                            placeholder="Search albums by title, description, or category..."
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

                            <!-- Tabs Navigation -->
                            <div class="flex gap-2 border-b border-blue-700/20">
                                <button @click="activeTab = 'my-albums'" :class="activeTab === 'my-albums' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-white'" class="px-6 py-3 font-semibold transition">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M21 5c-1.11-.35-2.33-.5-3.5-.5-1.95 0-4.05.4-5.5 1.5-1.45-1.1-3.55-1.5-5.5-1.5S2.45 4.9 1 6v14.65c0 .25.25.5.5.5.1 0 .15-.05.25-.05C3.1 20.45 5.05 20 6.5 20c1.95 0 4.05.4 5.5 1.5 1.35-.85 3.8-1.5 5.5-1.5 1.65 0 3.35.3 4.75 1.05.1.05.15.05.25.05.25 0 .5-.25.5-.5V6c-.6-.45-1.25-.75-2-1zm0 13.5c-1.1-.35-2.3-.5-3.5-.5-1.7 0-4.15.65-5.5 1.5V8c1.35-.85 3.8-1.5 5.5-1.5 1.2 0 2.4.15 3.5.5v11.5z"/></svg>
                                        My Albums
                                    </span>
                                </button>
                                <button @click="activeTab = 'favorites'" :class="activeTab === 'favorites' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-white'" class="px-6 py-3 font-semibold transition">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                                        Favorites
                                    </span>
                                </button>
                                <button @click="activeTab = 'recent'" :class="activeTab === 'recent' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-white'" class="px-6 py-3 font-semibold transition">
                                    <span class="flex items-center gap-2">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                                        Recently Viewed
                                    </span>
                                </button>
                            </div>
                        </div>

                        <!-- My Albums Tab Content -->
                        <div x-show="activeTab === 'my-albums'">
                        @if ($albums->count() > 0)
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                                @foreach ($albums as $album)
                                    <div class="overflow-hidden transition border bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border-blue-700/20 hover:border-blue-700/40">
                                        <div class="flex items-start gap-4 p-6">
                                            <div class="flex items-center justify-center w-12 h-12 text-xl text-white rounded-xl bg-gradient-to-br from-blue-600 to-black">
                                                🖼️
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <h3 class="font-bold text-white truncate">{{ $album->title }}</h3>
                                                    @if ($album->visibility === 'public')
                                                        <span class="px-2 py-0.5 text-xs rounded-lg bg-green-500/10 border border-green-500/30 text-green-200">Public</span>
                                                    @else
                                                        <span class="px-2 py-0.5 text-xs rounded-lg bg-yellow-500/10 border border-yellow-500/30 text-yellow-200">Private</span>
                                                    @endif
                                                    @if ($album->category)
                                                        <span class="px-2 py-0.5 text-xs rounded-lg bg-blue-500/10 border border-blue-500/30 text-blue-200">{{ $album->category }}</span>
                                                    @endif
                                                </div>
                                                <p class="text-sm text-gray-300 line-clamp-2">{{ $album->description }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-4 px-6 pb-6 text-sm text-gray-300">
                                            <span class="px-2 py-1 border rounded-lg bg-white/5 border-blue-700/20">📝 {{ $album->posts_count }} Posts</span>
                                            <span class="px-2 py-1 border rounded-lg bg-white/5 border-blue-700/20">👁️ {{ $album->views_count }} Views</span>
                                            <span class="px-2 py-1 border rounded-lg bg-white/5 border-blue-700/20">⭐ {{ $album->favorites_count }} Favorites</span>
                                        </div>
                                        <div class="flex gap-3 px-6 pb-6">
                                            <a href="{{ route('album.posts', $album->id) }}" class="px-3 py-2 text-sm text-gray-200 rounded-lg bg-slate-700/50 hover:bg-slate-700" title="View posts in album">View Posts</a>
                                            <button type="button" wire:click="startEdit({{ $album->id }})" class="px-3 py-2 text-sm text-gray-200 rounded-lg bg-slate-700/50 hover:bg-slate-700">Edit</button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-8 text-center border rounded-2xl border-blue-700/20 bg-gradient-to-br from-slate-800/50 to-slate-900/50">
                                <div class="mb-3 text-4xl">🗂️</div>
                                <h3 class="mb-2 font-bold text-white">No Albums Yet</h3>
                                <p class="mb-4 text-gray-300">Create your first album to organize your posts beautifully.</p>
                                <button type="button" wire:click="openCreateModal" class="inline-block px-4 py-2 text-sm font-semibold text-white transition rounded-lg bg-gradient-to-r from-blue-600 to-black hover:shadow-lg hover:shadow-blue-500/50">+ Create Album</button>
                            </div>
                        @endif
                    </div>

                    <!-- Create Album Modal -->
                    @if($showCreateModal)
                        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" wire:key="album-modal">
                            <div class="w-full max-w-lg p-6 border bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border-blue-700/30">
                                <div class="flex items-center justify-between mb-6">
                                    <h2 class="text-xl font-bold text-white">{{ $isEditing ? 'Edit Album' : 'Create New Album' }}</h2>
                                    <button wire:click="closeCreateModal" class="text-gray-400 transition hover:text-white">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                                <form wire:submit.prevent="{{ $isEditing ? 'updateAlbum' : 'createAlbum' }}" class="space-y-4">
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-300">Album Title *</label>
                                        <input type="text" wire:model="title" placeholder="Enter album title..." class="w-full px-4 py-2 text-white placeholder-gray-500 transition border rounded-lg bg-slate-700 border-blue-700/20 focus:border-blue-700 focus:outline-none">
                                        @error('title') <span class="mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-300">Description</label>
                                        <textarea wire:model="description" placeholder="Describe your album..." rows="3" class="w-full px-4 py-2 text-white placeholder-gray-500 transition border rounded-lg bg-slate-700 border-blue-700/20 focus:border-blue-700 focus:outline-none"></textarea>
                                        @error('description') <span class="mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
                                    </div>

                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-300">Visibility *</label>
                                        <div class="grid grid-cols-2 gap-3">
                                            <button type="button"
                                                wire:click="$set('visibility', 'public')"
                                                class="relative flex items-center justify-center p-3 rounded-lg cursor-pointer transition border {{ $visibility === 'public' ? 'bg-gradient-to-r from-blue-600 to-black border-blue-700' : 'bg-slate-700/50 border-blue-700/20' }}">
                                                <span class="text-sm font-medium text-white">🌍 Public</span>
                                            </button>
                                            <button type="button"
                                                wire:click="$set('visibility', 'private')"
                                                class="relative flex items-center justify-center p-3 rounded-lg cursor-pointer transition border {{ $visibility === 'private' ? 'bg-gradient-to-r from-blue-600 to-black border-blue-700' : 'bg-slate-700/50 border-blue-700/20' }}">
                                                <span class="text-sm font-medium text-white">🔒 Private</span>
                                            </button>
                                        </div>
                                        @error('visibility') <span class="mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-gray-300">Category</label>

                                        <!-- Toggle between predefined and custom -->
                                        <div class="flex gap-2 mb-3">
                                            <button type="button"
                                                wire:click="$set('useCustomCategory', false)"
                                                class="px-3 py-1.5 text-xs rounded-lg transition {{ !$useCustomCategory ? 'bg-blue-500 text-white' : 'bg-slate-700/50 text-gray-300 hover:bg-slate-700' }}">
                                                📋 Choose from list
                                            </button>
                                            <button type="button"
                                                wire:click="$set('useCustomCategory', true)"
                                                class="px-3 py-1.5 text-xs rounded-lg transition {{ $useCustomCategory ? 'bg-blue-500 text-white' : 'bg-slate-700/50 text-gray-300 hover:bg-slate-700' }}">
                                                ✏️ Write custom
                                            </button>
                                        </div>

                                        @if($useCustomCategory)
                                            <!-- Custom Category Input -->
                                            <input type="text"
                                                wire:model="customCategory"
                                                placeholder="Enter your custom category..."
                                                class="w-full px-4 py-2 text-white placeholder-gray-500 transition border rounded-lg bg-slate-700 border-blue-700/20 focus:border-blue-700 focus:outline-none">
                                            @error('customCategory') <span class="mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
                                        @else
                                            <!-- Predefined Categories Select -->
                                            <select wire:model="category"
                                                class="w-full px-4 py-2 text-white transition border rounded-lg bg-slate-700 border-blue-700/20 focus:border-blue-700 focus:outline-none">
                                                <option value="">Select a category...</option>
                                                @foreach($categoryOptions as $value => $label)
                                                    <option value="{{ $value }}">{{ $label }}</option>
                                                @endforeach
                                            </select>
                                            @error('category') <span class="mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
                                        @endif
                                    </div>
                                    <div class="flex gap-3 pt-4">
                                        <button type="button" wire:click="closeCreateModal" class="flex-1 px-4 py-2 font-medium text-white transition rounded-lg bg-slate-700 hover:bg-slate-600">Cancel</button>
                                        <button type="submit" class="flex-1 px-4 py-2 font-semibold text-white transition rounded-lg bg-gradient-to-r from-blue-600 to-black hover:shadow-lg hover:shadow-blue-500/50">{{ $isEditing ? 'Save Changes' : 'Create Album' }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
                <!-- Favorites Tab Content -->
                <div x-show="activeTab === 'favorites'">
                    <div class="py-12 text-center">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                        <p class="text-lg font-semibold text-gray-400">No favorite albums</p>
                        <p class="mt-2 text-sm text-gray-500">Your favorite albums will appear here</p>
                    </div>
                </div>

                <!-- Recently Viewed Tab Content -->
                <div x-show="activeTab === 'recent'">
                    <div class="py-12 text-center">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-600" fill="currentColor" viewBox="0 0 24 24"><path d="M13 3a9 9 0 00-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42A8.954 8.954 0 0013 21a9 9 0 000-18zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8H12z"/></svg>
                        <p class="text-lg font-semibold text-gray-400">No recently viewed albums</p>
                        <p class="mt-2 text-sm text-gray-500">Albums you view will appear here</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>



