<div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-black">
    <div class="px-4 mx-auto max-w-7xl" x-data="{ showCreatePost: false }" @close-modal.window="showCreatePost = false">
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

        <!-- Filter Tabs with Search State -->
        <div x-data="{ searchExpanded: false, showRecent: false }" @click.away="showRecent = false" class="sticky top-0 z-40 bg-gradient-to-br from-slate-900 via-slate-800 to-black">
            <div class="flex items-center justify-between gap-2 px-4 py-4 border-b border-blue-700/20">
                <div class="flex gap-2 overflow-x-auto">
                    <button wire:click="setFilter('all')" class="px-6 py-3 font-semibold {{ $filterType === 'all' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-white' }} transition whitespace-nowrap">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/></svg>
                            All Posts
                        </span>
                    </button>
                    <button wire:click="setFilter('contacts')" class="px-6 py-3 font-semibold {{ $filterType === 'contacts' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-white' }} transition whitespace-nowrap">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                            Contacts
                        </span>
                    </button>
                    <button wire:click="setFilter('following')" class="px-6 py-3 font-semibold {{ $filterType === 'following' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-white' }} transition whitespace-nowrap">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                            Following
                        </span>
                    </button>
                    <button wire:click="setFilter('mine')" class="px-6 py-3 font-semibold {{ $filterType === 'mine' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-white' }} transition whitespace-nowrap">
                        <span class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            My Posts
                        </span>
                    </button>
                </div>

                <!-- Search Icon Button -->
                <button type="button" @click="searchExpanded = !searchExpanded; searchExpanded && $nextTick(() => $refs.feedSearch.focus())" class="flex items-center justify-center w-9 h-9 rounded-full bg-slate-700 text-blue-300 hover:bg-slate-600 hover:scale-110 transition shrink-0" aria-label="Toggle search">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </div>

            <!-- Collapsible Search Bar -->
            <div x-show="searchExpanded" x-transition:enter="transition-all duration-300 ease-out" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition-all duration-200 ease-in" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4" class="overflow-hidden border-b border-blue-700/20" style="display: none;">
                <div class="px-4 pb-4">
                    <div class="flex items-center gap-3 px-4 py-3 rounded-xl border border-slate-600/50 bg-slate-800/70">
                        <button type="button" @click="showRecent = !showRecent; $refs.feedSearch.focus();" class="flex items-center justify-center w-9 h-9 rounded-full bg-slate-700 text-blue-300 hover:bg-slate-600 transition" aria-label="Toggle recent searches">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </button>
                        <input
                            x-ref="feedSearch"
                            @focus="showRecent = true"
                            type="text"
                            wire:model.live.debounce.500ms="search"
                            placeholder="Search posts, users, albums..."
                            class="flex-1 px-3 py-2 text-sm text-white placeholder-gray-400 bg-slate-900/40 rounded-xl border border-transparent focus:border-blue-500 focus:outline-none transition"
                        >
                        <button
                            wire:click="setSearchType('all')"
                            class="hidden sm:inline-flex items-center px-3 py-2 text-xs font-semibold rounded-lg bg-slate-900/60 text-gray-300 hover:text-white hover:bg-slate-800 transition"
                        >All</button>
                        @if($search)
                            <button
                                wire:click="$set('search', '')"
                                class="p-2 rounded-lg text-gray-400 hover:text-white hover:bg-slate-700 transition"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        @endif
                        <button
                            type="button"
                            @click="searchExpanded = false"
                            class="p-2 rounded-lg text-gray-400 hover:text-white hover:bg-slate-700 transition"
                            aria-label="Close search"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <!-- Recent Searches -->
                    <div x-show="showRecent" x-transition:enter="transition-all duration-200 ease-out" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="mt-4" style="display: none;">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center gap-2 text-sm font-semibold text-gray-200">
                                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span>Recent searches</span>
                            </div>
                            @if(count($recentSearches) > 0)
                                <button type="button" wire:click="clearRecentSearches" class="text-xs text-gray-400 hover:text-white">Clear all</button>
                            @endif
                        </div>

                        @if(count($recentSearches) > 0)
                            <div class="space-y-2">
                                @foreach($recentSearches as $index => $recent)
                                    <button
                                        type="button"
                                        wire:click="useRecentSearch({{ $index }})"
                                        @click="showRecent = false"
                                        class="w-full flex items-center justify-between px-3 py-2 rounded-lg bg-slate-800/70 hover:bg-slate-700/70 text-sm text-gray-100 transition"
                                    >
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16z"/></svg>
                                            <span class="truncate">{{ $recent }}</span>
                                        </div>
                                        <span class="text-[11px] text-gray-500">Tap to search</span>
                                    </button>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-gray-400">No recent searches yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Posts Display with Infinite Scroll -->
        <div x-data="{ observer: null, loading: false }" x-init="
            observer = new IntersectionObserver(
                entries => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting && !loading && $wire.hasMore) {
                            loading = true;
                            $wire.loadMore().then(() => { loading = false; });
                        }
                    });
                },
                { threshold: 0.1 }
            );
            $watch('$wire.offset', () => {
                setTimeout(() => {
                    const sentinel = document.getElementById('infinite-scroll-sentinel');
                    if (sentinel) observer.observe(sentinel);
                }, 100);
            });
        " class="space-y-6 mt-8 px-4">
            @forelse($posts as $post)
                @livewire('post.post-card', ['post' => $post], key($post->id))
            @empty
                <div class="text-center py-20">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-700 to-black rounded-full mx-auto mb-4 opacity-20"></div>
                    <p class="text-gray-400 text-lg">No posts yet. Be the first to share!</p>
                </div>
            @endforelse

            <!-- Infinite Scroll Sentinel -->
            <div id="infinite-scroll-sentinel" class="py-8 text-center">
                @if($hasMore)
                    <div class="flex items-center justify-center gap-2">
                        <div class="w-2 h-2 bg-blue-400 rounded-full animate-bounce"></div>
                        <div class="w-2 h-2 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0.1s;"></div>
                        <div class="w-2 h-2 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@livewire('post.post-create-modal')


