<div class="p-6 rounded-2xl border border-blue-700/30 bg-slate-900/80">
    <h2 class="text-xl font-bold mb-4">All Comments ({{ $comments->total() }})</h2>
    @livewire('post.comment-form', ['post' => $post], key('comment-form-' . $post->id))
    <div class="flex flex-wrap gap-2 mb-4 items-center">
        <label class="text-xs text-gray-400">Sort/Filter:</label>
        <select wire:model="commentView" wire:change="$refresh" class="px-2 py-1 rounded bg-slate-800 text-white text-xs border border-blue-700/30">
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
            <input wire:model.debounce.300ms="commentKeyword" wire:input="$refresh" type="text" placeholder="Search..." class="px-2 py-1 rounded bg-slate-800 text-white text-xs border border-blue-700/30" />
        @endif
    </div>
    <div class="space-y-4 mt-6">
        @foreach($comments as $comment)
            @livewire('post.comment-thread', ['comment' => $comment], key('comments-page-' . $comment->id))
        @endforeach
    </div>
    <div class="mt-6">{{ $comments->links('pagination::tailwind') }}</div>
</div>
