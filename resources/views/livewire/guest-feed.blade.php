<div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900">
    <!-- Navigation -->
    <nav class="sticky top-0 z-40 bg-gradient-to-r from-slate-900/95 to-black/95 border-b border-blue-700/20 backdrop-blur">
        <div class="max-w-3xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-blue-700 to-black rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm0-13c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="text-lg font-bold bg-gradient-to-r from-blue-600 to-black bg-clip-text text-transparent">InnoveraOne</h1>
                    <p class="text-xs text-gray-400">Guest Mode</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}" class="px-4 py-2 text-gray-300 hover:text-white transition text-sm">
                    🚀 Login
                </a>
                <a href="{{ route('register') }}" class="px-4 py-2 bg-gradient-to-r from-blue-700 to-black text-white rounded-lg hover:shadow-lg hover:shadow-blue-700/50 transition text-sm font-semibold">
                    ✨ Sign Up
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-3xl mx-auto px-4 py-8">
        <!-- Filter Tabs -->
        <div class="flex gap-3 mb-8 sticky top-16 z-30 bg-gradient-to-r from-slate-900/80 to-black/80 backdrop-blur py-4 -mx-4 px-4 border-b border-blue-700/20">
            <button
                wire:click="setFilter('all')"
                :class="'px-4 py-2 rounded-full font-semibold transition text-sm ' + (@if($filter === 'all') 'bg-gradient-to-r from-blue-700 to-black text-white shadow-lg shadow-blue-700/50' else 'bg-slate-700/50 text-gray-300 hover:bg-slate-700' @endif)"
            >
                📱 All Posts
            </button>
            <button
                wire:click="setFilter('trending')"
                :class="'px-4 py-2 rounded-full font-semibold transition text-sm ' + (@if($filter === 'trending') 'bg-gradient-to-r from-blue-700 to-black text-white shadow-lg shadow-blue-700/50' else 'bg-slate-700/50 text-gray-300 hover:bg-slate-700' @endif)"
            >
                🔥 Trending
            </button>
            <button
                wire:click="setFilter('recent')"
                :class="'px-4 py-2 rounded-full font-semibold transition text-sm ' + (@if($filter === 'recent') 'bg-gradient-to-r from-blue-700 to-black text-white shadow-lg shadow-blue-700/50' else 'bg-slate-700/50 text-gray-300 hover:bg-slate-700' @endif)"
            >
                ⏰ Recent
            </button>
        </div>

        <!-- Guest Info Banner -->
        <div class="mb-8 bg-gradient-to-r from-blue-700/20 to-black/20 border border-blue-700/30 rounded-2xl p-6 backdrop-blur">
            <div class="flex items-start gap-4">
                <div class="text-3xl">👀</div>
                <div class="flex-1">
                    <h3 class="font-bold text-lg text-white mb-2">Browsing as Guest</h3>
                    <p class="text-gray-300 text-sm mb-4">You're viewing public posts from our community. Sign up to like, comment, send messages, and connect with creators!</p>
                    <a href="{{ route('register') }}" class="inline-block px-4 py-2 bg-gradient-to-r from-blue-700 to-black text-white font-semibold rounded-lg hover:shadow-lg hover:shadow-blue-700/50 transition">
                        ✨ Create Free Account
                    </a>
                </div>
            </div>
        </div>

        <!-- Posts List -->
        @if ($posts->count() > 0)
            <div class="space-y-6 mb-8">
                @foreach ($posts as $post)
                    <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border border-blue-700/20 overflow-hidden hover:border-blue-700/40 transition">
                        <!-- Post Header -->
                        <div class="p-6 flex items-start justify-between border-b border-blue-700/10">
                            <div class="flex items-start gap-4 flex-1">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-700 to-black flex items-center justify-center flex-shrink-0">
                                    <span class="text-lg">{{ substr($post->user->name, 0, 1) }}</span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <h4 class="font-bold text-white">{{ $post->user->name }}</h4>
                                        <span class="text-gray-500">{{ '@' . ($post->user->username ?? strtolower(str_replace(' ', '', $post->user->name))) }}</span>
                                    </div>
                                    <p class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @if ($post->chapter)
                                <span class="px-3 py-1 bg-gradient-to-r from-blue-500/20 to-cyan-500/20 text-blue-300 text-xs rounded-full font-semibold whitespace-nowrap">
                                    🖼️ {{ $post->chapter->name }}
                                </span>
                            @endif
                        </div>

                        <!-- Post Content -->
                        <div class="p-6 space-y-4">
                            <p class="text-gray-200 text-base leading-relaxed">{{ $post->content }}</p>

                            @if ($post->media->count() > 0)
                                <div class="grid grid-cols-1 {{ $post->media->count() >= 2 ? 'md:grid-cols-2' : '' }} gap-4">
                                    @foreach ($post->media->take(4) as $media)
                                        @if (in_array($media->type, ['image', 'photo']))
                                            <div class="relative overflow-hidden rounded-xl aspect-square group">
                                                <img
                                                    src="{{ Storage::url($media->path) }}"
                                                    alt="Post media"
                                                    class="w-full h-full object-cover group-hover:scale-105 transition"
                                                >
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Post Stats & Actions -->
                        <div class="px-6 py-4 border-t border-blue-700/10 space-y-4">
                            <!-- Stats -->
                            <div class="flex justify-between text-sm text-gray-400">
                                <span>❤️ {{ $post->likes->count() }} {{ $post->likes->count() === 1 ? 'Like' : 'Likes' }}</span>
                                <span>💬 {{ $post->comments->count() }} {{ $post->comments->count() === 1 ? 'Comment' : 'Comments' }}</span>
                                @if ($post->expiration_hours)
                                    <span class="text-amber-400">⏰ {{ $post->expiration_hours }}h left</span>
                                @endif
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex gap-3">
                                <button
                                    onclick="alert('Sign up to like posts!')"
                                    class="flex-1 py-2 px-4 bg-gradient-to-r from-red-600/20 to-red-700/20 text-red-400 font-semibold rounded-lg hover:from-red-600/30 hover:to-red-700/30 transition"
                                >
                                    ❤️ Like
                                </button>
                                <button
                                    onclick="alert('Sign up to comment!')"
                                    class="flex-1 py-2 px-4 bg-gradient-to-r from-blue-500/20 to-cyan-500/20 text-blue-300 font-semibold rounded-lg hover:from-blue-500/30 hover:to-cyan-500/30 transition"
                                >
                                    💬 Comment
                                </button>
                                <button
                                    onclick="alert('Sign up to share!')"
                                    class="flex-1 py-2 px-4 bg-gradient-to-r from-blue-700/20 to-black/20 text-blue-400 font-semibold rounded-lg hover:from-blue-700/30 hover:to-black/30 transition"
                                >
                                    🔗 Share
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="flex justify-center gap-2 mb-8">
                {{ $posts->links() }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="text-6xl mb-4">📭</div>
                <h3 class="text-2xl font-bold text-white mb-2">No public posts yet</h3>
                <p class="text-gray-400 mb-8">Check back soon for amazing content from our community!</p>
                <a href="{{ route('register') }}" class="inline-block px-6 py-3 bg-gradient-to-r from-blue-700 to-black text-white font-bold rounded-lg hover:shadow-lg hover:shadow-blue-700/50 transition">
                    ✨ Be the First to Share
                </a>
            </div>
        @endif
    </div>
</div>

