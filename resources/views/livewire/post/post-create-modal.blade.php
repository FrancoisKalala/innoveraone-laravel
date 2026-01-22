<div>
    @if($show)
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4" wire:key="post-modal">
        <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-blue-700/30 max-w-2xl w-full p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-white">{{ $mode === 'edit' ? 'Edit Post' : 'Create Post' }}</h2>
                <button type="button" wire:click="close" class="text-gray-400 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit.prevent="save" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Content</label>
                    <textarea wire:model.defer="content" rows="6" class="w-full px-4 py-3 rounded-lg bg-slate-700 text-white placeholder-gray-500 border border-blue-700/20 focus:border-blue-700 focus:outline-none transition" placeholder="Share something..."></textarea>
                    @error('content') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Album (optional)</label>
                    <select wire:model.defer="albumId" class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white border border-blue-700/20 focus:border-blue-700 focus:outline-none transition">
                        <option value="">📁 Select an album (optional)</option>
                        @foreach($albums as $album)
                            <option value="{{ $album->id }}">{{ $album->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Interaction type</label>
                        <select wire:model.defer="interactionType" class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white border border-blue-700/20 focus:border-blue-700 focus:outline-none transition text-sm">
                            <option value="all">✨ Allow: All</option>
                            <option value="like">👍 Allow: Like</option>
                            <option value="comment">💬 Allow: Comment</option>
                            <option value="like_comment">👍💬 Allow: Like & Comment</option>
                            <option value="none">🔒 No Interactions</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Expiration</label>
                        <select wire:model.defer="expirationHours" class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white border border-blue-700/20 focus:border-blue-700 focus:outline-none transition text-sm">
                            <option value="5">⏱️ Expires in 5 hours</option>
                            <option value="10">⏱️ Expires in 10 hours</option>
                            <option value="24">⏱️ Expires in 24 hours</option>
                            <option value="72">⏱️ Expires in 3 days</option>
                            <option value="168">⏱️ Expires in 1 week</option>
                            <option value="720">⏱️ Expires in 30 days</option>
                        </select>
                        @error('expirationHours') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- File Upload Section -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Media Files</label>
                    <div class="border-2 border-dashed border-blue-700/30 rounded-lg p-4 text-center hover:border-blue-700/50 transition cursor-pointer bg-slate-700/30">
                        <input type="file" wire:model="newFiles" multiple accept="image/*,video/*,audio/*,.pdf,.glb,.gltf,.obj,.svg" class="hidden" id="file-upload-modal">
                        <label for="file-upload-modal" class="cursor-pointer">
                            <svg class="w-8 h-8 mx-auto mb-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            <p class="text-gray-300 text-sm">Click to upload media (max 10 files, 50 MB each)</p>
                        </label>
                        @if(count($newFiles) > 0)<div class="mt-2 text-green-400 text-sm">{{ count($newFiles) }} file(s) selected</div>@endif
                    </div>
                    @error('newFiles.*') <span class="text-red-400 text-xs mt-1 block">{{ $message }}</span> @enderror

                    <!-- Upload Progress Bar -->
                    <div wire:loading wire:target="newFiles" class="space-y-2 mt-2">
                        <div class="flex items-center justify-between text-xs text-blue-400">
                            <span>📤 Uploading & Compressing Files...</span>
                            <span class="animate-pulse">Processing...</span>
                        </div>
                        <div class="w-full bg-slate-700 rounded-full h-2 overflow-hidden border border-blue-700/20">
                            <div class="h-full bg-gradient-to-r from-blue-600 to-cyan-500 animate-pulse" style="width: 75%"></div>
                        </div>
                    </div>
                </div>

                <!-- Existing Files (Edit Mode) -->
                @if($mode === 'edit' && count($existingFiles) > 0)
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Current Media</label>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($existingFiles as $file)
                                <div class="relative bg-slate-700/50 rounded-lg p-3 border border-blue-700/20">
                                    @php
                                        $isImage = str_contains($file['file_type'], 'image') || str_ends_with($file['file_path'], '.webp');
                                        $isVideo = str_contains($file['file_type'], 'video');
                                        $isAudio = str_contains($file['file_type'], 'audio');
                                        $isPdf = str_contains($file['file_type'], 'pdf');
                                        $isModel = str_ends_with($file['file_path'], '.glb') || str_ends_with($file['file_path'], '.gltf') || str_ends_with($file['file_path'], '.obj');
                                    @endphp
                                    @if($isImage)
                                        <img src="{{ asset('storage/' . $file['file_path']) }}" class="w-full h-24 object-cover rounded mb-2" alt="Media" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                                        <div class="w-full h-24 bg-slate-600 rounded mb-2 flex items-center justify-center hidden">
                                            <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                                        </div>
                                    @elseif($isVideo)
                                        <div class="w-full h-24 bg-gradient-to-br from-purple-600 to-purple-900 rounded mb-2 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M18 3v2h-2V3H8v2H6V3H4v18h16V3h-2zm-2 16H8v-2h8v2z"/><path d="M6 7h12v8H6z" opacity="0.3"/></svg>
                                        </div>
                                    @elseif($isAudio)
                                        <div class="w-full h-24 bg-gradient-to-br from-blue-600 to-blue-900 rounded mb-2 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v9.28c-.47-.46-1.12-.75-1.84-.75-1.66 0-3 1.34-3 3s1.34 3 3 3c1.66 0 3-1.34 3-3V7h7V3h-8z"/></svg>
                                        </div>
                                    @elseif($isPdf)
                                        <div class="w-full h-24 bg-gradient-to-br from-red-600 to-red-900 rounded mb-2 flex items-center justify-center">
                                            <div class="text-center">
                                                <div class="text-white font-bold text-lg">PDF</div>
                                            </div>
                                        </div>
                                    @elseif($isModel)
                                        <div class="w-full h-24 bg-gradient-to-br from-cyan-600 to-cyan-900 rounded mb-2 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M6 2c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6H6zm7 7V3.5L17.5 9H13z" opacity="0.3"/><path d="M9 11h6v2H9zm0 4h6v2H9z"/></svg>
                                        </div>
                                    @else
                                        <div class="w-full h-24 bg-slate-600 rounded mb-2 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/></svg>
                                        </div>
                                    @endif
                                    <p class="text-xs text-gray-300 truncate mb-2">{{ basename($file['file_path']) }}</p>
                                    <div class="flex gap-1">
                                        <button type="button" wire:click="reviewFile('existing', {{ $loop->index }})" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white rounded px-2 py-1 text-xs transition">👁️ Review</button>
                                        <button type="button" wire:click="removeExistingFile({{ $file['id'] }})" class="bg-red-600 hover:bg-red-700 text-white rounded px-2 py-1 transition">
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
                        <label class="block text-sm font-medium text-gray-300 mb-2">New Files</label>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($newFiles as $index => $file)
                                <div class="relative bg-slate-700/50 rounded-lg p-3 border border-blue-700/20">
                                    @php
                                        $mimeType = $file->getMimeType();
                                        $isImage = str_starts_with($mimeType, 'image/');
                                        $isVideo = str_starts_with($mimeType, 'video/');
                                        $isAudio = str_starts_with($mimeType, 'audio/');
                                        $isPdf = str_contains($mimeType, 'pdf');
                                        $isModel = str_ends_with($file->getClientOriginalName(), '.glb') || str_ends_with($file->getClientOriginalName(), '.gltf') || str_ends_with($file->getClientOriginalName(), '.obj');
                                    @endphp
                                    @if($isImage)
                                        <img src="{{ $file->temporaryUrl() }}" class="w-full h-24 object-cover rounded mb-2" alt="Media" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                                        <div class="w-full h-24 bg-slate-600 rounded mb-2 flex items-center justify-center hidden">
                                            <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                                        </div>
                                    @elseif($isVideo)
                                        <div class="w-full h-24 bg-gradient-to-br from-purple-600 to-purple-900 rounded mb-2 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M18 3v2h-2V3H8v2H6V3H4v18h16V3h-2zm-2 16H8v-2h8v2z"/><path d="M6 7h12v8H6z" opacity="0.3"/></svg>
                                        </div>
                                    @elseif($isAudio)
                                        <div class="w-full h-24 bg-gradient-to-br from-blue-600 to-blue-900 rounded mb-2 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v9.28c-.47-.46-1.12-.75-1.84-.75-1.66 0-3 1.34-3 3s1.34 3 3 3c1.66 0 3-1.34 3-3V7h7V3h-8z"/></svg>
                                        </div>
                                    @elseif($isPdf)
                                        <div class="w-full h-24 bg-gradient-to-br from-red-600 to-red-900 rounded mb-2 flex items-center justify-center">
                                            <div class="text-center">
                                                <div class="text-white font-bold text-lg">PDF</div>
                                            </div>
                                        </div>
                                    @elseif($isModel)
                                        <div class="w-full h-24 bg-gradient-to-br from-cyan-600 to-cyan-900 rounded mb-2 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M6 2c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6H6zm7 7V3.5L17.5 9H13z" opacity="0.3"/><path d="M9 11h6v2H9zm0 4h6v2H9z"/></svg>
                                        </div>
                                    @else
                                        <div class="w-full h-24 bg-slate-600 rounded mb-2 flex items-center justify-center">
                                            <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/></svg>
                                        </div>
                                    @endif
                                    <p class="text-xs text-gray-300 truncate mb-2">{{ $file->getClientOriginalName() }}</p>
                                    <div class="flex gap-1">
                                        <button type="button" wire:click="reviewFile('new', {{ $loop->index }})" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white rounded px-2 py-1 text-xs transition">👁️ Review</button>
                                        <button type="button" wire:click="removeNewFile({{ $loop->index }})" class="bg-red-600 hover:bg-red-700 text-white rounded px-2 py-1 transition">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="flex gap-3 pt-4">
                    <button type="button" wire:click="close" class="flex-1 px-4 py-2 bg-slate-700 text-white rounded-lg hover:bg-slate-600 transition font-medium">Cancel</button>
                    <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed" class="flex-1 px-4 py-2 bg-gradient-to-r from-blue-600 to-black text-white rounded-lg hover:shadow-lg hover:shadow-blue-500/50 transition font-semibold disabled:hover:shadow-none">
                        <span wire:loading.remove wire:target="save">{{ $mode === 'edit' ? 'Update Post' : 'Create Post' }}</span>
                        <span wire:loading wire:target="save" class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
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
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-blue-700/30 max-w-md w-full p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-white">File Preview</h2>
                <button type="button" wire:click="closeReviewModal" class="text-gray-400 hover:text-white transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="space-y-4">
                <div class="bg-slate-700/50 rounded-lg p-6 flex items-center justify-center min-h-48">
                    @if($reviewFileType === 'existing' && isset($existingFiles[$reviewFileIndex]))
                        @php
                            $file = $existingFiles[$reviewFileIndex];
                            $isImage = str_contains($file['file_type'], 'image') || str_ends_with($file['file_path'], '.webp');
                            $isVideo = str_contains($file['file_type'], 'video');
                            $isAudio = str_contains($file['file_type'], 'audio');
                            $isPdf = str_contains($file['file_type'], 'pdf');
                            $isModel = str_ends_with($file['file_path'], '.glb') || str_ends_with($file['file_path'], '.gltf') || str_ends_with($file['file_path'], '.obj');
                        @endphp
                        @if($isImage)
                            <img src="{{ asset('storage/' . $file['file_path']) }}" class="max-w-full max-h-40 rounded" alt="Preview" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                            <div class="hidden flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400 mb-3" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                                <p class="text-gray-300 font-semibold">{{ basename($file['file_path']) }}</p>
                            </div>
                        @elseif($isVideo)
                            <div class="text-center">
                                <svg class="w-16 h-16 mx-auto text-purple-400 mb-3" fill="currentColor" viewBox="0 0 24 24"><path d="M18 3v2h-2V3H8v2H6V3H4v18h16V3h-2zm-2 16H8v-2h8v2z"/><path d="M6 7h12v8H6z" opacity="0.3"/></svg>
                                <p class="text-gray-300 font-semibold">{{ basename($file['file_path']) }}</p>
                                <p class="text-gray-400 text-sm mt-1">Video File</p>
                            </div>
                        @elseif($isAudio)
                            <div class="text-center">
                                <svg class="w-16 h-16 mx-auto text-blue-400 mb-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v9.28c-.47-.46-1.12-.75-1.84-.75-1.66 0-3 1.34-3 3s1.34 3 3 3c1.66 0 3-1.34 3-3V7h7V3h-8z"/></svg>
                                <p class="text-gray-300 font-semibold">{{ basename($file['file_path']) }}</p>
                                <p class="text-gray-400 text-sm mt-1">Audio File</p>
                            </div>
                        @elseif($isPdf)
                            <div class="text-center">
                                <div class="w-16 h-16 mx-auto mb-3 bg-gradient-to-br from-red-600 to-red-900 rounded-lg flex items-center justify-center">
                                    <span class="text-white font-bold text-xl">PDF</span>
                                </div>
                                <p class="text-gray-300 font-semibold">{{ basename($file['file_path']) }}</p>
                                <p class="text-gray-400 text-sm mt-1">PDF Document</p>
                            </div>
                        @elseif($isModel)
                            <div class="text-center">
                                <svg class="w-16 h-16 mx-auto text-cyan-400 mb-3" fill="currentColor" viewBox="0 0 24 24"><path d="M6 2c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6H6zm7 7V3.5L17.5 9H13z" opacity="0.3"/><path d="M9 11h6v2H9zm0 4h6v2H9z"/></svg>
                                <p class="text-gray-300 font-semibold">{{ basename($file['file_path']) }}</p>
                                <p class="text-gray-400 text-sm mt-1">3D Model</p>
                            </div>
                        @else
                            <div class="text-center">
                                <svg class="w-16 h-16 mx-auto text-gray-400 mb-3" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/></svg>
                                <p class="text-gray-300 font-semibold">{{ basename($file['file_path']) }}</p>
                            </div>
                        @endif
                    @elseif($reviewFileType === 'new' && isset($newFiles[$reviewFileIndex]))
                        @php
                            $file = $newFiles[$reviewFileIndex];
                            $mimeType = $file->getMimeType();
                            $isImage = str_starts_with($mimeType, 'image/');
                            $isVideo = str_starts_with($mimeType, 'video/');
                            $isAudio = str_starts_with($mimeType, 'audio/');
                            $isPdf = str_contains($mimeType, 'pdf');
                            $isModel = str_ends_with($file->getClientOriginalName(), '.glb') || str_ends_with($file->getClientOriginalName(), '.gltf') || str_ends_with($file->getClientOriginalName(), '.obj');
                        @endphp
                        @if($isImage)
                            <img src="{{ $file->temporaryUrl() }}" class="max-w-full max-h-40 rounded" alt="Preview" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                            <div class="hidden flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400 mb-3" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                                <p class="text-gray-300 font-semibold">{{ $file->getClientOriginalName() }}</p>
                            </div>
                        @elseif($isVideo)
                            <div class="text-center">
                                <svg class="w-16 h-16 mx-auto text-purple-400 mb-3" fill="currentColor" viewBox="0 0 24 24"><path d="M18 3v2h-2V3H8v2H6V3H4v18h16V3h-2zm-2 16H8v-2h8v2z"/><path d="M6 7h12v8H6z" opacity="0.3"/></svg>
                                <p class="text-gray-300 font-semibold">{{ $file->getClientOriginalName() }}</p>
                                <p class="text-gray-400 text-sm mt-1">{{ number_format($file->getSize() / 1024, 1) }} KB</p>
                            </div>
                        @elseif($isAudio)
                            <div class="text-center">
                                <svg class="w-16 h-16 mx-auto text-blue-400 mb-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v9.28c-.47-.46-1.12-.75-1.84-.75-1.66 0-3 1.34-3 3s1.34 3 3 3c1.66 0 3-1.34 3-3V7h7V3h-8z"/></svg>
                                <p class="text-gray-300 font-semibold">{{ $file->getClientOriginalName() }}</p>
                                <p class="text-gray-400 text-sm mt-1">{{ number_format($file->getSize() / 1024, 1) }} KB</p>
                            </div>
                        @elseif($isPdf)
                            <div class="text-center">
                                <div class="w-16 h-16 mx-auto mb-3 bg-gradient-to-br from-red-600 to-red-900 rounded-lg flex items-center justify-center">
                                    <span class="text-white font-bold text-xl">PDF</span>
                                </div>
                                <p class="text-gray-300 font-semibold">{{ $file->getClientOriginalName() }}</p>
                                <p class="text-gray-400 text-sm mt-1">{{ number_format($file->getSize() / 1024, 1) }} KB</p>
                            </div>
                        @elseif($isModel)
                            <div class="text-center">
                                <svg class="w-16 h-16 mx-auto text-cyan-400 mb-3" fill="currentColor" viewBox="0 0 24 24"><path d="M6 2c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6H6zm7 7V3.5L17.5 9H13z" opacity="0.3"/><path d="M9 11h6v2H9zm0 4h6v2H9z"/></svg>
                                <p class="text-gray-300 font-semibold">{{ $file->getClientOriginalName() }}</p>
                                <p class="text-gray-400 text-sm mt-1">{{ number_format($file->getSize() / 1024, 1) }} KB</p>
                            </div>
                        @else
                            <div class="text-center">
                                <svg class="w-16 h-16 mx-auto text-gray-400 mb-3" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/></svg>
                                <p class="text-gray-300 font-semibold">{{ $file->getClientOriginalName() }}</p>
                                <p class="text-gray-400 text-sm mt-1">{{ number_format($file->getSize() / 1024, 1) }} KB</p>
                            </div>
                        @endif
                    @endif
                </div>

                <div class="flex gap-3 pt-4">
                    @if($reviewFileType === 'existing' && isset($existingFiles[$reviewFileIndex]))
                        <button type="button" wire:click="removeExistingFile({{ $existingFiles[$reviewFileIndex]['id'] }}); closeReviewModal();" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">Delete</button>
                    @elseif($reviewFileType === 'new' && isset($newFiles[$reviewFileIndex]))
                        <button type="button" wire:click="removeNewFile({{ $reviewFileIndex }}); closeReviewModal();" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">Delete</button>
                    @endif
                    <button type="button" wire:click="closeReviewModal" class="flex-1 px-4 py-2 bg-gradient-to-r from-blue-600 to-black text-white rounded-lg hover:shadow-lg hover:shadow-blue-500/50 transition font-semibold">Close</button>
                </div>
            </div>
        </div>
    </div>
@endif
