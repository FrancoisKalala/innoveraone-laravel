<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - {{ $user->name }}'s Albums</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-black pb-32">
        <div class="flex">
            <livewire:layout.sidebar />
            <main class="flex-1 overflow-y-auto mb-8">
                <div class="max-w-6xl mx-auto px-4 py-8">
                    <!-- Header -->
                    <div class="mb-8">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-600 to-black flex items-center justify-center text-white text-3xl font-bold">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div>
                                <h1 class="text-3xl font-bold text-white">{{ $user->name }}'s Albums</h1>
                                <p class="text-blue-400 font-semibold">{{ '@' . ($user->username ?? 'user') }}</p>
                                <p class="text-gray-400 text-sm mt-2">{{ $user->albums()->count() }} albums</p>
                            </div>
                        </div>
                        <a href="{{ route('followers-manager') }}" class="inline-block px-4 py-2 bg-slate-700/50 text-gray-200 rounded-lg hover:bg-slate-700 transition">
                            ← Back
                        </a>
                    </div>

                    <!-- Albums Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($user->albums as $album)
                            <div class="bg-gradient-to-br from-slate-800/60 to-slate-900/60 rounded-2xl border border-blue-700/20 overflow-hidden hover:border-blue-700/40 transition cursor-pointer">
                                <div class="bg-gradient-to-br from-blue-600 to-black h-32 flex items-center justify-center">
                                    <span class="text-4xl">🖼️</span>
                                </div>
                                <div class="p-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <h3 class="font-bold text-white truncate">{{ $album->title }}</h3>
                                        @if($album->visibility === 'public')
                                            <span class="px-2 py-0.5 text-xs rounded-lg bg-green-500/10 border border-green-500/30 text-green-200">Public</span>
                                        @else
                                            <span class="px-2 py-0.5 text-xs rounded-lg bg-yellow-500/10 border border-yellow-500/30 text-yellow-200">Private</span>
                                        @endif
                                    </div>
                                    <p class="text-gray-400 text-sm mb-3">{{ Str::limit($album->description ?? 'No description', 80) }}</p>
                                    <div class="flex items-center gap-3 text-sm text-gray-300">
                                        <span class="px-2 py-1 rounded-lg bg-blue-500/10 border border-blue-500/20">📝 {{ $album->posts()->count() }} Posts</span>
                                        <span class="px-2 py-1 rounded-lg bg-blue-500/10 border border-blue-500/20">👁️ Views</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-12">
                                <p class="text-gray-400 text-lg">No albums from {{ $user->name }}</p>
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
