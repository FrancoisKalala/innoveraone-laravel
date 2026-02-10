<div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900">
    <div class="max-w-3xl px-4 py-8 mx-auto">
        <!-- User Title Block -->
        <div class="relative mb-8">
            <div class="absolute top-0 left-0">
                <button onclick="window.history.back()" class="flex items-center gap-2 px-4 py-2 font-semibold text-white transition shadow-lg bg-gradient-to-r from-blue-700 to-black rounded-xl hover:scale-105 hover:bg-blue-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back
                </button>
            </div>
            <div class="flex flex-col items-center px-6 py-8 overflow-hidden border shadow-xl bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border-blue-500/20">
                <div class="flex items-center gap-4 mb-4">
                    <a href="{{ route('user.profile', $user->id) }}" class="flex items-center justify-center rounded-full shadow-lg w-14 h-14 bg-gradient-to-br from-blue-700 to-black hover:scale-105 transition">
                        <span class="text-3xl font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                    </a>
                    <h2 class="text-3xl font-extrabold tracking-tight text-white md:text-4xl drop-shadow">{{ $user->name }}</h2>
                </div>
                <p class="mb-2 text-lg text-blue-200">{{  '@' . $user->username }}</p>
                <span class="inline-block px-3 py-1 text-xs font-semibold text-blue-400 rounded-full bg-blue-500/20">
                    {{ $user->posts()->count() }} Posts
                </span>
            </div>
        </div>
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
                    @livewire('post.post-card', ['post' => $post, 'user' => $post->user, 'files' => $post->files ?? collect([])], key($post->id))
                </div>
            @empty
                <div class="py-20 text-center">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-blue-700 to-black opacity-20"></div>
                    <p class="text-lg text-gray-400">No posts yet from {{ $user->name }}</p>
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
