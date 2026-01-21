<div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-blue-500/20 overflow-hidden hover:border-blue-500/40 transition shadow-2xl group">
    <div class="p-6 bg-gradient-to-r from-blue-500/10 to-black/10 border-b border-blue-500/20">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-3">
                <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=3b82f6&color=fff' }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full object-cover ring-2 ring-blue-500/50">
                <div>
                    <h3 class="font-bold text-white text-lg">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-400">{{ '@' . ($user->username ?? strtolower(str_replace(' ', '', $user->name))) }} • {{ $post->created_at->diffForHumans() }}</p>
                </div>
            </div>
            @if($post->user_id === auth()->id())
                <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                    <button wire:click="openEditModal" class="p-2 hover:bg-blue-500/20 rounded transition" title="Edit post">
                        <svg class="w-5 h-5 text-blue-400 hover:text-blue-300" fill="currentColor" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                    </button>
                    <button wire:click="deletePost" onclick="return confirm('Move this post to expired posts?')" class="p-2 hover:bg-red-500/20 rounded transition" title="Delete post">
                        <svg class="w-5 h-5 text-red-400 hover:text-red-300" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-9l-1 1H5v2h14V4z"/></svg>
                    </button>
                </div>
            @endif
        </div>
    </div>
    <div class="p-6 space-y-4">
        @if($post->album)<span class="inline-block px-3 py-1 bg-gradient-to-r from-blue-500 to-cyan-500 text-white text-xs font-semibold rounded-full">📁 {{ $post->album->title }}</span>@endif
        <p class="text-gray-100 text-base leading-relaxed">{{ Str::limit($post->content, 300) }}</p>
        
        @if($files->count() > 0)
            <!-- Images Grid -->
            @php $images = $files->filter(fn($f) => str_contains($f->file_type, 'image')); @endphp
            @if($images->count() > 0)
                <div class="mt-4 space-y-2">
                    <p class="text-xs text-blue-400 font-semibold">🖼️ IMAGES ({{ $images->count() }})</p>
                    <div class="grid gap-3 @if($images->count() == 1) grid-cols-1 @elseif($images->count() == 2) grid-cols-2 @else grid-cols-3 @endif">
                        @foreach($images as $file)
                            <div class="relative group rounded-lg overflow-hidden ring-1 ring-blue-500/20 bg-slate-900">
                                <img src="{{ asset('storage/' . $file->file_path) }}" alt="Post image" class="w-full h-auto max-h-[600px] object-contain group-hover:scale-105 transition">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition"></div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- Videos -->
            @php $videos = $files->filter(fn($f) => str_contains($f->file_type, 'video')); @endphp
            @if($videos->count() > 0)
                <div class="mt-4 space-y-2">
                    <p class="text-xs text-purple-400 font-semibold">🎬 VIDEOS ({{ $videos->count() }})</p>
                    <div class="space-y-3">
                        @foreach($videos as $file)
                            <video class="w-full h-auto max-h-[600px] rounded-lg bg-black ring-1 ring-blue-500/20" controls>
                                <source src="{{ asset('storage/' . $file->file_path) }}" type="{{ $file->file_type }}">
                            </video>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- Audio -->
            @php $audio = $files->filter(fn($f) => str_contains($f->file_type, 'audio')); @endphp
            @if($audio->count() > 0)
                <div class="mt-4 space-y-2">
                    <p class="text-xs text-green-400 font-semibold">🎵 AUDIO ({{ $audio->count() }})</p>
                    <div class="space-y-2">
                        @foreach($audio as $file)
                            <div class="bg-gradient-to-r from-green-500/10 to-emerald-500/10 p-3 rounded-lg border border-green-500/20 ring-1 ring-green-500/20">
                                <audio class="w-full" controls style="height: 36px;">
                                    <source src="{{ asset('storage/' . $file->file_path) }}" type="{{ $file->file_type }}">
                                </audio>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- PDFs -->
            @php $pdfs = $files->filter(fn($f) => str_contains($f->file_type, 'pdf')); @endphp
            @if($pdfs->count() > 0)
                <div class="mt-4 space-y-2">
                    <p class="text-xs text-red-400 font-semibold">📄 DOCUMENTS ({{ $pdfs->count() }})</p>
                    <div class="space-y-2">
                        @foreach($pdfs as $file)
                            <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="flex items-center gap-3 p-3 bg-gradient-to-r from-red-500/10 to-pink-500/10 rounded-lg border border-red-500/20 hover:border-red-500/40 transition ring-1 ring-red-500/20">
                                <svg class="w-6 h-6 text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20M12,19L8,15H10.5V12H13.5V15H16L12,19Z"/></svg>
                                <div>
                                    <p class="text-sm font-semibold text-red-300">{{ basename($file->file_path) }}</p>
                                    <p class="text-xs text-gray-400">PDF Document • Click to view</p>
                                </div>
                                <svg class="w-4 h-4 text-gray-500 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- SVG/Vector -->
            @php $vectors = $files->filter(fn($f) => str_contains($f->file_type, 'svg') || str_ends_with($file->file_path ?? '', '.svg')); @endphp
            @if($vectors->count() > 0)
                <div class="mt-4 space-y-2">
                    <p class="text-xs text-yellow-400 font-semibold">🎨 VECTOR GRAPHICS ({{ $vectors->count() }})</p>
                    <div class="grid gap-3 grid-cols-2">
                        @foreach($vectors as $file)
                            <div class="relative group rounded-lg overflow-hidden bg-gradient-to-br from-yellow-500/10 to-orange-500/10 ring-1 ring-yellow-500/20 flex items-center justify-center h-40">
                                <svg class="w-12 h-12 text-yellow-400 opacity-50" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/></svg>
                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="absolute inset-0 opacity-0 hover:opacity-100 bg-black/50 flex items-center justify-center transition">
                                    <p class="text-white text-xs font-semibold">Open SVG</p>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- 3D Models -->
            @php $models = $files->filter(fn($f) => str_contains($f->file_type, 'model') || \Illuminate\Support\Str::endsWith($f->file_path ?? '', ['.glb', '.gltf', '.obj'])); @endphp
            @if($models->count() > 0)
                <div class="mt-4 space-y-2">
                    <p class="text-xs text-cyan-400 font-semibold">🎯 3D MODELS ({{ $models->count() }})</p>
                    <div class="space-y-2">
                        @foreach($models as $file)
                            <a href="{{ asset('storage/' . $file->file_path) }}" download class="flex items-center gap-3 p-3 bg-gradient-to-r from-cyan-500/10 to-blue-500/10 rounded-lg border border-cyan-500/20 hover:border-cyan-500/40 transition ring-1 ring-cyan-500/20">
                                <svg class="w-6 h-6 text-cyan-400 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7V12C2 16.5 4.23 20.68 7.5 23.8L12 20L16.5 23.8C19.77 20.68 22 16.5 22 12V7L12 2M12 4.18L19 7.5V12C19 15.03 17.54 17.74 15.35 19.44L12 16.6L8.65 19.44C6.46 17.74 5 15.03 5 12V7.5L12 4.18Z"/></svg>
                                <div>
                                    <p class="text-sm font-semibold text-cyan-300">{{ basename($file->file_path) }}</p>
                                    <p class="text-xs text-gray-400">3D Model • Click to download</p>
                                </div>
                                <svg class="w-4 h-4 text-gray-500 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif
        <div class="flex gap-6 text-sm text-gray-400 pt-4 border-t border-blue-500/20">
            <div class="flex items-center gap-2"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg><span>{{ $likeCount }}</span></div>
            <div class="flex items-center gap-2"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg><span>{{ $commentCount }}</span></div>
            <div class="flex items-center gap-2 ml-auto text-xs">⏱️ {{ $post->expiration_hours }}h expiration</div>
        </div>
    </div>
    @if($post->interaction_type !== 'none')
    <div class="px-6 py-4 bg-slate-900/50 border-t border-blue-500/20 flex gap-3">
        @if(in_array($post->interaction_type, ['like', 'like_comment', 'all']))
        <button wire:click="toggleLike" class="flex-1 py-2 px-4 rounded-lg font-semibold transition flex items-center justify-center gap-2 {{ $isLiked ? 'bg-gradient-to-r from-red-600 to-red-700 text-white' : 'bg-slate-800 text-gray-300 hover:bg-slate-700' }}"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>Like</button>
        @endif
        @if(in_array($post->interaction_type, ['comment', 'like_comment', 'all']))
        <button wire:click="toggleComments" class="flex-1 py-2 px-4 rounded-lg font-semibold transition bg-slate-800 text-gray-300 hover:bg-slate-700 flex items-center justify-center gap-2"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm0 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg>Comment</button>
        @endif
        <button class="flex-1 py-2 px-4 rounded-lg font-semibold transition bg-slate-800 text-gray-300 hover:bg-slate-700 flex items-center justify-center gap-2"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.15c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.44 9.31 6.77 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.77 0 1.44-.3 1.96-.77l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/></svg>Share</button>
    </div>
    @endif
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

<!-- Edit Post Modal -->
@if($showEditModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div wire:click="closeEditModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>
        <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-blue-700/30 p-8 max-w-2xl w-full relative z-10">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-white">Edit Post</h2>
                <button type="button" wire:click="closeEditModal" class="text-gray-400 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form wire:submit.prevent="updatePost" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Content</label>
                    <textarea wire:model="editContent" rows="6" class="w-full px-4 py-3 rounded-lg bg-slate-700 text-white placeholder-gray-500 border border-blue-700/20 focus:border-blue-700 focus:outline-none transition" placeholder="What's on your mind?"></textarea>
                    @error('editContent') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="flex gap-2 pt-4">
                    <button type="button" wire:click="closeEditModal" class="flex-1 px-4 py-2 bg-slate-700 text-white rounded-lg font-semibold hover:bg-slate-600 transition">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 bg-gradient-to-r from-blue-700 to-black text-white rounded-lg font-semibold hover:shadow-lg transition">
                        Update Post
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif
