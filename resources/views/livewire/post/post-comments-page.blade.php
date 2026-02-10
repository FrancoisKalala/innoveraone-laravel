<div class="min-h-screen bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900">
    <div class="max-w-3xl px-4 py-8 mx-auto">
        <!-- Post Title Block -->
        <div class="relative mb-8">
            <div class="absolute top-0 left-0">
                <button onclick="window.history.back()" class="flex items-center gap-2 px-4 py-2 font-semibold text-white transition shadow-lg bg-gradient-to-r from-blue-700 to-black rounded-xl hover:scale-105 hover:bg-blue-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Back
                </button>
            </div>
            <div class="flex flex-col items-center px-6 py-8 overflow-hidden border shadow-xl bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border-blue-500/20">
                <div class="flex items-center gap-4 mb-4">
                    <a href="{{ route('user.profile', $post->user->id) }}" class="flex items-center justify-center transition rounded-full shadow-lg w-14 h-14 bg-gradient-to-br from-blue-700 to-black hover:scale-105">
                        <span class="text-3xl font-bold text-white">{{ substr($post->user->name, 0, 1) }}</span>
                    </a>
                    <h2 class="text-2xl font-extrabold tracking-tight text-white drop-shadow">{{ $post->user->name }}'s Post</h2>
                </div>
                <p class="mb-2 text-lg text-blue-200">{{ $post->created_at->format('M d, Y H:i') }}</p>
                <span class="inline-block px-3 py-1 text-xs font-semibold text-blue-400 rounded-full bg-blue-500/20">
                    {{ $post->comments()->count() }} Comments
                </span>
            </div>
        </div>
        <!-- Comments Block (unchanged) -->
        <div class="p-6 overflow-y-auto border shadow-2xl rounded-2xl border-blue-700/30 bg-slate-900/80 backdrop-blur-xl" style="scrollbar-width: thin; scrollbar-color: rgba(168, 85, 247, 0.5) transparent;">
            @livewire('post.comment-form', ['post' => $post], key('comment-form-' . $post->id))
            <div class="flex flex-wrap items-center gap-2 mb-4">
                <label class="text-xs text-gray-400">Sort/Filter:</label>
                <select wire:model="commentView" wire:change="$refresh" class="px-2 py-1 text-xs text-white border rounded bg-slate-800 border-blue-700/30">
                    <option value="all">All (Newest)</option>
                    <option value="pinned">Pinned</option>
                    <option value="highlighted">Highlighted</option>
                    <option value="mine">My Comments</option>
                    <option value="keyword">Keyword</option>
                    <option value="most_liked">Most Liked</option>
                    <option value="most_replied">Most Replied</option>
                    <option value="oldest">Oldest</option>
                </select>
                @if($commentView === 'keyword')
                    <input wire:model.debounce.300ms="commentKeyword" wire:input="$refresh" type="text" placeholder="Search..." class="px-2 py-1 text-xs text-white border rounded bg-slate-800 border-blue-700/30" />
                @endif
            </div>
            <div class="mt-6 space-y-4">
                @foreach($comments as $comment)
                    @livewire('post.comment-thread', ['comment' => $comment], key('comments-page-' . $comment->id))
                @endforeach
            </div>
            <div class="mt-20">{{ $comments->links('pagination::tailwind') }}</div>
        </div>
    </div>
</div>
