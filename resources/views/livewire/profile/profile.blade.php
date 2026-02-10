<div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900">
    <!-- Banner -->
    <div class="relative h-48 shadow-lg bg-gradient-to-r from-blue-800/60 to-purple-800/60 rounded-b-3xl">
        <!-- Avatar -->
        <div class="absolute bottom-0 transform -translate-x-1/2 translate-y-1/2 left-1/2">
            <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" class="object-cover w-32 h-32 border-4 border-white rounded-full shadow-xl bg-slate-700" />
        </div>
    </div>
    <!-- Profile Card -->
    <div class="max-w-4xl p-8 mx-auto mt-20 shadow-2xl bg-slate-900/80 rounded-2xl backdrop-blur">
        <div class="flex flex-col items-center">
            <h2 class="text-3xl font-extrabold text-white">{{ $user->name }}</h2>
            <div class="font-mono text-lg text-blue-400">
                @if($user->username)
                    {{'@'. $user->username }}
                @else
                    @{{ strtolower(preg_replace('/\s+/', '', $user->name)) }}
                @endif
            </div>
            <p class="max-w-xl mt-2 text-center text-gray-400">{{ $user->bio ?? 'No bio provided.' }}</p>
            <div class="flex gap-8 mt-6">
                <div class="text-center">
                    <a href="{{ route('user.posts', $user->id) }}" class="block text-xl font-bold text-blue-400 transition hover:text-blue-500">{{ $user->posts()->count() }}</a>
                    <div class="text-xs text-gray-400">Posts</div>
                </div>
                <div class="text-center">
                    <a href="{{ route('follower.user', $user->id) }}" class="block text-xl font-bold text-blue-400 transition hover:text-blue-500">{{ $user->followers()->count() }}</a>
                    <div class="text-xs text-gray-400">Followers</div>
                </div>
                <div class="text-center">
                    <a href="{{ route('follower.user', $user->id) }}?tab=following" class="block text-xl font-bold text-blue-400 transition hover:text-blue-500">{{ $user->following()->count() }}</a>
                    <div class="text-xs text-gray-400">Following</div>
                </div>
                <div class="text-center">
                    <a href="{{ route('user.albums', $user->id) }}" class="block text-xl font-bold text-blue-400 transition hover:text-blue-500">{{ $user->albums()->count() }}</a>
                    <div class="text-xs text-gray-400">Albums</div>
                </div>
                <div class="text-center">
                    <a href="{{ route('contact.user', $user->id) }}" class="block text-xl font-bold text-blue-400 transition hover:text-blue-500">{{ $user->contacts()->count() }}</a>
                    <div class="text-xs text-gray-400">Contacts</div>
                </div>
            </div>
            <div class="flex gap-4 mt-8">
                @if(auth()->id() !== $user->id)
                    @if(auth()->user()->following()->where('following_id', $user->id)->exists())
                        <button wire:click="toggleFollow" class="px-6 py-2 font-semibold text-purple-400 transition bg-transparent border border-purple-400 rounded-full shadow hover:bg-purple-700 hover:text-white">Unfollow</button>
                    @else
                        <button wire:click="toggleFollow" class="px-6 py-2 font-semibold text-white transition bg-blue-600 rounded-full shadow hover:bg-blue-700">Follow</button>
                    @endif
                    <a href="{{ route('messages', ['user' => $user->id]) }}" class="flex items-center gap-2 px-6 py-2 font-semibold text-white transition rounded-full shadow bg-slate-700 hover:bg-slate-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2z"/></svg>
                        Message
                    </a>
                @endif
                <a href="{{ route('user.posts', $user->id) }}" class="px-6 py-2 font-semibold text-white transition rounded-full shadow bg-gradient-to-r from-blue-700 to-purple-700 hover:from-blue-800 hover:to-purple-800">View Posts</a>
            </div>
            <div class="flex gap-4 mt-4 text-xs text-gray-400">
                <span class="flex items-center gap-1"><svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Joined {{ $user->created_at->format('M Y') }}</span>
                <span class="flex items-center gap-1"><svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"/></svg> Last seen {{ $user->updated_at->diffForHumans() }}</span>
            </div>
        </div>
    </div>
    <!-- Content -->
    <div class="max-w-4xl mx-auto mt-8">
        <h3 class="flex items-center gap-2 mb-4 text-xl font-bold text-white">
            <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 24 24"><path d="M19 21l-7-5-7 5V5a2 2 0 012-2h10a2 2 0 012 2z"/></svg>
            Recent Posts
        </h3>
        <div>
            @foreach($user->posts()->latest()->take(5)->get() as $post)
                @livewire('post.post-card', ['post' => $post], key('profile-post-card-'.$post->id))
            @endforeach
            @if($user->posts()->count() === 0)
                <div class="text-gray-400">No posts yet.</div>
            @endif
        </div>
        <div class="mb-32"></div>
    </div>
</div>
