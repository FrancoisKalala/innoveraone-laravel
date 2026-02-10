<div class="space-y-6">
    @if(session()->has('success'))
        <div class="p-4 text-green-400 border rounded-lg bg-green-500/20 border-green-500/50">{{ session('success') }}</div>
    @endif

    <!-- Album Header -->
    <div class="overflow-hidden border bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border-blue-500/20 relative">
        @livewire('album.album-favorite-button', ['album' => $selectedAlbum], key('album-fav-'.$selectedAlbum->id))
        <div class="relative h-40 bg-gradient-to-r from-blue-600 to-black">
            <div class="absolute inset-0 opacity-20" style="background: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"0.1\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E'); background-repeat: repeat;"></div>
        </div>

        <div class="px-6 py-8 md:px-8">
            <div class="flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
                <div>
                    <h1 class="mb-2 text-3xl font-bold text-white md:text-4xl">{{ $selectedAlbum->title }}</h1>
                    <p class="mb-3 text-blue-400">by <a href="#" class="font-semibold hover:text-blue-300">{{ $selectedAlbum->user->name }}</a></p>
                    <p class="max-w-2xl text-gray-300">{{ $selectedAlbum->description }}</p>

                    <div class="flex flex-wrap gap-6 mt-4">
                        <div class="flex items-center gap-2 text-gray-300">
                            <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-5.04-6.71l-2.75 3.54h2.86l4.04-5.25-1.57-1.21-2.58 3.52z"/></svg>
                            <span class="font-semibold">{{ $selectedAlbum->posts()->count() }} posts</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-300">
                            <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/></svg>
                            <span class="font-semibold">{{ $selectedAlbum->views()->count() }} views</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-300">
                            <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                            <span class="font-semibold">{{ $selectedAlbum->favorites()->count() }} favorites</span>
                        </div>
                    </div>

                    <div class="mt-4">
                        <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold {{ $selectedAlbum->visibility === 'public' ? 'bg-green-500/20 text-green-400' : 'bg-blue-500/20 text-blue-400' }}">
                            {{ ucfirst($selectedAlbum->visibility) }}
                        </span>
                    </div>
                </div>

                @if(auth()->id() === $selectedAlbum->user_id)
                    <div class="flex gap-4 mt-6">
                        <a href="{{ route('albums', ['edit' => $selectedAlbum->id]) }}" class="px-6 py-2 font-semibold text-white transition rounded-full shadow-lg bg-gradient-to-r from-blue-700 to-purple-700 hover:from-blue-800 hover:to-purple-800">
                            Edit Album
                        </a>
                        <button wire:click="deleteAlbum" onclick="confirm('Delete this album?') || event.stopImmediatePropagation()" class="px-6 py-2 font-semibold text-red-400 transition border rounded-full shadow-lg bg-gradient-to-r from-red-500 to-black border-red-500/50 hover:bg-red-500/30">
                            Delete
                        </button>
                        <a href="{{ route('albums.posts', $selectedAlbum->id) }}" class="px-6 py-2 font-semibold text-white transition rounded-full shadow-lg bg-gradient-to-r from-blue-600 to-black hover:from-blue-800 hover:to-black">
                            View Posts
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Posts Grid -->
    <div class="p-6 border bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border-blue-500/20">
        <h2 class="flex items-center gap-2 mb-6 text-2xl font-bold text-white">
            <svg class="w-6 h-6 text-blue-400" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
            Album Posts
        </h2>

        @if($selectedAlbum->posts()->count() > 0)
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3">
                @forelse($selectedAlbum->posts()->orderByDesc('created_at')->paginate(10) as $post)
                    <div class="relative p-4 transition-transform border shadow-lg group bg-gradient-to-br from-slate-900/80 to-blue-900/60 border-blue-500/20 rounded-2xl hover:-translate-y-1 hover:shadow-2xl">
                        <!-- Badge for post type/media -->
                        @if($post->type ?? false)
                            <span class="absolute px-3 py-1 text-xs font-semibold text-blue-400 rounded-full top-4 left-4 bg-blue-500/20 backdrop-blur-sm">{{ ucfirst($post->type) }}</span>
                        @endif
                        <!-- Post Card -->
                        <div class="flex flex-col h-full">
                            @livewire('post.post-card', ['post' => $post], key($post->id))
                        </div>
                    </div>
                @empty
                    <p class="py-8 text-center text-gray-400">No posts in this album yet</p>
                @endforelse
            </div>

            @if($selectedAlbum->posts()->paginate(10)->hasPages())
                <div class="mt-6">
                    {{ $selectedAlbum->posts()->paginate(10)->links() }}
                </div>
            @endif
        @else
            <div class="py-16 text-center">
                <div class="mb-3 text-5xl">📸</div>
                <p class="mb-4 text-gray-400">No posts in this album yet</p>
                @if(auth()->id() === $selectedAlbum->user_id)
                    <a href="{{ route('dashboard') }}" class="inline-block px-6 py-2 font-semibold text-white transition rounded-lg bg-gradient-to-r from-blue-600 to-black hover:shadow-lg">
                        Create Post
                    </a>
                @endif
            </div>
        @endif
    </div>

    <!-- Album Stats -->
    <div class="grid gap-6 md:grid-cols-3">
        <div class="p-6 border bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border-blue-500/20">
            <h3 class="flex items-center gap-2 mb-2 text-lg font-bold text-white">
                <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                Total Views
            </h3>
            <p class="text-4xl font-bold text-transparent bg-gradient-to-r from-blue-400 to-black bg-clip-text">{{ $selectedAlbum->views()->count() }}</p>
        </div>

        <div class="p-6 border bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border-blue-500/20">
            <h3 class="flex items-center gap-2 mb-2 text-lg font-bold text-white">
                <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                Favorites
            </h3>
            <p class="text-4xl font-bold text-transparent bg-gradient-to-r from-blue-400 to-black bg-clip-text">{{ $selectedAlbum->favorites()->count() }}</p>
        </div>

        <div class="p-6 border bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border-blue-500/20">
            <h3 class="flex items-center gap-2 mb-2 text-lg font-bold text-white">
                <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm3.5-9c.83 0 1.5-.67 1.5-1.5S16.33 8 15.5 8 14 8.67 14 9.5s.67 1.5 1.5 1.5zm-7 0c.83 0 1.5-.67 1.5-1.5S9.33 8 8.5 8 7 8.67 7 9.5 7.67 11 8.5 11zm3.5 6.5c2.33 0 4.31-1.46 5.11-3.5H6.89c.8 2.04 2.78 3.5 5.11 3.5z"/></svg>
                Created
            </h3>
            <p class="text-lg font-semibold text-gray-300">{{ $selectedAlbum->created_at->format('M d, Y') }}</p>
        </div>
    </div>
</div>

