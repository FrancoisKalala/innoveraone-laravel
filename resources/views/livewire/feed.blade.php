<div class="min-h-screen">
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
        <div class="mb-8">@livewire('post.create-post')</div>
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

