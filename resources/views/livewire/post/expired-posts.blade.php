<div class="max-w-6xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-white mb-2 flex items-center gap-3">
            <svg class="w-8 h-8 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 4h-3.5l-1-1h-5l-1 1H5v2h14V4zM6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12z"/>
            </svg>
            Expired Posts
        </h1>
        <p class="text-gray-400">Manage your deleted posts</p>
    </div>

    @if(session()->has('success'))
        <div class="mb-6 p-4 bg-green-500/20 border border-green-500/50 rounded-lg text-green-400">
            {{ session('success') }}
        </div>
    @endif

    <!-- Expired Posts List -->
    @if($expiredPosts->count() > 0)
        <div class="space-y-6">
            @foreach($expiredPosts as $post)
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-red-500/20 overflow-hidden">
                    <!-- Post Header -->
                    <div class="p-6 bg-gradient-to-r from-red-500/10 to-black/10 border-b border-red-500/20">
                        <div class="flex items-start justify-between">
                            <div class="flex items-center gap-3">
                                <img src="{{ $post->user->profile_photo_path ? asset('storage/' . $post->user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) . '&background=ef4444&color=fff' }}" 
                                     alt="{{ $post->user->name }}" 
                                     class="w-12 h-12 rounded-full object-cover ring-2 ring-red-500/50">
                                <div>
                                    <h3 class="font-bold text-white text-lg">{{ $post->user->name }}</h3>
                                    <p class="text-sm text-gray-400">Deleted {{ $post->updated_at->diffForHumans() }}</p>
                                    @if($post->album)
                                        <span class="inline-block mt-1 px-2 py-0.5 bg-blue-500/20 text-blue-300 text-xs rounded">
                                            📁 {{ $post->album->title }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <span class="px-3 py-1 bg-red-500/20 text-red-400 text-xs font-semibold rounded-full">
                                Expired
                            </span>
                        </div>
                    </div>

                    <!-- Post Content -->
                    <div class="p-6">
                        <p class="text-gray-300 mb-4">{{ Str::limit($post->content, 300) }}</p>
                        
                        @if($post->media->count() > 0)
                            <div class="text-sm text-gray-400">
                                📎 Contains {{ $post->media->count() }} file(s)
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="px-6 py-4 bg-slate-900/50 border-t border-red-500/20 flex gap-3">
                        <button wire:click="restorePost({{ $post->id }})"
                                class="flex-1 py-2 px-4 rounded-lg font-semibold transition bg-gradient-to-r from-green-600 to-emerald-600 text-white hover:shadow-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M13 3c-4.97 0-9 4.03-9 9H1l3.89 3.89.07.14L9 12H6c0-3.87 3.13-7 7-7s7 3.13 7 7-3.13 7-7 7c-1.93 0-3.68-.79-4.94-2.06l-1.42 1.42C8.27 19.99 10.51 21 13 21c4.97 0 9-4.03 9-9s-4.03-9-9-9zm-1 5v5l4.28 2.54.72-1.21-3.5-2.08V8H12z"/>
                            </svg>
                            Restore
                        </button>
                        <button wire:click="openDeleteModal({{ $post->id }})"
                                class="flex-1 py-2 px-4 rounded-lg font-semibold transition bg-red-500/20 text-red-400 border border-red-500/50 hover:bg-red-500/30 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                            </svg>
                            Delete Forever
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $expiredPosts->links() }}
        </div>
    @else
        <div class="text-center py-16 bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border border-blue-700/20">
            <div class="text-6xl mb-4">🗑️</div>
            <h3 class="text-2xl font-bold text-white mb-2">No Expired Posts</h3>
            <p class="text-gray-400">Your deleted posts will appear here</p>
        </div>
    @endif

    <!-- Delete Forever Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-red-700/30 max-w-md w-full p-6">
                <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-red-400" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                    Delete Expired Post
                </h2>
                <p class="text-gray-300 mb-6">Are you sure you want to permanently delete this expired post? This action cannot be undone.</p>
                <div class="flex gap-3">
                    <button wire:click="permanentlyDelete" class="flex-1 px-4 py-2 font-semibold text-white transition rounded-lg bg-gradient-to-r from-red-600 to-black hover:shadow-lg hover:shadow-red-500/50">Delete Forever</button>
                    <button wire:click="closeDeleteModal" class="flex-1 px-4 py-2 font-semibold text-white transition rounded-lg bg-slate-700 hover:bg-slate-800">Cancel</button>
                </div>
            </div>
        </div>
    @endif
</div>
