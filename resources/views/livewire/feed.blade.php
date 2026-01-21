<div class="min-h-screen" x-data="{ showCreatePost: false }" @close-modal.window="showCreatePost = false">
    <!-- Fixed Floating Action Button -->
    <button @click="showCreatePost = true" class="fixed bottom-8 right-8 z-50 w-16 h-16 bg-gradient-to-r from-blue-700 to-black rounded-full shadow-2xl shadow-blue-700/50 hover:shadow-blue-700/70 hover:scale-110 transition-all duration-300 flex items-center justify-center group">
        <svg class="w-8 h-8 text-white group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
    </button>

    <!-- Create Post Modal -->
    <div x-show="showCreatePost" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4" 
         style="display: none;">
        <div @click="showCreatePost = false" class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>
        <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-blue-700/30 max-w-4xl w-full p-6 relative z-10 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/></svg>
                    Create New Post
                </h2>
                <button @click="showCreatePost = false" class="text-gray-400 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div>
                @livewire('post.create-post', ['albumId' => $albumId], key('modal-create-post'))
            </div>
        </div>
    </div>

    <!-- Filter Tabs -->
    <div class="sticky top-0 z-40 bg-slate-900/95 backdrop-blur border-b border-blue-700/20">
        <div class="max-w-4xl mx-auto px-4 py-4">
            @if ($filterType === 'album' && $albumId)
                <!-- Album Filter Header -->
                <div class="flex items-center gap-3 mb-4">
                    <a href="{{ route('dashboard') }}" class="p-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/5">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </a>
                    <div class="flex items-center gap-2">
                        <span class="text-xl">🖼️</span>
                        <span class="text-white font-semibold">Album Posts</span>
                    </div>
                </div>
            @else
                <!-- Standard Filter Tabs -->
                <div class="flex gap-3">
                    <button wire:click="setFilter('all')" class="px-6 py-2.5 rounded-xl transition {{ $filterType === 'all' ? 'bg-gradient-to-r from-blue-700 to-black text-white shadow-lg shadow-blue-700/30' : 'text-gray-300 hover:bg-slate-800/50 hover:text-white' }}">
                        <span class="font-medium">All Posts</span>
                    </button>
                    <button wire:click="setFilter('contacts')" class="px-6 py-2.5 rounded-xl transition {{ $filterType === 'contacts' ? 'bg-gradient-to-r from-blue-700 to-black text-white shadow-lg shadow-blue-700/30' : 'text-gray-300 hover:bg-slate-800/50 hover:text-white' }}">
                        <span class="font-medium">Contacts</span>
                    </button>
                    <button wire:click="setFilter('following')" class="px-6 py-2.5 rounded-xl transition {{ $filterType === 'following' ? 'bg-gradient-to-r from-blue-700 to-black text-white shadow-lg shadow-blue-700/30' : 'text-gray-300 hover:bg-slate-800/50 hover:text-white' }}">
                        <span class="font-medium">Following</span>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <div class="max-w-4xl mx-auto py-8 px-4">
        <div class="space-y-6">
            @forelse($posts as $post)
                @livewire('post.post-card', ['post' => $post], key($post->id))
            @empty
                <div class="text-center py-20">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-700 to-black rounded-full mx-auto mb-4 opacity-20"></div>
                    <p class="text-gray-400 text-lg">No posts yet. Be the first to share!</p>
                </div>
            @endforelse
        </div>
        <div class="mt-12">{{ $posts->links('pagination::tailwind') }}</div>
    </div>
</div>

