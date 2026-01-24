<div>
    @if($show)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" wire:key="post-modal">
        <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-blue-700/30 max-w-2xl w-full p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-white">{{ $mode === 'edit' ? 'Edit Post' : 'Create Post' }}</h2>
                <button type="button" wire:click="close" class="text-gray-400 transition hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit.prevent="save" class="space-y-4">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-300">Content</label>
                    <textarea wire:model.defer="content" rows="6" class="w-full px-4 py-3 text-white placeholder-gray-500 transition border rounded-lg bg-slate-700 border-blue-700/20 focus:border-blue-700 focus:outline-none" placeholder="Share something..."></textarea>
                    @error('content') <span class="mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-300">Album (optional)</label>
                    <div class="flex items-center gap-2">
                        <select wire:model.defer="albumId" class="flex-1 px-4 py-2 text-white transition border rounded-lg bg-slate-700 border-blue-700/20 focus:border-blue-700 focus:outline-none">
                            <option value="">📁 Select an album (optional)</option>
                            @foreach($albums as $album)
                                <option value="{{ $album->id }}">{{ $album->title }}</option>
                            @endforeach
                        </select>
                            <button type="button" class="p-2 text-white transition duration-150 rounded-full shadow bg-gradient-to-br from-blue-500 to-blue-700 hover:scale-110" wire:click="$set('showAlbumInput', true)">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="url(#plusGradient)"/>
                                    <path d="M12 8v8M8 12h8" stroke="white" stroke-width="2" stroke-linecap="round"/>
                                    <defs>
                                        <linearGradient id="plusGradient" x1="0" y1="0" x2="24" y2="24" gradientUnits="userSpaceOnUse">
                                            <stop stop-color="#3b82f6"/>
                                            <stop offset="1" stop-color="#1e40af"/>
                                        </linearGradient>
                                    </defs>
                                </svg>
                            </button>
                    </div>
                    @if($showAlbumInput)
                        <div class="flex gap-2 mt-2">
                            <input type="text" wire:model.defer="newAlbumName" class="flex-1 px-3 py-2 text-sm text-white border rounded bg-slate-700 border-blue-700/20 focus:border-blue-700 focus:outline-none" placeholder="Album name...">
                            <button type="button" class="px-3 py-2 text-xs text-white bg-green-600 rounded hover:bg-green-700" wire:click="createAlbum">Create</button>
                            <button type="button" class="px-3 py-2 text-xs text-white rounded bg-slate-600 hover:bg-slate-700" wire:click="$set('showAlbumInput', false)">Cancel</button>
                        </div>
                        @error('newAlbumName') <span class="block mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
                    @endif
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-300">Interaction type</label>
                        <select wire:model.defer="interactionType" class="w-full px-4 py-2 text-sm text-white transition border rounded-lg bg-slate-700 border-blue-700/20 focus:border-blue-700 focus:outline-none">
                            <option value="all">✨ Allow: All</option>
                            <option value="like">👍 Allow: Like</option>
                            <option value="comment">💬 Allow: Comment</option>
                            <option value="like_comment">👍💬 Allow: Like & Comment</option>
                            <option value="none">🔒 No Interactions</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-300">Expiration</label>
                        <select wire:model.defer="expirationHours" class="w-full px-4 py-2 text-sm text-white transition border rounded-lg bg-slate-700 border-blue-700/20 focus:border-blue-700 focus:outline-none">
                            <option value="5">⏱️ Expires in 5 hours</option>
                            <option value="10">⏱️ Expires in 10 hours</option>
                            <option value="24">⏱️ Expires in 24 hours</option>
                            <option value="72">⏱️ Expires in 3 days</option>
                            <option value="168">⏱️ Expires in 1 week</option>
                            <option value="720">⏱️ Expires in 30 days</option>
                        </select>
                        @error('expirationHours') <span class="mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- File Upload Section -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-300">Media Files</label>
                    <div x-data="{
                        isDragging: false,
                        handleDrop(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            this.isDragging = false;
                            const files = e.dataTransfer.files;
                            if (files.length) {
                                this.$refs.fileInput.files = files;
                                this.$refs.fileInput.dispatchEvent(new Event('change', { bubbles: true }));
                            }
                        }
                    }"
                        :class="isDragging ? 'border-blue-500 bg-blue-900/10' : ''"
                        class="p-4 text-center transition border-2 border-dashed rounded-lg cursor-pointer border-blue-700/30 hover:border-blue-700/50 bg-slate-700/30"
                        @dragover.prevent.stop="isDragging = true"
                        @dragleave.prevent.stop="isDragging = false"
                        @drop.prevent.stop="handleDrop($event)">
                        <input type="file" wire:model="newFiles" multiple accept="image/*,video/*,audio/*,.pdf,.glb,.gltf,.obj,.svg" class="hidden" id="file-upload-modal" x-ref="fileInput">
                        <label for="file-upload-modal" class="cursor-pointer">
                            <svg class="w-8 h-8 mx-auto mb-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <p class="text-sm text-gray-300">Click or drag files here (max 10 files, 50 MB each)</p>
                        </label>
                        <!-- Alpine.js handles drag/drop events above -->
                        @if(count($newFiles) > 0)
                            <div class="grid grid-cols-2 gap-3 mt-4">
                                @foreach($newFiles as $file)
                                    @php
                                        $mimeType = $file->getMimeType();
                                        $fileName = $file->getClientOriginalName();
                                        $isImage = str_starts_with($mimeType, 'image/');
                                        $isVideo = str_starts_with($mimeType, 'video/');
                                        $isAudio = str_starts_with($mimeType, 'audio/');
                                        $isPdf = str_contains($mimeType, 'pdf');
                                        $isModel = str_ends_with($fileName, '.glb') || str_ends_with($fileName, '.gltf') || str_ends_with($fileName, '.obj');
                                        $isSvg = str_ends_with($fileName, '.svg');
                                    @endphp
                                    <div class="relative p-3 border rounded-lg bg-slate-700/50 border-blue-700/20">
                                        @if($isImage)
                                            <img src="{{ $file->temporaryUrl() }}" class="object-cover w-full h-24 mb-2 rounded" alt="Media" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                                            <div class="flex items-center justify-center hidden w-full h-24 mb-2 rounded bg-slate-600">
                                                <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                                            </div>
                                            <div class="text-xs text-center text-blue-400">Image</div>
                                        @elseif($isVideo)
                                            <div class="flex items-center justify-center w-full h-24 mb-2 rounded bg-gradient-to-br from-purple-600 to-purple-900">
                                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M18 3v2h-2V3H8v2H6V3H4v18h16V3h-2zm-2 16H8v-2h8v2z"/><path d="M6 7h12v8H6z" opacity="0.3"/></svg>
                                            </div>
                                            <div class="text-xs text-center text-purple-400">Video</div>
                                        @elseif($isAudio)
                                            <div class="flex items-center justify-center w-full h-24 mb-2 rounded bg-gradient-to-br from-blue-600 to-blue-900">
                                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v9.28c-.47-.46-1.12-.75-1.84-.75-1.66 0-3 1.34-3 3s1.34 3 3 3c1.66 0 3-1.34 3-3V7h7V3h-8z"/></svg>
                                            </div>
                                            <div class="text-xs text-center text-blue-400">Audio</div>
                                        @elseif($isPdf)
                                            <div class="flex items-center justify-center w-full h-24 mb-2 rounded bg-gradient-to-br from-red-600 to-red-900">
                                                <div class="text-center">
                                                    <div class="text-lg font-bold text-white">PDF</div>
                                                </div>
                                            </div>
                                            <div class="text-xs text-center text-red-400">PDF</div>
                                        @elseif($isModel)
                                            <div class="flex items-center justify-center w-full h-24 mb-2 rounded bg-gradient-to-br from-cyan-600 to-cyan-900">
                                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M6 2c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6H6zm7 7V3.5L17.5 9H13z" opacity="0.3"/><path d="M9 11h6v2H9zm0 4h6v2H9z"/></svg>
                                            </div>
                                            <div class="text-xs text-center text-cyan-400">3D Model</div>
                                        @elseif($isSvg)
                                            <div class="flex items-center justify-center w-full h-24 mb-2 rounded bg-gradient-to-br from-yellow-500 to-yellow-900">
                                                <svg class="w-8 h-8 text-yellow-400" fill="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/></svg>
                                            </div>
                                            <div class="text-xs text-center text-yellow-400">SVG</div>
                                        @else
                                            <div class="flex items-center justify-center w-full h-24 mb-2 rounded bg-slate-600">
                                                <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/></svg>
                                            </div>
                                            <div class="text-xs text-center text-red-400">File</div>
                                        @endif
                                        <div class="mt-1 text-xs text-gray-400">{{ $fileName }}</div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    @error('newFiles.*') <span class="block mt-1 text-xs text-red-400">{{ $message }}</span> @enderror

                    <!-- Upload Progress Bar -->
                    <div wire:loading wire:target="newFiles" class="mt-2 space-y-2">
                        <div class="flex items-center justify-between text-xs text-blue-400">
                            <span>📤 Uploading & Compressing Files...</span>
                            <span class="animate-pulse">Processing...</span>
                        </div>
                        <div class="w-full h-2 overflow-hidden border rounded-full bg-slate-700 border-blue-700/20">
                            <div class="h-full bg-gradient-to-r from-blue-600 to-cyan-500 animate-pulse" style="width: 75%"></div>
                        </div>
                    </div>
                </div>

                <!-- Existing Files (Edit Mode) -->
                @if($mode === 'edit' && count($existingFiles) > 0)
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-300">Current Media</label>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($existingFiles as $file)
                                <div class="relative p-3 border rounded-lg bg-slate-700/50 border-blue-700/20">
                                    @php
                                        $isImage = str_contains($file['file_type'], 'image') || str_ends_with($file['file_path'], '.webp');
                                        $isVideo = str_contains($file['file_type'], 'video');
                                        $isAudio = str_contains($file['file_type'], 'audio');
                                        $isPdf = str_contains($file['file_type'], 'pdf');
                                        $isModel = str_ends_with($file['file_path'], '.glb') || str_ends_with($file['file_path'], '.gltf') || str_ends_with($file['file_path'], '.obj');
                                    @endphp
                                    @if($isImage)
                                        <img src="{{ asset('storage/' . $file['file_path']) }}" class="object-cover w-full h-24 mb-2 rounded" alt="Media" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                                        <div class="flex items-center justify-center hidden w-full h-24 mb-2 rounded bg-slate-600">
                                            <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                                        </div>
                                    @elseif($isVideo)
                                        <div class="flex items-center justify-center w-full h-24 mb-2 rounded bg-gradient-to-br from-purple-600 to-purple-900">
                                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M18 3v2h-2V3H8v2H6V3H4v18h16V3h-2zm-2 16H8v-2h8v2z"/><path d="M6 7h12v8H6z" opacity="0.3"/></svg>
                                        </div>
                                    @elseif($isAudio)
                                        <div class="flex items-center justify-center w-full h-24 mb-2 rounded bg-gradient-to-br from-blue-600 to-blue-900">
                                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v9.28c-.47-.46-1.12-.75-1.84-.75-1.66 0-3 1.34-3 3s1.34 3 3 3c1.66 0 3-1.34 3-3V7h7V3h-8z"/></svg>
                                        </div>
                                    @elseif($isPdf)
                                        <div class="flex items-center justify-center w-full h-24 mb-2 rounded bg-gradient-to-br from-red-600 to-red-900">
                                            <div class="text-center">
                                                <div class="text-lg font-bold text-white">PDF</div>
                                            </div>
                                        </div>
                                    @elseif($isModel)
                                        <div class="flex items-center justify-center w-full h-24 mb-2 rounded bg-gradient-to-br from-cyan-600 to-cyan-900">
                                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M6 2c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6H6zm7 7V3.5L17.5 9H13z" opacity="0.3"/><path d="M9 11h6v2H9zm0 4h6v2H9z"/></svg>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-center w-full h-24 mb-2 rounded bg-slate-600">
                                            <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/></svg>
                                        </div>
                                    @endif
                                    <p class="mb-2 text-xs text-gray-300 truncate">{{ basename($file['file_path']) }}</p>
                                    <div class="flex gap-1">
                                        <button type="button" wire:click="reviewFile('existing', {{ $loop->index }})" class="flex-1 px-2 py-1 text-xs text-white transition bg-blue-600 rounded hover:bg-blue-700">👁️ Review</button>
                                        <button type="button" wire:click="removeExistingFile({{ $file['id'] }})" class="px-2 py-1 text-white transition bg-red-600 rounded hover:bg-red-700">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- New Files Preview -->
                @if(count($newFiles) > 0)
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-300">New Files</label>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($newFiles as $index => $file)
                                <div class="relative p-3 border rounded-lg bg-slate-700/50 border-blue-700/20">
                                    @php
                                        $mimeType = $file->getMimeType();
                                        $isImage = str_starts_with($mimeType, 'image/');
                                        $isVideo = str_starts_with($mimeType, 'video/');
                                        $isAudio = str_starts_with($mimeType, 'audio/');
                                        $isPdf = str_contains($mimeType, 'pdf');
                                        $isModel = str_ends_with($file->getClientOriginalName(), '.glb') || str_ends_with($file->getClientOriginalName(), '.gltf') || str_ends_with($file->getClientOriginalName(), '.obj');
                                    @endphp
                                    @if($isImage)
                                        <img src="{{ $file->temporaryUrl() }}" class="object-cover w-full h-24 mb-2 rounded" alt="Media" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                                        <div class="flex items-center justify-center hidden w-full h-24 mb-2 rounded bg-slate-600">
                                            <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                                        </div>
                                    @elseif($isVideo)
                                        <div class="flex items-center justify-center w-full h-24 mb-2 rounded bg-gradient-to-br from-purple-600 to-purple-900">
                                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M18 3v2h-2V3H8v2H6V3H4v18h16V3h-2zm-2 16H8v-2h8v2z"/><path d="M6 7h12v8H6z" opacity="0.3"/></svg>
                                        </div>
                                    @elseif($isAudio)
                                        <div class="flex items-center justify-center w-full h-24 mb-2 rounded bg-gradient-to-br from-blue-600 to-blue-900">
                                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v9.28c-.47-.46-1.12-.75-1.84-.75-1.66 0-3 1.34-3 3s1.34 3 3 3c1.66 0 3-1.34 3-3V7h7V3h-8z"/></svg>
                                        </div>
                                    @elseif($isPdf)
                                        <div class="flex items-center justify-center w-full h-24 mb-2 rounded bg-gradient-to-br from-red-600 to-red-900">
                                            <div class="text-center">
                                                <div class="text-lg font-bold text-white">PDF</div>
                                            </div>
                                        </div>
                                    @elseif($isModel)
                                        <div class="flex items-center justify-center w-full h-24 mb-2 rounded bg-gradient-to-br from-cyan-600 to-cyan-900">
                                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M6 2c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6H6zm7 7V3.5L17.5 9H13z" opacity="0.3"/><path d="M9 11h6v2H9zm0 4h6v2H9z"/></svg>
                                        </div>
                                    @else
                                        <div class="flex items-center justify-center w-full h-24 mb-2 rounded bg-slate-600">
                                            <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/></svg>
                                        </div>
                                    @endif
                                    <p class="mb-2 text-xs text-gray-300 truncate">{{ $file->getClientOriginalName() }}</p>
                                    <div class="flex gap-1">
                                        <button type="button" wire:click="reviewFile('new', {{ $loop->index }})" class="flex-1 px-2 py-1 text-xs text-white transition bg-blue-600 rounded hover:bg-blue-700">👁️ Review</button>
                                        <button type="button" wire:click="removeNewFile({{ $loop->index }})" class="px-2 py-1 text-white transition bg-red-600 rounded hover:bg-red-700">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="flex gap-3 pt-4">
                    <button type="button" wire:click="close" class="flex-1 px-4 py-2 font-medium text-white transition rounded-lg bg-slate-700 hover:bg-slate-600">Cancel</button>
                    <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed" class="flex-1 px-4 py-2 font-semibold text-white transition rounded-lg bg-gradient-to-r from-blue-600 to-black hover:shadow-lg hover:shadow-blue-500/50 disabled:hover:shadow-none">
                        <span wire:loading.remove wire:target="save">{{ $mode === 'edit' ? 'Update Post' : 'Create Post' }}</span>
                        <span wire:loading wire:target="save" class="flex items-center justify-center gap-2">
                            <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ $mode === 'edit' ? 'Updating...' : 'Creating...' }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>

<!-- File Review Modal -->
@if($showReviewModal)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
                    <div x-data="fileDrop" x-init="init()" :class="isDragging ? 'border-blue-500 bg-blue-900/10' : ''"
                        class="p-4 text-center transition border-2 border-dashed rounded-lg cursor-pointer border-blue-700/30 hover:border-blue-700/50 bg-slate-700/30"
                        @dragover.prevent.stop="isDragging = true"
                        @dragleave.prevent.stop="isDragging = false"
                        @drop.prevent.stop="handleDrop($event)">
                        <input type="file" wire:model="newFiles" multiple accept="image/*,video/*,audio/*,.pdf,.glb,.gltf,.obj,.svg" style="display:none;" id="file-upload-modal" x-ref="fileInput">
                        <label for="file-upload-modal" class="cursor-pointer">
                            <svg class="w-8 h-8 mx-auto mb-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <p class="text-sm text-gray-300">Click or drag files here (max 10 files, 50 MB each)</p>
                        </label>
                        <script>
                        document.addEventListener('alpine:init', () => {
                            Alpine.data('fileDrop', () => ({
                                isDragging: false,
                                handleDrop(e) {
                                    e.preventDefault();
                                    e.stopPropagation();
                                    this.isDragging = false;
                                    const files = e.dataTransfer.files;
                                    if (files.length) {
                                        try {
                                            this.$refs.fileInput.files = files;
                                            this.$refs.fileInput.dispatchEvent(new Event('change', { bubbles: true }));
                                        } catch (err) {
                                            this.$refs.fileInput.click();
                                        }
                                    }
                                },
                                init() {}
                            }));
                        });
                        </script>
                            </div>
                </div>

                <div class="flex gap-3 pt-4">
                    @if($reviewFileType === 'existing' && isset($existingFiles[$reviewFileIndex]))
                        <button type="button" wire:click="removeExistingFile({{ $existingFiles[$reviewFileIndex]['id'] }}); closeReviewModal();" class="flex-1 px-4 py-2 font-medium text-white transition bg-red-600 rounded-lg hover:bg-red-700">Delete</button>
                    @elseif($reviewFileType === 'new' && isset($newFiles[$reviewFileIndex]))
                        <button type="button" wire:click="removeNewFile({{ $reviewFileIndex }}); closeReviewModal();" class="flex-1 px-4 py-2 font-medium text-white transition bg-red-600 rounded-lg hover:bg-red-700">Delete</button>
                    @endif
                    <button type="button" wire:click="closeReviewModal" class="flex-1 px-4 py-2 font-semibold text-white transition rounded-lg bg-gradient-to-r from-blue-600 to-black hover:shadow-lg hover:shadow-blue-500/50">Close</button>
                </div>
            </div>
        </div>
    </div>
@endif
