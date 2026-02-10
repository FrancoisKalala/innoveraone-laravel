
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900">
    <!-- Beautiful Top Bar -->
    <nav class="sticky top-0 z-40 bg-gradient-to-r from-blue-900/95 to-black/95 border-b border-blue-700/30 shadow-lg shadow-blue-900/10 backdrop-blur">
        <div class="max-w-3xl mx-auto px-4 py-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-blue-700 to-black rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" fill="#2563eb"/>
                        <text x="12" y="17" text-anchor="middle" font-size="12" fill="white" font-family="Arial" font-weight="bold">IO</text>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-extrabold bg-gradient-to-r from-blue-400 to-blue-900 bg-clip-text text-transparent tracking-tight drop-shadow">InnoveraOne</h1>
                    <p class="text-xs text-blue-200 font-semibold">Guest Mode</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="px-5 py-2 text-blue-200 hover:text-white transition text-base font-semibold rounded-lg hover:bg-blue-700/30">🚀 Login</a>
                <a href="{{ route('register') }}" class="px-5 py-2 bg-gradient-to-r from-blue-700 to-black text-white rounded-lg hover:shadow-lg hover:shadow-blue-700/50 transition text-base font-bold">✨ Sign Up</a>
            </div>
        </div>
    </nav>

    <div class="max-w-3xl mx-auto px-4 py-8">
        <h2 class="text-3xl font-extrabold text-white mb-8 text-center tracking-tight drop-shadow">Public Albums</h2>

        <!-- Infinite Scroll Feed -->
        <div x-data="{
            observer: null,
            loading: false
        }"
        x-init="
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
        " class="mb-8">
            @forelse($posts as $post)
                <div class="mb-8 animate-fade-in">
                    @include('livewire.post.post-card-guest', ['post' => $post])
                </div>
            @empty
                <div class="text-center py-20">
                    <div class="w-16 h-16 bg-gradient-to-br from-blue-700 to-black rounded-full mx-auto mb-4 opacity-20"></div>
                    <p class="text-gray-400 text-lg">No public posts yet. Be the first to share!</p>
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

        <!-- Modal for Guest Interactions -->
        <div x-data="{ showModal: false, action: '' }" @show-guest-modal.window="showModal = true; action = $event.detail.action;" style="z-index: 9999;">
            <div x-show="showModal" class="fixed inset-0 flex items-center justify-center bg-black/60 backdrop-blur-sm" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" style="display: none;">
                <div class="bg-gradient-to-br from-blue-800 to-black rounded-2xl border border-blue-700/30 max-w-md w-full p-8 relative z-10 shadow-2xl">
                    <button @click="showModal = false" class="absolute top-3 right-3 text-gray-400 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    <div class="flex flex-col items-center gap-4">
                        <div class="text-4xl">🔒</div>
                        <h3 class="text-xl font-bold text-white">Create an Account or Login</h3>
                        <p class="text-blue-200 text-center">To <span x-text="action"></span>, please create a free account or log in to join the community and interact with posts!</p>
                        <div class="flex gap-4 mt-4">
                            <a href="{{ route('register') }}" class="px-5 py-2 bg-gradient-to-r from-blue-700 to-black text-white rounded-lg font-bold hover:shadow-lg hover:shadow-blue-700/50 transition">✨ Sign Up</a>
                            <a href="{{ route('login') }}" class="px-5 py-2 text-blue-200 hover:text-white transition font-semibold rounded-lg hover:bg-blue-700/30">🚀 Login</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

