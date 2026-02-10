<div class="overflow-hidden transition border shadow-2xl bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border-blue-700/20 hover:border-blue-700/40 backdrop-blur-xl animate-fade-in">
    <div class="p-6 border-b border-blue-700/10">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center flex-1 gap-4">
                <div class="flex items-center justify-center flex-shrink-0 w-16 h-16 rounded-full shadow-lg bg-gradient-to-br from-blue-700 to-black ring-4 ring-blue-500/50">
                    <span class="text-2xl font-bold text-white">{{ substr($post->user->name, 0, 1) }}</span>
                </div>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-white">{{ $post->user->name }}</h3>
                    <p class="text-sm text-gray-400">{{ '@' . ($post->user->username ?? strtolower(str_replace(' ', '', $post->user->name))) }} • {{ $post->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @if ($post->chapter)
                <span class="px-3 py-1 text-xs font-semibold text-blue-300 rounded-full bg-gradient-to-r from-blue-500/20 to-cyan-500/20 whitespace-nowrap">
                    🖼️ {{ $post->chapter->name }}
                </span>
            @endif
        </div>
        <div class="mt-2">
            <p class="text-base leading-relaxed text-gray-200">{{ $post->content }}</p>
            @if ($post->media->count() > 0)
                <div class="grid grid-cols-1 {{ $post->media->count() >= 2 ? 'md:grid-cols-2' : '' }} gap-4 mt-4">
                    @foreach ($post->media->take(4) as $media)
                        @if (in_array($media->type, ['image', 'photo']))
                            <div class="relative overflow-hidden rounded-xl aspect-square group">
                                <img src="{{ Storage::url($media->path) }}" alt="Post media" class="object-cover w-full h-full transition group-hover:scale-105">
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    <div class="px-6 py-4 border-t border-blue-700/10">
        <div class="flex gap-6 pb-4 text-sm text-gray-400 border-b border-blue-500/20">
            <div class="flex items-center gap-2"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg><span>{{ $post->likes->count() }}</span></div>
            <div class="flex items-center gap-2"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg><span>{{ $post->comments->count() }}</span></div>
            <div class="flex items-center gap-2 ml-auto text-xs">⏱️ {{ $post->expiration_hours }}h expiration</div>
        </div>
        @if($post->interaction_type !== 'none')
        <div class="flex gap-3 pt-4">
            @if(in_array($post->interaction_type, ['like', 'like_comment', 'all']))
                @foreach(['👍','😂','😍','😮'] as $emoji)
                    <button
                        @click="$dispatch('show-guest-modal', { action: 'react to this post' })"
                        class="flex items-center gap-1 px-2 py-1 text-2xl transition duration-200 ease-in-out rounded-full shadow-lg bg-slate-800 hover:scale-125 hover:bg-blue-700"
                        style="will-change: transform;"
                        title="React with {{ $emoji }}"
                    >
                        <span class="emoji-bounce">{{ $emoji }}</span>
                    </button>
                @endforeach
            @endif
            @if(in_array($post->interaction_type, ['comment', 'like_comment', 'all']))
                <button
                    @click="$dispatch('show-guest-modal', { action: 'comment on this post' })"
                    class="flex items-center justify-center flex-1 gap-2 px-4 py-2 font-semibold text-gray-300 transition rounded-lg bg-slate-800 hover:bg-slate-700"
                    title="Comment"
                >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg>
                </button>
            @endif
            <button
                @click="$dispatch('show-guest-modal', { action: 'share this post' })"
                class="flex items-center justify-center flex-1 gap-2 px-4 py-2 font-semibold text-gray-300 transition rounded-lg bg-slate-800 hover:bg-slate-700"
                title="Share"
            >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.15c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.44 9.31 6.77 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.77 0 1.44-.3 1.96-.77l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/></svg>
            </button>
        </div>
        @endif
    </div>
</div>
