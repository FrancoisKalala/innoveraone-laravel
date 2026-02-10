<div class="overflow-hidden transition border shadow-2xl bg-gradient-to-br from-slate-800/50 to-slate-900/50 rounded-2xl border-blue-700/20 hover:border-blue-700/40 backdrop-blur-xl" wire:key="post-card-{{ $post->id }}">
    <style>
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }
        .emoji-bounce:hover {
            animation: bounce 0.4s;
        }
        .animate-fade-in {
            animation: fadeIn 0.5s;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
    <div class="p-6 border-b border-blue-700/10">
        <div class="relative flex items-start justify-between mb-4">
            <div class="flex items-center flex-1 gap-4">
                <!-- Avatar with Explore Design Style -->
                <a href="{{ route('user.profile', $user->id) }}" class="flex items-center justify-center transition rounded-full shadow-lg w-14 h-14 bg-gradient-to-br from-blue-700 to-black hover:scale-105">
                    <span class="text-3xl font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                </a>
                <div class="flex-1">
                    <h3 class="text-lg font-bold text-white transition group-hover:text-blue-400">{{ $user->name }}</h3>
                    <p class="text-sm text-gray-400">{{ '@' . ($user->username ?? strtolower(str_replace(' ', '', $user->name))) }} • {{ $post->created_at->diffForHumans() }}</p>
                    @if($post->user_id !== auth()->id())
                        @if($isFollowing)
                            <button wire:click="toggleFollow" class="px-3 py-1 mt-2 text-xs font-semibold text-purple-400 transition bg-transparent border border-purple-400 rounded-full hover:bg-purple-700 hover:text-white">
                                Unfollow
                            </button>
                        @else
                            <button wire:click="toggleFollow" class="px-3 py-1 mt-2 text-xs font-semibold text-white bg-blue-600 rounded-full shadow hover:bg-blue-700">
                                Follow
                            </button>
                        @endif
                    @endif
                </div>
            </div>
            <div class="relative z-10 flex gap-2">
                @if($post->user_id === auth()->id())
                    <button
                        type="button"
                        wire:click.stop="openEditModal"
                        wire:loading.attr="disabled"
                        class="p-2 transition rounded cursor-pointer hover:bg-blue-500/20"
                        title="Edit post"
                        aria-label="Edit post"
                    >
                        <svg class="w-5 h-5 text-blue-400 pointer-events-none hover:text-blue-300" fill="currentColor" viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04c.39-.39.39-1.02 0-1.41l-2.34-2.34c-.39-.39-1.02-.39-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
                    </button>
                    <button type="button" wire:click.stop="openDeleteModal" wire:loading.attr="disabled" class="p-2 transition rounded cursor-pointer hover:bg-red-500/20" title="Delete post">
                        <svg class="w-5 h-5 text-red-400 pointer-events-none hover:text-red-300" fill="currentColor" viewBox="0 0 24 24"><path d="M6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM19 4h-3.5l-1-1h-9l-1 1H5v2h14V4z"/></svg>
                    </button>
                @endif
                <!-- Forward Dropdown -->
                <div class="relative" x-data="{ open: @entangle('showForwardOptions') }">
                    <button
                        type="button"
                        @click="open = !open"
                        class="p-2 transition rounded cursor-pointer hover:bg-green-500/20"
                        title="Forward post"
                    >
                        <svg class="w-5 h-5 text-green-400 pointer-events-none hover:text-green-300" fill="currentColor" viewBox="0 0 24 24">
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
                        class="absolute right-0 z-50 p-3 mt-2 space-y-2 border rounded-lg shadow-xl top-full w-80 bg-slate-800 border-green-700/30"
                        style="display: none;"
                    >
                        <div class="px-2 mb-2 text-xs font-semibold text-gray-400">Forward to contacts</div>
                        <div class="space-y-1 overflow-y-auto max-h-96" style="scrollbar-width: thin; scrollbar-color: rgba(168, 85, 247, 0.5) transparent;">
                            @php
                                $contacts = auth()->user()->contacts()->wherePivot('is_deleted', false)->get();
                            @endphp
                            @forelse($contacts as $contact)
                                <button
                                    type="button"
                                    @click="open = false"
                                    wire:click.prevent="forwardToContact({{ $contact->id }})"
                                    class="flex items-center w-full gap-3 px-3 py-2 text-left transition rounded hover:bg-slate-700"
                                >
                                    <div class="flex items-center justify-center w-10 h-10 rounded-full bg-gradient-to-br from-blue-700 to-black ring-2 ring-green-500/30">
                                        <span class="text-sm font-bold text-white">{{ substr($contact->name, 0, 1) }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-sm font-semibold text-white truncate">{{ $contact->name }}</div>
                                        <div class="text-xs text-gray-400 truncate">@{{ $contact->username ?? strtolower(str_replace(' ', '', $contact->name)) }}</div>
                                    </div>
                                </button>
                            @empty
                                <div class="py-4 text-sm text-center text-gray-400">No contacts available</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <div class="p-6 space-y-4">
        @if($post->album)
            <a href="{{ route('album.posts', $post->album->id) }}" class="inline-block px-3 py-1 text-xs font-semibold text-white transition rounded-full bg-gradient-to-r from-blue-500 to-cyan-500 hover:bg-blue-700/80" title="View posts in album">
                📁 {{ $post->album->title }}
            </a>
        @endif
        <p class="text-base leading-relaxed text-gray-100">
            {!!
                preg_replace(
                    [
                        '/#([\p{L}0-9_]+)/u',
                        '/@([\p{L}0-9_]+)/u'
                    ],
                    [
                        '<a href="' . url('/search?tag=$1') . '" class="text-blue-400 hover:underline">#$1</a>',
                        '<a href="' . url('/profile/$1') . '" class="text-blue-400 hover:underline">@$1</a>'
                    ],
                    Str::limit($post->content, 300)
                )
            !!}
        </p>

        @if($files->count() > 0)
            <div x-data="() => ({ fileIndex: 0 })" class="mt-4">
                @php $fileList = $files->values(); @endphp
                <div class="relative flex items-center justify-center w-full">
                    <!-- File Display -->
                    @foreach($fileList as $idx => $file)
                        <div x-show="fileIndex === {{ $idx }}" x-cloak class="w-full transition-all duration-500 ease-in-out transform" :class="fileIndex === {{ $idx }} ? 'scale-100 opacity-100' : 'scale-95 opacity-0'">
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
                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="flex items-center gap-3 p-3 transition border rounded-lg bg-gradient-to-r from-red-500/10 to-pink-500/10 border-red-500/20 hover:border-red-500/40 ring-1 ring-red-500/20">
                                    <svg class="flex-shrink-0 w-6 h-6 text-red-400" fill="currentColor" viewBox="0 0 24 24"><path d="M14,2H6A2,2 0 0,0 4,4V20A2,2 0 0,0 6,22H18A2,2 0 0,0 20,20V8L14,2M18,20H6V4H13V9H18V20M12,19L8,15H10.5V12H13.5V15H16L12,19Z"/></svg>
                                    <div>
                                        <p class="text-sm font-semibold text-red-300">{{ basename($file->file_path) }}</p>
                                        <p class="text-xs text-gray-400">PDF Document • Click to view</p>
                                    </div>
                                    <svg class="w-4 h-4 ml-auto text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            @else
                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="block text-blue-400 underline">Open File</a>
                            @endif
                        </div>
                    @endforeach
                    <!-- Navigation Arrows -->
                    @if($fileList->count() > 1)
                        <!-- Left Arrow -->
                        <button @click="fileIndex = Math.max(0, fileIndex - 1)" :disabled="fileIndex === 0"
                            class="absolute left-0 z-20 p-5 text-white transition-all duration-300 ease-in-out -translate-y-1/2 rounded-full shadow-lg top-1/2 bg-gradient-to-br from-blue-600/30 to-blue-900/30 md:p-8 hover:scale-110 hover:from-blue-500/50 hover:to-blue-700/50 disabled:opacity-20 disabled:cursor-not-allowed animate-fade-in"
                            style="min-width: 56px; min-height: 56px; opacity: 0.5;"
                            aria-label="Previous file">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <!-- Right Arrow -->
                        <button @click="fileIndex = Math.min({{ $fileList->count() - 1 }}, fileIndex + 1)" :disabled="fileIndex === {{ $fileList->count() - 1 }}"
                            class="absolute right-0 z-20 p-5 text-white transition-all duration-300 ease-in-out -translate-y-1/2 rounded-full shadow-lg top-1/2 bg-gradient-to-br from-blue-600/30 to-blue-900/30 md:p-8 hover:scale-110 hover:from-blue-500/50 hover:to-blue-700/50 disabled:opacity-20 disabled:cursor-not-allowed animate-fade-in"
                            style="min-width: 56px; min-height: 56px; opacity: 0.5;"
                            aria-label="Next file">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    @endif
                </div>
                <!-- Dots Indicator: always below the media container -->
                @if($fileList->count() > 1)
                    <div class="flex justify-center w-full gap-2 mt-5 animate-fade-in">
                        <template x-for="i in {{ $fileList->count() }}" :key="i">
                            <span :class="fileIndex === i - 1 ? 'bg-gradient-to-br from-blue-400 to-cyan-400 scale-125 shadow-lg ring-2 ring-blue-300' : 'bg-gray-400 opacity-60'"
                                class="inline-block w-3 h-3 transition-all duration-300 border rounded-full cursor-pointer border-white/30 hover:scale-110 hover:shadow-xl"
                                @click="fileIndex = i - 1"
                                :style="fileIndex === i - 1 ? 'animation: bounceDot 0.5s;' : ''"
                            ></span>
                        </template>
                    </div>
                @endif
                <style>
                @keyframes bounceDot {
                    0%, 100% { transform: scale(1); }
                    50% { transform: scale(1.4); }
                }
                .animate-fade-in { animation: fadeIn 0.5s; }
                @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
                </style>
            </div>
        @endif
        <div class="flex gap-6 pt-4 text-sm text-gray-400 border-t border-blue-500/20">
            <div class="flex items-center gap-2"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg><span>{{ $likeCount }}</span></div>
            <div class="flex items-center gap-2"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg><span>{{ $commentCount }}</span></div>
            @if($shareCount > 0)
                <div class="flex items-center gap-2"><svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.15c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.44 9.31 6.77 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.77 0 1.44-.3 1.96-.77l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/></svg><span>{{ $shareCount }}</span></div>
            @endif
            <div class="flex items-center gap-2 ml-auto text-xs">⏱️ {{ $post->expiration_hours }}h expiration</div>
        </div>
    </div>
        @if($post->interaction_type !== 'none')
    <div class="flex gap-3 px-6 py-4 border-t bg-slate-900/50 border-blue-500/20">
        @if(in_array($post->interaction_type, ['like', 'like_comment', 'all']))
        @foreach(['👍','😂','😍','😮'] as $emoji)
            <button wire:click.prevent="reactEmoji('{{ $emoji }}')"
                class="flex items-center gap-1 px-2 py-1 text-2xl transition duration-200 ease-in-out rounded-full shadow-lg bg-slate-800 hover:scale-125 hover:bg-blue-700"
                style="will-change: transform;"
                title="React with {{ $emoji }}"
            >
                <span class="emoji-bounce">{{ $emoji }}</span>
                <span class="text-xs font-bold text-blue-400">{{ $reactions[$emoji] ?? 0 }}</span>
            </button>
        @endforeach
        @endif
        @if(in_array($post->interaction_type, ['comment', 'like_comment', 'all']))
        <button wire:click="toggleComments" class="flex items-center justify-center flex-1 gap-2 px-4 py-2 font-semibold text-gray-300 transition rounded-lg bg-slate-800 hover:bg-slate-700" title="Comment">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg>
        </button>
        @endif

        <!-- Share Button with Dropdown -->
        <div class="relative flex-1" x-data="{ open: @entangle('showShareOptions') }">
            <button
                @click="open = !open"
                class="flex items-center justify-center w-full gap-2 px-4 py-2 font-semibold text-gray-300 transition rounded-lg bg-slate-800 hover:bg-slate-700"
                title="Share"
            >
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.15c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.44 9.31 6.77 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.77 0 1.44-.3 1.96-.77l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/></svg>
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
                class="absolute left-0 z-50 w-64 p-3 mb-2 space-y-2 border rounded-lg shadow-xl bottom-full bg-slate-800 border-blue-700/30"
                style="display: none;"
            >
                <div class="px-2 mb-2 text-xs font-semibold text-gray-400">Share this post</div>

                <!-- Copy Link -->
                <button
                    type="button"
                    @click="navigator.clipboard.writeText('{{ url('/posts/'.$post->id.'/comments') }}'); $wire.sharePost('link')"
                    class="flex items-center w-full gap-3 px-3 py-2 text-left transition rounded hover:bg-slate-700"
                >
                    <div class="flex items-center justify-center w-8 h-8 bg-blue-600 rounded-lg">
                        <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M16 1H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg>
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
                    class="flex items-center w-full gap-3 px-3 py-2 text-left transition rounded hover:bg-slate-700"
                >
                    <div class="flex items-center justify-center w-8 h-8 bg-blue-500 rounded-lg">
                        <span class="text-sm font-bold text-white">f</span>
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
                    class="flex items-center w-full gap-3 px-3 py-2 text-left transition rounded hover:bg-slate-700"
                >
                    <div class="flex items-center justify-center w-8 h-8 bg-black border border-gray-600 rounded-lg">
                        <span class="text-sm font-bold text-white">𝕏</span>
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
                    class="flex items-center w-full gap-3 px-3 py-2 text-left transition rounded hover:bg-slate-700"
                >
                    <div class="flex items-center justify-center w-8 h-8 bg-blue-700 rounded-lg">
                        <span class="text-sm font-bold text-white">in</span>
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
                    class="flex items-center w-full gap-3 px-3 py-2 text-left transition rounded hover:bg-slate-700"
                >
                    <div class="flex items-center justify-center w-8 h-8 bg-green-500 rounded-lg">
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
            class="fixed z-50 flex items-center gap-3 px-6 py-3 text-white border rounded-lg shadow-2xl bottom-8 right-8 bg-gradient-to-r from-green-600 to-emerald-600 border-green-400/30"
        >
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
            <span class="font-semibold">{{ $shareMessage }}</span>
        </div>
    @endif
    @if($showComments)
            <div class="p-6 space-y-4 overflow-y-auto border-t bg-slate-900/50 border-blue-500/20" style="scrollbar-width: thin; scrollbar-color: rgba(168, 85, 247, 0.5) transparent;">
            <h4 class="mb-4 font-bold text-white">Comments ({{ $commentCount }})</h4>
            <div class="flex flex-wrap items-center gap-2 mb-4">
                <label class="text-xs text-gray-400">Sort/Filter:</label>
                <select wire:model="commentView" class="px-2 py-1 text-xs text-white border rounded bg-slate-800 border-blue-700/30">
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
                    <input wire:model="commentKeyword" type="text" placeholder="Search..." class="px-2 py-1 text-xs text-white border rounded bg-slate-800 border-blue-700/30" />
                @endif
            </div>
            @if(in_array($post->interaction_type, ['comment', 'like_comment', 'all']))
                <form wire:submit.prevent="addComment" class="mb-4 space-y-2">
                    <div class="relative">
                        <textarea id="newCommentContent" wire:model.defer="newComment" rows="3" class="w-full px-4 py-3 text-white placeholder-gray-500 transition border rounded-lg bg-slate-800 border-blue-700/30 focus:border-blue-700 focus:outline-none" placeholder="Add a comment..."></textarea>
                        <button type="button" class="absolute text-xl right-2 top-2" onclick="document.getElementById('emoji-picker-new-comment').classList.toggle('hidden')">😊</button>
                        <div id="emoji-picker-new-comment" class="absolute z-10 hidden p-2 mt-2 border rounded-lg bg-slate-800 border-blue-700/30" style="max-width: 250px; max-height: 180px; overflow-y: auto;">
                            <div class="flex flex-wrap gap-1">
                                @foreach(['😀','😁','😂','🤣','😃','😄','😅','😆','😉','😊','😋','😎','😍','😘','🥰','😗','😙','😚','🙂','🤗','🤩','🤔','🤨','😐','😑','😶','🙄','😏','😣','😥','😮','🤐','😯','😪','😫','🥱','😴','😌','😛','😜','😝','🤤','😒','😓','😔','😕','🙃','🤑','😲','☹️','🙁','😖','😞','😟','😤','😢','😭','😦','😧','😨','😩','🤯','😬','😰','😱','🥵','🥶','😳','🤪','😵','😡','😠','🤬','😷','🤒','🤕','🤢','🤮','🤧','😇','🥳','🥺','🤠','🤡','🤥','🤫','🤭','🧐','🤓','😈','👿','👹','👺','💀','👻','👽','🤖','💩','😺','😸','😹','😻','😼','😽','🙀','😿','😾'] as $emoji)
                                    <button type="button" class="p-1 text-xl rounded hover:bg-slate-700" onclick="document.getElementById('newCommentContent').value += '{{ $emoji }}'; document.getElementById('newCommentContent').dispatchEvent(new Event('input'))">{{ $emoji }}</button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @error('newComment') <span class="text-xs text-red-400">{{ $message }}</span> @enderror
                    <div class="flex justify-end">
                        <button type="submit" class="px-4 py-2 font-semibold text-white transition rounded-lg bg-gradient-to-r from-blue-600 to-black hover:shadow-lg hover:shadow-blue-500/40">Post Comment</button>
                    </div>
                </form>
            @endif
            @forelse($comments as $comment)
                @livewire('post.comment-thread', ['comment' => $comment], key($comment->id))
            @empty
                <p class="py-4 text-center text-gray-400">No comments yet. Be the first!</p>
            @endforelse
            @if($commentCount > 5)
                <div class="text-center">
                    <a href="{{ route('posts.comments', $post->id) }}" class="inline-block px-4 py-2 text-sm font-semibold text-white transition rounded-lg bg-slate-800 hover:bg-slate-700">See all comments</a>
                </div>
            @endif
        </div>
    @endif

    <!-- Delete Post Modal -->
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" wire:key="delete-post-modal">
            <div class="w-full max-w-md p-6 border bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border-red-700/30">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-white">Delete Post</h2>
                    <button type="button" wire:click="closeDeleteModal" class="text-gray-400 transition hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <p class="mb-6 text-gray-200">Move this post to expired posts? You can restore it later from Expired Posts.</p>
                <div class="flex gap-3">
                    <button type="button" wire:click="closeDeleteModal" class="flex-1 px-4 py-2 font-medium text-white transition rounded-lg bg-slate-700 hover:bg-slate-600">Cancel</button>
                    <button type="button" wire:click="deletePost" wire:loading.attr="disabled" class="flex-1 px-4 py-2 font-semibold text-white transition rounded-lg bg-gradient-to-r from-red-600 to-pink-600 hover:shadow-lg hover:shadow-red-500/40">Yes, delete</button>
                </div>
            </div>
        </div>
    @endif
</div>
