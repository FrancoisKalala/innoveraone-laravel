<div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-blue-500/20 overflow-hidden hover:border-blue-500/40 transition shadow-2xl">
    <div class="p-6 bg-gradient-to-r from-blue-500/10 to-black/10 border-b border-blue-500/20">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-3">
                <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=3b82f6&color=fff' }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full object-cover ring-2 ring-blue-500/50">
                <div>
                    <h3 class="font-bold text-white text-lg">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-400">@{{ $user->username }} • {{ $post->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @if($post->user_id === auth()->id())
                <button wire:click="deletePost" class="text-gray-400 hover:text-red-500 transition">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-9l-1 1H5v2h14V4z"/></svg>
                </button>
            @endif
        </div>
    </div>
    <div class="p-6 space-y-4">
        @if($post->chapter)<span class="inline-block px-3 py-1 bg-gradient-to-r from-blue-500 to-cyan-500 text-white text-xs font-semibold rounded-full">🖼️ {{ $post->chapter->title }}</span>@endif
        <p class="text-gray-100 text-base leading-relaxed">{{ Str::limit($post->content, 300) }}</p>
        @if($files->count() > 0)
            <div class="grid gap-3 mt-4">
                @foreach($files as $file)
                    <div class="relative group rounded-lg overflow-hidden">
                        @if(str_contains($file->file_type, 'image'))
                            <img src="{{ asset('storage/' . $file->file_path) }}" alt="Post media" class="w-full h-48 object-cover group-hover:scale-105 transition">
                        @else
                            <div class="w-full h-48 bg-gradient-to-br from-blue-500 to-black flex items-center justify-center group-hover:scale-105 transition">
                                <svg class="w-12 h-12 text-white opacity-50" fill="currentColor" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/></svg>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
        <div class="flex gap-6 text-sm text-gray-400 pt-4 border-t border-blue-500/20">
            <div class="flex items-center gap-2"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg><span>{{ $likeCount }}</span></div>
            <div class="flex items-center gap-2"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg><span>{{ $commentCount }}</span></div>
            <div class="flex items-center gap-2 ml-auto text-xs">⏱️ {{ $post->expiration_hours }}h expiration</div>
        </div>
    </div>
    <div class="px-6 py-4 bg-slate-900/50 border-t border-blue-500/20 flex gap-3">
        <button wire:click="toggleLike" class="flex-1 py-2 px-4 rounded-lg font-semibold transition flex items-center justify-center gap-2 {{ $isLiked ? 'bg-gradient-to-r from-red-600 to-red-700 text-white' : 'bg-slate-800 text-gray-300 hover:bg-slate-700' }}"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>Like</button>
        <button wire:click="toggleComments" class="flex-1 py-2 px-4 rounded-lg font-semibold transition bg-slate-800 text-gray-300 hover:bg-slate-700 flex items-center justify-center gap-2"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg>Comment</button>
        <button class="flex-1 py-2 px-4 rounded-lg font-semibold transition bg-slate-800 text-gray-300 hover:bg-slate-700 flex items-center justify-center gap-2"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.15c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.44 9.31 6.77 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.77 0 1.44-.3 1.96-.77l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/></svg>Share</button>
    </div>
    @if($showComments)
        <div class="bg-slate-900/50 border-t border-blue-500/20 p-6 space-y-4">
            <h4 class="font-bold text-white mb-4">Comments ({{ $commentCount }})</h4>
            @forelse($comments as $comment)
                @livewire('post.comment-thread', ['comment' => $comment], key($comment->id))
            @empty
                <p class="text-gray-400 text-center py-4">No comments yet. Be the first!</p>
            @endforelse
        </div>
    @endif
</div>

