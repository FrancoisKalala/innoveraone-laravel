<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-white mb-2 flex items-center gap-3">
            <svg class="w-7 h-7 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 4h-3.5l-1-1h-5l-1 1H5v2h14V4zM6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12z"/>
            </svg>
            Published Posts
        </h1>
        <p class="text-gray-400">Posts that are visible to others.</p>
    </div>
    @if($publishedPosts->count() > 0)
        <div class="space-y-6">
            @foreach($publishedPosts as $post)
                @livewire('post.post-card', ['post' => $post], key('published-'.$post->id))
            @endforeach
        </div>
        <div class="mt-8">
            {{ $publishedPosts->links() }}
        </div>
    @else
        <div class="text-center py-16 bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border border-blue-700/20">
            <div class="text-6xl mb-4">📢</div>
            <h3 class="text-2xl font-bold text-white mb-2">No Published Posts</h3>
            <p class="text-gray-400">Your published posts will appear here.</p>
        </div>
    @endif
</div>
