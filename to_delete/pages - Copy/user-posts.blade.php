<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - {{ $user->name }}'s Posts</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen pb-32 bg-gradient-to-br from-slate-900 via-slate-800 to-black">
        <div class="flex">
            <livewire:layout.sidebar />
            <main class="flex-1 mb-8 overflow-y-auto">
                <div class="max-w-4xl px-4 py-8 mx-auto">
                    <!-- Header -->
                    <div class="mb-8">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="flex items-center justify-center w-20 h-20 text-3xl font-bold text-white rounded-full bg-gradient-to-br from-blue-600 to-black">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-white">{{ $user->name }}</h1>
                                <p class="font-semibold text-blue-400">{{ '@' . ($user->username ?? 'user') }}</p>
                                <p class="mt-2 text-sm text-gray-400">{{ $user->posts()->count() }} posts</p>
                            </div>
                        </div>
                        <a href="{{ route('followers-manager') }}" class="inline-block px-4 py-2 text-gray-200 transition rounded-lg bg-slate-700/50 hover:bg-slate-700">
                            ← Back
                        </a>
                    </div>

                    <!-- Posts -->
                    <div class="space-y-6">
                        @forelse($user->posts()->orderByDesc('created_at')->get() as $post)
                            <div class="p-6 border bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border-blue-700/20">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="flex items-center justify-center w-12 h-12 font-bold text-white rounded-full bg-gradient-to-br from-blue-600 to-black">
                                        {{ substr($post->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-white">{{ $post->user->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $post->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <p class="mb-4 text-gray-200">{{ $post->content }}</p>
                                @if($post->media()->count() > 0)
                                    <div class="grid grid-cols-2 gap-2 mb-4 md:grid-cols-3">
                                        @foreach($post->media as $media)
                                            <img src="{{ $media->url }}" alt="Post media" class="object-cover w-full h-40 rounded-lg">
                                        @endforeach
                                    </div>
                                @endif
                                <div class="flex gap-4 text-sm text-gray-400">
                                    <span>❤️ {{ $post->likes()->count() }} Likes</span>
                                    <span>💬 {{ $post->comments()->count() }} Comments</span>
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center">
                                <p class="text-lg text-gray-400">No posts yet from {{ $user->name }}</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </main>
        </div>
    </div>
    @livewireScripts
</body>
</html>
