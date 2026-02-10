<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Profile</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-black pb-32">
        <livewire:layout.sidebar />
        <div class="flex">
            <main class="flex-1 overflow-y-auto mb-8">
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
                                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-4">
                                        <a href="{{ route('dashboard') }}" class="text-center rounded-lg border border-blue-700/20 bg-slate-900/40 hover:border-blue-500/40 transition p-3 block">
                                            <div class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-black bg-clip-text text-transparent">{{ auth()->user()->posts()->count() }}</div>
                                            <div class="text-sm text-gray-300">Posts</div>
                                        </a>
                                        <a href="{{ route('albums') }}" class="text-center rounded-lg border border-blue-700/20 bg-slate-900/40 hover:border-blue-500/40 transition p-3 block">
                                            <div class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-black bg-clip-text text-transparent">{{ auth()->user()->albums()->count() }}</div>
                                            <div class="text-sm text-gray-300">Albums</div>
                                        </a>
                                        <a href="{{ route('followers-manager') }}" class="text-center rounded-lg border border-blue-700/20 bg-slate-900/40 hover:border-blue-500/40 transition p-3 block">
                                            <div class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-black bg-clip-text text-transparent">{{ auth()->user()->following()->count() }}</div>
                                            <div class="text-sm text-gray-300">Following</div>
                                        </a>
                                        <a href="{{ route('followers-manager') }}" class="text-center rounded-lg border border-blue-700/20 bg-slate-900/40 hover:border-blue-500/40 transition p-3 block">
                                            <div class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-black bg-clip-text text-transparent">{{ auth()->user()->followers()->count() }}</div>
                                            <div class="text-sm text-gray-300">Followers</div>
                                        </a>
                                        <a href="{{ route('expired-posts') }}" class="text-center rounded-lg border border-red-700/20 bg-slate-900/40 hover:border-red-500/40 transition p-3 block">
                                            <div class="text-xl font-semibold text-red-400">Expired</div>
                                            <div class="text-sm text-gray-300">Posts</div>
                                        </a>
                                    </div>

                                    <!-- Logout Button -->
                                    <div class="mt-6">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex items-center justify-center gap-2 px-6 py-3 bg-red-500/10 text-red-400 hover:bg-red-500/20 rounded-lg transition font-semibold border border-red-700/20">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                                <span>Logout</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="space-y-6">
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
                </div>
            </main>
        </div>
    </div>
    @livewireScripts
</body>
</html>

