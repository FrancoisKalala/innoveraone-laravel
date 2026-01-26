<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2 flex items-center gap-3">
            <svg class="w-7 h-7 text-yellow-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Scheduled Posts
        </h1>
        <p class="text-gray-400">Posts scheduled to be published in the future.</p>
    </div>
    @if($scheduledPosts->count() > 0)
        <div class="space-y-6">
            @foreach($scheduledPosts as $post)
                <div class="bg-gradient-to-br from-yellow-900/40 to-slate-900/50 rounded-2xl border border-yellow-500/20 overflow-hidden">
                    <div class="p-6 border-b border-yellow-500/20 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <img src="{{ $post->user->profile_photo_path ? asset('storage/' . $post->user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) . '&background=facc15&color=000' }}" alt="{{ $post->user->name }}" class="w-12 h-12 rounded-full object-cover ring-2 ring-yellow-500/50">
                            <div>
                                <h3 class="font-bold text-white text-lg">{{ $post->user->name }}</h3>
                                <p class="text-sm text-gray-400">Scheduled for {{ $post->publish_at->format('M d, Y H:i') }}</p>
                                @if($post->album)
                                    <span class="inline-block mt-1 px-2 py-0.5 bg-blue-500/20 text-blue-300 text-xs rounded">📁 {{ $post->album->title }}</span>
                                @endif
                            </div>
                        </div>
                        <span class="px-3 py-1 bg-yellow-500/20 text-yellow-400 text-xs font-semibold rounded-full">Scheduled</span>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-300 mb-4">{{ Str::limit($post->content, 300) }}</p>
                        @if($post->media->count() > 0)
                            <div class="text-sm text-gray-400">📎 Contains {{ $post->media->count() }} file(s)</div>
                        @endif
                        <div class="flex gap-3 mt-4">
                            <button wire:click="openEditModal({{ $post->id }})" class="flex-1 py-2 px-4 rounded-lg font-semibold transition bg-gradient-to-r from-blue-600 to-blue-800 text-white hover:shadow-lg flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/>
                                </svg>
                                Edit
                            </button>
                            <button wire:click="openDeleteModal({{ $post->id }})" class="flex-1 py-2 px-4 rounded-lg font-semibold transition bg-red-500/20 text-red-400 border border-red-500/50 hover:bg-red-500/30 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/>
                                </svg>
                                Delete Forever
                            </button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="mt-8">
            {{ $scheduledPosts->links() }}
        </div>
    @else
        <div class="text-center py-16 bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border border-yellow-500/20">
            <div class="text-6xl mb-4">⏰</div>
            <h3 class="text-2xl font-bold text-white mb-2">No Scheduled Posts</h3>
            <p class="text-gray-400">Your scheduled posts will appear here.</p>
        </div>
    @endif

    <!-- Delete Forever Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-red-700/30 max-w-md w-full p-6">
                <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-red-400" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
                    Delete Scheduled Post
                </h2>
                <p class="text-gray-300 mb-6">Are you sure you want to permanently delete this scheduled post? This action cannot be undone.</p>
                <div class="flex gap-3">
                    <button wire:click="permanentlyDelete" class="flex-1 px-4 py-2 font-semibold text-white transition rounded-lg bg-gradient-to-r from-red-600 to-black hover:shadow-lg hover:shadow-red-500/50">Delete Forever</button>
                    <button wire:click="closeDeleteModal" class="flex-1 px-4 py-2 font-semibold text-white transition rounded-lg bg-slate-700 hover:bg-slate-800">Cancel</button>
                </div>
            </div>
        </div>
    @endif
</div>
