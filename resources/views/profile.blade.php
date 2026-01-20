<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Profile</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @livewireStyles
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gradient-to-br from-slate-950 via-blue-950 via-30% via-blue-900 via-60% to-slate-900 relative">
        <!-- Animated background elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-700 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse"></div>
            <div class="absolute top-20 right-1/4 w-96 h-96 bg-blue-700 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse" style="animation-delay: 2s"></div>
            <div class="absolute -bottom-8 left-1/2 w-96 h-96 bg-blue-700 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse" style="animation-delay: 4s"></div>
        </div>
        <div class="flex">
            <livewire:layout.sidebar />
            <main class="flex-1 overflow-y-auto">
                <div class="max-w-6xl mx-auto px-4 py-8">
                    <!-- Profile Header -->
                    <div class="mb-8">
                        <!-- Cover Photo -->
                        <div class="relative h-48 md:h-64 rounded-2xl overflow-hidden mb-6 bg-gradient-to-r from-blue-700 to-black">
                            <div class="absolute inset-0 opacity-20" style="background: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.1"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E'); background-repeat: repeat;"></div>
                        </div>

                        <!-- Profile Info Card -->
                        <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-blue-700/20 p-6 md:p-8 -mt-12 relative z-10">
                            <div class="flex flex-col md:flex-row gap-6 items-start md:items-center">
                                <!-- Avatar -->
                                <div class="flex-shrink-0">
                                    <div class="w-32 h-32 rounded-2xl ring-4 ring-blue-700 overflow-hidden bg-gradient-to-br from-blue-700 to-black flex items-center justify-center">
                                        <span class="text-5xl font-bold text-white">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                    </div>
                                </div>

                                <!-- Info -->
                                <div class="flex-1">
                                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">{{ auth()->user()->name }}</h1>
                                    <p class="text-lg text-blue-600 mb-4">@{{ auth()->user()->username }}</p>
                                    <p class="text-gray-300 mb-6">{{ auth()->user()->bio ?? 'No bio yet' }}</p>

                                    <!-- Stats -->
                                    <div class="grid grid-cols-4 gap-4">
                                        <div class="text-center">
                                            <div class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-black bg-clip-text text-transparent">{{ auth()->user()->posts()->count() }}</div>
                                            <div class="text-sm text-gray-400">Posts</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-black bg-clip-text text-transparent">{{ auth()->user()->albums()->count() }}</div>
                                            <div class="text-sm text-gray-400">Albums</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-black bg-clip-text text-transparent">{{ auth()->user()->following()->count() }}</div>
                                            <div class="text-sm text-gray-400">Following</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-black bg-clip-text text-transparent">{{ auth()->user()->followers()->count() }}</div>
                                            <div class="text-sm text-gray-400">Followers</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content Grid -->
                    <div class="grid md:grid-cols-3 gap-8">
                        <!-- Main Content -->
                        <div class="md:col-span-2 space-y-6">
                            <!-- Recent Posts -->
                            <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border border-blue-700/20 p-6">
                                <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
                                    <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                                    Recent Posts
                                </h2>
                                <div class="space-y-4">
                                    @forelse(auth()->user()->posts()->orderByDesc('created_at')->limit(5)->get() as $post)
                                        @livewire('post.post-card', ['post' => $post], key($post->id))
                                    @empty
                                        <div class="text-center py-12">
                                            <div class="text-6xl mb-3">📝</div>
                                            <p class="text-gray-400">No posts yet. Create your first post!</p>
                                            <a href="{{ route('dashboard') }}" class="mt-4 inline-block px-6 py-2 bg-gradient-to-r from-blue-700 to-black text-white rounded-lg font-semibold hover:shadow-lg transition">
                                                Create Post
                                            </a>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <!-- Sidebar -->
                        <div class="space-y-6">
                            <!-- Albums Section -->
                            <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border border-blue-700/20 p-6">
                                <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                                    Your Albums
                                </h3>
                                <div class="space-y-2 max-h-64 overflow-y-auto">
                                    @forelse(auth()->user()->albums()->orderByDesc('created_at')->limit(8)->get() as $album)
                                        <a href="{{ route('albums', ['album' => $album->id]) }}" class="block p-3 rounded-lg bg-slate-700/50 hover:bg-slate-700 transition group">
                                            <p class="font-semibold text-white text-sm truncate group-hover:text-blue-600">🖼️ {{ $album->title }}</p>
                                            <p class="text-xs text-gray-400">{{ $album->posts()->count() }} posts</p>
                                        </a>
                                    @empty
                                        <p class="text-gray-400 text-sm text-center py-4">No albums yet</p>
                                    @endforelse
                                </div>
                                <a href="{{ route('albums') }}" class="block mt-4 w-full py-2 text-center bg-gradient-to-r from-blue-700 to-black text-white rounded-lg font-semibold text-sm hover:shadow-lg transition">
                                    All Albums
                                </a>
                            </div>

                            <!-- Followers -->
                            <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border border-blue-700/20 p-6">
                                <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                    Followers
                                </h3>
                                <p class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-black bg-clip-text text-transparent">{{ auth()->user()->followers()->count() }}</p>
                                <p class="text-gray-400 text-sm mt-2 mb-4">People following you</p>
                                <button class="w-full py-2 px-4 bg-slate-700 hover:bg-slate-600 text-white rounded-lg font-semibold text-sm transition">
                                    View List
                                </button>
                            </div>

                            <!-- Following -->
                            <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border border-blue-700/20 p-6">
                                <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.89 1.97 1.74 1.97 2.95V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                                    Following
                                </h3>
                                <p class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-black bg-clip-text text-transparent">{{ auth()->user()->following()->count() }}</p>
                                <p class="text-gray-400 text-sm mt-2 mb-4">People you follow</p>
                                <button class="w-full py-2 px-4 bg-slate-700 hover:bg-slate-600 text-white rounded-lg font-semibold text-sm transition">
                                    View List
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    @livewireScripts
</body>
</html>

