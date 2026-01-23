<div class="bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border border-blue-700/20 overflow-hidden hover:border-blue-700/40 transition" wire:key="post-card-{{ $post->id }}">
    <div class="p-6 border-b border-blue-700/10">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-4 flex-1">
                <!-- Avatar with Explore Design Style -->
                <div class="flex-shrink-0 w-20 h-20 rounded-full bg-gradient-to-br from-blue-700 to-black flex items-center justify-center ring-4 ring-blue-500/50 group-hover:ring-blue-500 transition shadow-lg cursor-pointer">
                    <span class="text-3xl font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                </div>
                <div class="flex-1">
                    <h3 class="font-bold text-white text-lg group-hover:text-blue-400 transition">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-400">{{ '@' . ($user->username ?? strtolower(str_replace(' ', '', $user->name))) }} • {{ $post->created_at->diffForHumans() }}</p>
                    @if($post->user_id !== auth()->id())
                        <button wire:click="toggleFollow" class="mt-2 px-3 py-1 rounded-full text-xs font-semibold {{ $isFollowing ? 'bg-slate-700 text-white border border-blue-500/40' : 'bg-blue-600 text-white border border-blue-500/60' }} hover:opacity-90 transition">
                            {{ $isFollowing ? 'Following' : 'Follow' }}
                        </button>
                    @endif
                </div>
            </div>
            <div class="flex gap-2 relative z-10">
                @if($post->user_id === auth()->id())
                    <button
                        type="button"
                        wire:click.stop="openEditModal"
                        wire:loading.attr="disabled"
                        class="p-2 hover:bg-blue-500/20 rounded transition cursor-pointer"
                        title="Edit post"
                        aria-label="Edit post"
                    >
                        <svg class="w-5 h-5 text-blue-400 hover:text-blue-300 pointer-events-none" fill="currentColor" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                    </button>
                    <button type="button" wire:click.stop="openDeleteModal" wire:loading.attr="disabled" class="p-2 hover:bg-red-500/20 rounded transition cursor-pointer" title="Delete post">
                        <svg class="w-5 h-5 text-red-400 hover:text-red-300 pointer-events-none" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-9l-1 1H5v2h14V4z"/></svg>
                    </button>
                @endif
                <!-- Forward Dropdown -->
                <div class="relative" x-data="{ open: @entangle('showForwardOptions') }">
                    <button
                        type="button"
                        @click="open = !open"
                        class="p-2 hover:bg-green-500/20 rounded transition cursor-pointer"
                        title="Forward post"
                    >
                        <svg class="w-5 h-5 text-green-400 hover:text-green-300 pointer-events-none" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8-8-8z"/>
                            <path d="M20 4l-1.41 1.41L24.17 11H12v2h12.17l-5.58 5.59L20 20l8-8-8-8z" transform="translate(-4, 0)"/>
                        </svg>
                    </button>

                    <!-- Forward Options Dropdown -->
                    <div
                        x-show="open"
                        @click.away="open = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95"
                        x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100"
                        x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 top-full mt-2 w-80 bg-slate-800 rounded-lg shadow-xl border border-green-700/30 p-3 space-y-2 z-50"
                        style="display: none;"
                    >
                        <div class="text-xs font-semibold text-gray-400 mb-2 px-2">Forward to contacts</div>
                        <div class="space-y-1 max-h-96 overflow-y-auto">
                            @php
                                $contacts = auth()->user()->contacts()->wherePivot('is_deleted', false)->get();
                            @endphp
                            @forelse($contacts as $contact)
                                <button
                                    type="button"
                                    @click="open = false"
                                    wire:click.prevent="forwardToContact({{ $contact->id }})"
                                    class="w-full flex items-center gap-3 px-3 py-2 rounded hover:bg-slate-700 transition text-left"
                                >
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-700 to-black flex items-center justify-center ring-2 ring-green-500/30">
                                        <span class="text-sm font-bold text-white">{{ substr($contact->name, 0, 1) }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-semibold text-white truncate">{{ $contact->name }}</div>
                                        <div class="text-xs text-gray-400 truncate">@{{ $contact->username ?? strtolower(str_replace(' ', '', $contact->name)) }}</div>
                                    </div>
                                </button>
                            @empty
                                <div class="text-center py-4 text-gray-400 text-sm">No contacts available</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <div class="p-6 space-y-4">
        @if($post->album)<span class="inline-block px-3 py-1 bg-gradient-to-r from-blue-500 to-cyan-500 text-white text-xs font-semibold rounded-full">📁 {{ $post->album->title }}</span>@endif
        <p class="text-gray-100 text-base leading-relaxed">{{ Str::limit($post->content, 300) }}</p>

        @if($files->count() > 0)
            <div x-data="{ fileIndex: 0 }" class="mt-4">
                <div class="relative flex flex-col items-center justify-center">
                    @php $fileList = $files->values(); @endphp
                    <!-- File Display -->
                    @foreach($fileList as $idx => $file)
                        <div x-show="fileIndex === {{ $idx }}" x-cloak class="w-full">
                            @if(str_contains($file->file_type, 'image'))
                                <img src="{{ asset('storage/' . $file->file_path) }}" alt="Post image" class="w-full h-auto max-h-[600px] object-contain rounded-lg" />
                            @elseif(str_contains($file->file_type, 'video'))
                                <video class="w-full h-auto max-h-[600px] rounded-lg bg-black" controls>
                                    <source src="{{ asset('storage/' . $file->file_path) }}" type="{{ $file->file_type }}">
                                </video>
                            @elseif(str_contains($file->file_type, 'audio'))
                                <audio class="w-full" controls style="height: 36px;">
                                    <source src="{{ asset('storage/' . $file->file_path) }}" type="{{ $file->file_type }}">
                                </audio>
                            @elseif(str_contains($file->file_type, 'pdf'))
                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="flex items-center gap-3 p-3 bg-gradient-to-r from-red-500/10 to-pink-500/10 rounded-lg border border-red-500/20 hover:border-red-500/40 transition ring-1 ring-red-500/20">
                                    <svg class="w-6 h-6 text-red-400 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20M12,19L8,15H10.5V12H13.5V15H16L12,19Z"/></svg>
                                    <div>
                                        <p class="text-sm font-semibold text-red-300">{{ basename($file->file_path) }}</p>
                                        <p class="text-xs text-gray-400">PDF Document • Click to view</p>
                                    </div>
                                    <svg class="w-4 h-4 text-gray-500 ml-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            @else
                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="block text-blue-400 underline">Open File</a>
                            @endif
                        </div>
                    @endforeach
                    <!-- Navigation Arrows -->
                    @if($fileList->count() > 1)
                        <button @click="fileIndex = Math.max(0, fileIndex - 1)" :disabled="fileIndex === 0" class="absolute left-2 top-1/2 -translate-y-1/2 bg-slate-800/80 text-white rounded-full p-2 shadow hover:bg-blue-700 transition disabled:opacity-50">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button @click="fileIndex = Math.min({{ $fileList->count() - 1 }}, fileIndex + 1)" :disabled="fileIndex === {{ $fileList->count() - 1 }}" class="absolute right-2 top-1/2 -translate-y-1/2 bg-slate-800/80 text-white rounded-full p-2 shadow hover:bg-blue-700 transition disabled:opacity-50">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                        <!-- Dots -->
                        <div class="flex justify-center gap-2 mt-4">
                            <template x-for="i in {{ $fileList->count() }}" :key="i">
                                <span :class="fileIndex === i - 1 ? 'bg-blue-400 scale-125' : 'bg-gray-400'" class="w-3 h-3 rounded-full inline-block transition-all duration-200 cursor-pointer" @click="fileIndex = i - 1"></span>
                            </template>
                        </div>
                    @endif
                </div>
            </div>
        @endif
        <div class="flex gap-6 text-sm text-gray-400 pt-4 border-t border-blue-500/20">
            <div class="flex items-center gap-2"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg><span>{{ $likeCount }}</span></div>
            <div class="flex items-center gap-2"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg><span>{{ $commentCount }}</span></div>
            @if($shareCount > 0)
                <div class="flex items-center gap-2"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.15c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.44 9.31 6.77 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.77 0 1.44-.3 1.96-.77l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/></svg><span>{{ $shareCount }}</span></div>
            @endif
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

        <!-- Share Button with Dropdown -->
        <div class="flex-1 relative" x-data="{ open: @entangle('showShareOptions') }">
            <button
                @click="open = !open"
                class="w-full py-2 px-4 rounded-lg font-semibold transition bg-slate-800 text-gray-300 hover:bg-slate-700 flex items-center justify-center gap-2"
            >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.15c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.44 9.31 6.77 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.77 0 1.44-.3 1.96-.77l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/></svg>
                Share
                @if($shareCount > 0)
                    <span class="text-xs bg-blue-600 px-2 py-0.5 rounded-full">{{ $shareCount }}</span>
                @endif
            </button>

            <!-- Share Options Dropdown -->
            <div
                x-show="open"
                @click.away="open = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute bottom-full left-0 mb-2 w-64 bg-slate-800 rounded-lg shadow-xl border border-blue-700/30 p-3 space-y-2 z-50"
                style="display: none;"
            >
                <div class="text-xs font-semibold text-gray-400 mb-2 px-2">Share this post</div>

                <!-- Copy Link -->
                <button
                    type="button"
                    @click="navigator.clipboard.writeText('{{ url('/posts/'.$post->id.'/comments') }}'); $wire.sharePost('link')"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded hover:bg-slate-700 transition text-left"
                >
                    <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M16 1H4c-1.1 0-2 .9-2 2v14h2V3h12V1zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2zm0 16H8V7h11v14z"/></svg>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-semibold text-white">Copy Link</div>
                        <div class="text-xs text-gray-400">Share anywhere</div>
                    </div>
                </button>

                <!-- Facebook -->
                <a
                    href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url('/posts/'.$post->id.'/comments')) }}"
                    target="_blank"
                    @click="$wire.sharePost('facebook')"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded hover:bg-slate-700 transition text-left"
                >
                    <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center">
                        <span class="text-white text-sm font-bold">f</span>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-semibold text-white">Facebook</div>
                        <div class="text-xs text-gray-400">Share on Facebook</div>
                    </div>
                </a>

                <!-- Twitter/X -->
                <a
                    href="https://twitter.com/intent/tweet?url={{ urlencode(url('/posts/'.$post->id.'/comments')) }}&text={{ urlencode(Str::limit($post->content, 100)) }}"
                    target="_blank"
                    @click="$wire.sharePost('twitter')"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded hover:bg-slate-700 transition text-left"
                >
                    <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center border border-gray-600">
                        <span class="text-white text-sm font-bold">𝕏</span>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-semibold text-white">Twitter / X</div>
                        <div class="text-xs text-gray-400">Post on Twitter</div>
                    </div>
                </a>

                <!-- LinkedIn -->
                <a
                    href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(url('/posts/'.$post->id.'/comments')) }}"
                    target="_blank"
                    @click="$wire.sharePost('linkedin')"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded hover:bg-slate-700 transition text-left"
                >
                    <div class="w-8 h-8 bg-blue-700 rounded-lg flex items-center justify-center">
                        <span class="text-white text-sm font-bold">in</span>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-semibold text-white">LinkedIn</div>
                        <div class="text-xs text-gray-400">Share professionally</div>
                    </div>
                </a>

                <!-- WhatsApp -->
                <a
                    href="https://wa.me/?text={{ urlencode(Str::limit($post->content, 100) . ' - ' . url('/posts/'.$post->id.'/comments')) }}"
                    target="_blank"
                    @click="$wire.sharePost('whatsapp')"
                    class="w-full flex items-center gap-3 px-3 py-2 rounded hover:bg-slate-700 transition text-left"
                >
                    <div class="w-8 h-8 bg-green-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-semibold text-white">WhatsApp</div>
                        <div class="text-xs text-gray-400">Send to contacts</div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Share Success Toast -->
    @if($shareMessage)
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => show = false, 3000); $wire.on('clearShareMessage', () => { setTimeout(() => $wire.set('shareMessage', ''), 3000) })"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-4"
            class="fixed bottom-8 right-8 z-50 bg-gradient-to-r from-green-600 to-emerald-600 text-white px-6 py-3 rounded-lg shadow-2xl flex items-center gap-3 border border-green-400/30"
        >
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
            <span class="font-semibold">{{ $shareMessage }}</span>
        </div>
    @endif
    @if($showComments)
        <div class="bg-slate-900/50 border-t border-blue-500/20 p-6 space-y-4">
            <h4 class="font-bold text-white mb-4">Comments ({{ $commentCount }})</h4>
            @if(in_array($post->interaction_type, ['comment', 'like_comment', 'all']))
                <form wire:submit.prevent="addComment" class="space-y-2 mb-4">
                    <textarea wire:model.defer="newComment" rows="3" class="w-full px-4 py-3 rounded-lg bg-slate-800 text-white placeholder-gray-500 border border-blue-700/30 focus:border-blue-700 focus:outline-none transition" placeholder="Add a comment..."></textarea>
                    @error('newComment') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-black text-white rounded-lg font-semibold hover:shadow-lg hover:shadow-blue-500/40 transition">Post Comment</button>
                    </div>
                </form>
            @endif
            @forelse($comments as $comment)
                @livewire('post.comment-thread', ['comment' => $comment], key($comment->id))
            @empty
                <p class="text-gray-400 text-center py-4">No comments yet. Be the first!</p>
            @endforelse
            @if($commentCount > 5)
                <div class="text-center">
                    <a href="{{ route('posts.comments', $post->id) }}" class="inline-block px-4 py-2 rounded-lg bg-slate-800 text-white hover:bg-slate-700 transition text-sm font-semibold">See all comments</a>
                </div>
            @endif
        </div>
    @endif

    <!-- Delete Post Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4" wire:key="delete-post-modal">
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-red-700/30 max-w-md w-full p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-white">Delete Post</h2>
                    <button type="button" wire:click="closeDeleteModal" class="text-gray-400 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <p class="text-gray-200 mb-6">Move this post to expired posts? You can restore it later from Expired Posts.</p>
                <div class="flex gap-3">
                    <button type="button" wire:click="closeDeleteModal" class="flex-1 px-4 py-2 bg-slate-700 text-white rounded-lg hover:bg-slate-600 transition font-medium">Cancel</button>
                    <button type="button" wire:click="deletePost" wire:loading.attr="disabled" class="flex-1 px-4 py-2 bg-gradient-to-r from-red-600 to-pink-600 text-white rounded-lg hover:shadow-lg hover:shadow-red-500/40 transition font-semibold">Yes, delete</button>
                </div>
            </div>
        </div>
    @endif
</div>
