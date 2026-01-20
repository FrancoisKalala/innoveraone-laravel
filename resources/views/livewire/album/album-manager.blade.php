<div class="min-h-screen bg-gradient-to-br from-slate-950 via-purple-950 via-30% via-pink-950 via-60% to-slate-900 relative">
    <!-- Animated background elements -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse"></div>
        <div class="absolute top-20 right-1/4 w-96 h-96 bg-pink-600 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse" style="animation-delay: 2s"></div>
        <div class="absolute -bottom-8 left-1/2 w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse" style="animation-delay: 4s"></div>
    </div>
    <div class="max-w-7xl mx-auto px-6 py-8 relative z-10">
        @if(session()->has('success'))
            <div class="mb-6 p-4 bg-green-500/20 border border-green-500/50 rounded-lg text-green-400">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold bg-gradient-to-r from-purple-400 to-pink-400 bg-clip-text text-transparent">Your Albums</h1>
                <p class="text-sm text-gray-400">Organize posts into albums (formerly chapters)</p>
            </div>
            <button wire:click="openCreateModal" class="px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:shadow-lg hover:shadow-purple-500/50 transition text-sm font-semibold">+ New Album</button>
        </div>

        @if ($albums->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($albums as $album)
                    <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border border-blue-700/20 overflow-hidden hover:border-blue-700/40 transition">
                        <div class="p-6 flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-white text-xl">
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
                                </div>
                                <p class="text-gray-300 text-sm line-clamp-2">{{ $album->description }}</p>
                            </div>
                        </div>
                        <div class="px-6 pb-6 flex items-center gap-4 text-sm text-gray-300">
                            <span class="px-2 py-1 rounded-lg bg-white/5 border border-blue-700/20">📝 {{ $album->posts_count }} Posts</span>
                            <span class="px-2 py-1 rounded-lg bg-white/5 border border-blue-700/20">👁️ {{ $album->views_count }} Views</span>
                            <span class="px-2 py-1 rounded-lg bg-white/5 border border-blue-700/20">⭐ {{ $album->favorites_count }} Favorites</span>
                        </div>
                        <div class="px-6 pb-6 flex gap-3">
                            <a href="{{ route('dashboard', ['album' => $album->id]) }}" class="px-3 py-2 text-sm rounded-lg bg-slate-700/50 text-gray-200 hover:bg-slate-700">View Posts</a>
                            <a href="#" class="px-3 py-2 text-sm rounded-lg bg-slate-700/50 text-gray-200 hover:bg-slate-700">Edit</a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-2xl border border-blue-700/20 bg-gradient-to-br from-slate-800/50 to-slate-900/50 p-8 text-center">
                <div class="text-4xl mb-3">🗂️</div>
                <h3 class="text-white font-bold mb-2">No Albums Yet</h3>
                <p class="text-gray-300 mb-4">Create your first album to organize your posts beautifully.</p>
                <a href="#" class="inline-block px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:shadow-lg hover:shadow-purple-500/50 transition text-sm font-semibold">+ Create Album</a>
            </div>
        @endif
    </div>

    <!-- Create Album Modal -->
    @if($showCreateModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-blue-700/30 max-w-lg w-full p-6">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-white">Create New Album</h2>
                    <button wire:click="closeCreateModal" class="text-gray-400 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <form wire:submit.prevent="createAlbum" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Album Title *</label>
                        <input type="text" wire:model="title" placeholder="Enter album title..." class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white placeholder-gray-500 border border-blue-700/20 focus:border-blue-700 focus:outline-none transition">
                        @error('title') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                        <textarea wire:model="description" placeholder="Describe your album..." rows="3" class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white placeholder-gray-500 border border-blue-700/20 focus:border-blue-700 focus:outline-none transition"></textarea>
                        @error('description') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Visibility *</label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="relative flex items-center justify-center p-3 rounded-lg cursor-pointer transition border"
                                :class="$wire.visibility === 'public' ? 'bg-gradient-to-r from-purple-500 to-pink-500 border-blue-700' : 'bg-slate-700/50 border-blue-700/20'">
                                <input type="radio" wire:model.live="visibility" value="public" class="sr-only">
                                <span class="text-white font-medium text-sm">🌍 Public</span>
                            </label>
                            <label class="relative flex items-center justify-center p-3 rounded-lg cursor-pointer transition border"
                                :class="$wire.visibility === 'private' ? 'bg-gradient-to-r from-purple-500 to-pink-500 border-blue-700' : 'bg-slate-700/50 border-blue-700/20'">
                                <input type="radio" wire:model.live="visibility" value="private" class="sr-only">
                                <span class="text-white font-medium text-sm">🔒 Private</span>
                            </label>
                        </div>
                        @error('visibility') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Category</label>

                        <!-- Toggle between predefined and custom -->
                        <div class="flex gap-2 mb-3">
                            <button type="button"
                                wire:click="$set('useCustomCategory', false)"
                                class="px-3 py-1.5 text-xs rounded-lg transition {{ !$useCustomCategory ? 'bg-purple-500 text-white' : 'bg-slate-700/50 text-gray-300 hover:bg-slate-700' }}">
                                📋 Choose from list
                            </button>
                            <button type="button"
                                wire:click="$set('useCustomCategory', true)"
                                class="px-3 py-1.5 text-xs rounded-lg transition {{ $useCustomCategory ? 'bg-purple-500 text-white' : 'bg-slate-700/50 text-gray-300 hover:bg-slate-700' }}">
                                ✏️ Write custom
                            </button>
                        </div>

                        @if($useCustomCategory)
                            <!-- Custom Category Input -->
                            <input type="text"
                                wire:model="customCategory"
                                placeholder="Enter your custom category..."
                                class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white placeholder-gray-500 border border-blue-700/20 focus:border-blue-700 focus:outline-none transition">
                            @error('customCategory') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                        @else
                            <!-- Predefined Categories Select -->
                            <select wire:model="category"
                                class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white border border-blue-700/20 focus:border-blue-700 focus:outline-none transition">
                                <option value="">Select a category...</option>
                                @foreach($categoryOptions as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('category') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                        @endif
                    </div>
                    <div class="flex gap-3 pt-4">
                        <button type="button" wire:click="closeCreateModal" class="flex-1 px-4 py-2 bg-slate-700 text-white rounded-lg hover:bg-slate-600 transition font-medium">Cancel</button>
                        <button type="submit" class="flex-1 px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:shadow-lg hover:shadow-purple-500/50 transition font-semibold">Create Album</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>

