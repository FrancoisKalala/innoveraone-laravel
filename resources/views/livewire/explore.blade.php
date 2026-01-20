<div class="max-w-6xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-white mb-2 flex items-center gap-3">
            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M15.5 1h-8C6.12 1 5 2.12 5 3.5v17C5 21.88 6.12 23 7.5 23h8c1.38 0 2.5-1.12 2.5-2.5v-17C18 2.12 16.88 1 15.5 1zm-4 21c-.83 0-1.5-.67-1.5-1.5s.67-1.5 1.5-1.5 1.5.67 1.5 1.5-.67 1.5-1.5 1.5zm4.5-4H7V4h9v14z"/></svg>
            Explore
        </h1>
        <p class="text-gray-400">Discover new people and content</p>
    </div>

    <!-- Search & Filters -->
    <div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border border-blue-700/20 p-6 mb-8">
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <input type="text" wire:model.live="searchQuery" placeholder="Search users..." class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white placeholder-gray-500 border border-blue-700/20 focus:border-blue-700 focus:outline-none transition">
            </div>
            <div>
                <select wire:model.live="categoryFilter" class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white border border-blue-700/20 focus:border-blue-700 focus:outline-none transition">
                    <option value="">All Categories</option>
                    <option value="photography">Photography</option>
                    <option value="art">Art</option>
                    <option value="music">Music</option>
                    <option value="travel">Travel</option>
                    <option value="food">Food</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Users Grid -->
    <div>
        <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            Popular Users
        </h2>

        @if($users->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($users as $user)
                    <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-blue-700/20 p-6 hover:border-blue-700/50 transition group">
                        <!-- User Avatar -->
                        <div class="w-24 h-24 rounded-full mx-auto mb-4 bg-gradient-to-br from-blue-700 to-black flex items-center justify-center">
                            <span class="text-4xl font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                        </div>

                        <!-- User Info -->
                        <div class="text-center mb-4">
                            <h3 class="text-lg font-bold text-white group-hover:text-blue-600 transition">{{ $user->name }}</h3>
                            <p class="text-blue-600">@{{ $user->username }}</p>
                            <p class="text-sm text-gray-400 mt-2">{{ $user->bio ?? 'No bio' }}</p>
                        </div>

                        <!-- Stats -->
                        <div class="grid grid-cols-3 gap-2 mb-4 py-4 border-y border-blue-700/20">
                            <div class="text-center">
                                <p class="text-lg font-bold text-blue-600">{{ $user->posts()->count() }}</p>
                                <p class="text-xs text-gray-400">Posts</p>
                            </div>
                            <div class="text-center">
                                <p class="text-lg font-bold text-blue-600">{{ $user->followers()->count() }}</p>
                                <p class="text-xs text-gray-400">Followers</p>
                            </div>
                            <div class="text-center">
                                <p class="text-lg font-bold text-blue-600">{{ $user->albums()->count() }}</p>
                                <p class="text-xs text-gray-400">Albums</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <button wire:click="toggleFollow({{ $user->id }})" class="flex-1 py-2 px-4 rounded-lg font-semibold text-sm transition cursor-pointer
                                {{ in_array($user->id, $followingIds)
                                    ? 'bg-slate-700 text-gray-200 hover:bg-slate-600'
                                    : 'bg-gradient-to-r from-blue-700 to-black text-white hover:shadow-lg' }}">
                                {{ in_array($user->id, $followingIds) ? 'Following' : 'Follow' }}
                            </button>
                            <a href="{{ route('profile') }}" class="flex-1 py-2 px-4 bg-slate-700 text-white rounded-lg font-semibold text-sm hover:bg-slate-600 transition text-center">
                                View
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-5xl mb-3">🔍</div>
                <p class="text-gray-400">No users found</p>
            </div>
        @endif
    </div>

    <!-- Trending Albums -->
    <div class="mt-12">
        <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
            Trending Albums
        </h2>

        @if($albums->count() > 0)
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($albums as $album)
                    <a href="{{ route('albums', ['album' => $album->id]) }}" class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-blue-700/20 p-4 hover:border-blue-700/50 transition group">
                        <div class="aspect-square rounded-lg bg-gradient-to-r from-blue-700 to-black mb-4 flex items-center justify-center overflow-hidden relative">
                            <div class="absolute inset-0 opacity-20" style="background: url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.1"%3E%3Cpath d="M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E'); background-repeat: repeat;"></div>
                            <span class="text-3xl">🖼️</span>
                        </div>
                        <h4 class="font-bold text-white group-hover:text-blue-600 transition truncate">{{ $album->title }}</h4>
                        <p class="text-sm text-blue-600">by {{ $album->user->name }}</p>
                        <p class="text-xs text-gray-400 mt-2">{{ $album->posts()->count() }} posts</p>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-gray-400">No public albums yet</p>
            </div>
        @endif
    </div>
</div>

