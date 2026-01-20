<div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-blue-700/20 p-4">
    <div class="flex items-start gap-3">
        <img src="{{ $comment->user->profile_photo_path ? asset('storage/' . $comment->user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) . '&background=8b5cf6&color=fff' }}" alt="{{ $comment->user->name }}" class="w-10 h-10 rounded-full object-cover flex-shrink-0">
        <div class="flex-1">
            <div class="flex items-center gap-2 mb-2">
                <h4 class="font-bold text-white text-sm">{{ $comment->user->name }}</h4>
                <span class="text-xs text-gray-500">@{{ $comment->user->username }}</span>
                <span class="text-xs text-gray-600">{{ $comment->created_at->diffForHumans() }}</span>
            </div>
            <p class="text-gray-200 mb-2 text-sm">{{ $comment->content }}</p>
            <div class="flex gap-3 text-xs">
                <button wire:click="toggleLike" class="flex items-center gap-1 transition {{ $isLiked ? 'text-red-500' : 'text-gray-400 hover:text-red-500' }}"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>{{ $likeCount }}</button>
                <button wire:click="toggleDislike" class="flex items-center gap-1 transition {{ $isDisliked ? 'text-blue-500' : 'text-gray-400 hover:text-blue-500' }}"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M15 1H9c-.55 0-1 .45-1 1v14h2V4h6v12h2V2c0-.55-.45-1-1-1zm4 4v14H9.46c-.9 0-1.64.5-2.05 1.23l-1.44 2.77c-.3.59-.84.77-1.39.77-.67 0-1.22-.55-1.22-1.22V9c0-1.1.9-2 2-2h13.91c1.1 0 2 .9 2 2z"/></svg>{{ $dislikeCount }}</button>
                <button wire:click="$toggle('showReplies')" class="text-gray-400 hover:text-blue-600 transition">💬 Reply</button>
                @if($comment->user_id === auth()->id())<button wire:click="deleteComment" class="text-gray-400 hover:text-red-500 transition">🗑️</button>@endif
            </div>
            @if($showReplies)
                <div class="mt-3 pt-3 border-t border-blue-700/20">
                    <form wire:submit.prevent="addReply" class="space-y-2">
                        <textarea wire:model="content" placeholder="Write a reply..." class="w-full px-3 py-2 rounded-lg bg-slate-700 text-white placeholder-gray-500 border border-blue-700/20 focus:border-blue-700 focus:outline-none transition text-xs" rows="2"></textarea>
                        <button type="submit" class="px-3 py-1 bg-gradient-to-r from-blue-700 to-black text-white rounded text-xs font-semibold hover:shadow-lg transition">Reply</button>
                    </form>
                </div>
            @endif
            @if($replies->count() > 0)
                <div class="mt-3 space-y-2 pl-3 border-l border-blue-700/20">
                    @foreach($replies as $reply)
                        <div class="bg-slate-700/30 p-2 rounded text-xs">
                            <div class="flex items-start gap-2 mb-1">
                                <img src="{{ $reply->user->profile_photo_path ? asset('storage/' . $reply->user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($reply->user->name) . '&background=8b5cf6&color=fff' }}" alt="{{ $reply->user->name }}" class="w-6 h-6 rounded-full object-cover flex-shrink-0">
                                <div class="flex-1"><span class="font-semibold text-white">{{ $reply->user->name }}</span><span class="text-gray-500 ml-1">{{ $reply->created_at->diffForHumans() }}</span></div>
                            </div>
                            <p class="text-gray-300">{{ $reply->content }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

