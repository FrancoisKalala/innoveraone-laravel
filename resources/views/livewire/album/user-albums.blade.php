<div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900">
    <div class="max-w-4xl px-4 py-8 mx-auto">
        <!-- Header Block -->
        <div class="relative mb-8">
            <div class="absolute top-0 left-0">
                <button onclick="window.history.back()" class="flex items-center gap-2 px-4 py-2 font-semibold text-white transition shadow-lg bg-gradient-to-r from-blue-700 to-black rounded-xl hover:scale-105 hover:bg-blue-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back
                </button>
            </div>
            <div class="flex flex-col items-center px-6 py-8 overflow-hidden border shadow-xl bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border-blue-500/20">
                <div class="flex items-center gap-4 mb-4">
                    <div class="flex items-center justify-center rounded-full shadow-lg w-14 h-14 bg-gradient-to-br from-blue-700 to-black">
                        <a href="{{ route('user.profile', $user->id) }}" class="flex items-center justify-center transition rounded-full shadow-lg w-14 h-14 bg-gradient-to-br from-blue-700 to-black hover:scale-105">
                            <span class="text-3xl font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                        </a>
                    </div>
                    <h2 class="text-3xl font-extrabold tracking-tight text-white md:text-4xl drop-shadow">{{ $user->name }}'s Albums</h2>
                </div>
                <p class="mb-2 text-lg text-blue-200">{{'@'. ($user->username ?? 'user') }}</p>
                <span class="inline-block px-3 py-1 text-xs font-semibold text-blue-400 rounded-full bg-blue-500/20">
                    {{ $albums->count() }} Albums
                </span>
            </div>
        </div>
        <!-- Albums Grid -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($albums as $album)
                <a href="{{ route('album.posts', $album->id) }}" class="block overflow-hidden transition border cursor-pointer bg-gradient-to-br from-slate-800/60 to-slate-900/60 rounded-2xl border-blue-700/20 hover:border-blue-700/40 group focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <div class="flex items-center justify-center h-32 transition bg-gradient-to-br from-blue-600 to-black group-hover:bg-blue-700/80">
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
                                @php
                                    $unviewed = $unviewedCounts[$album->id] ?? 0;
                                    $viewed = $viewedCounts[$album->id] ?? 0;
                                @endphp
                            @if($unviewed > 0)
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-lg bg-pink-500/20 border border-pink-500/40 text-pink-400 animate-pulse">
                                    {{ $unviewed }} new
                                </span>
                            @endif
                            @if($viewed > 0)
                                <span class="px-2 py-0.5 text-xs font-semibold rounded-lg bg-blue-500/20 border border-blue-500/40 text-blue-400 ml-1">
                                    {{ $viewed }} viewed
                                </span>
                            @endif
                        </div>
                        <p class="mb-3 text-sm text-gray-400">{{ Str::limit($album->description ?? 'No description', 80) }}</p>
                        <div class="flex items-center gap-3 text-sm text-gray-300">
                            <span class="px-2 py-1 border rounded-lg bg-blue-500/10 border-blue-500/20">📝 {{ $album->posts_count }} Posts</span>
                            <span class="px-2 py-1 border rounded-lg bg-blue-500/10 border-blue-500/20">👁️ {{ $album->views_count }} Views</span>
                        </div>
                    </div>
                </a>
            @empty
                <div class="py-12 text-center col-span-full">
                    <p class="text-lg text-gray-400">No albums from {{ $user->name }}</p>
                </div>
            @endforelse
        </div>
    </div>
    <div class="h-32"></div>
</div>
