<div>
    @if(session()->has('message'))<div class="mb-4 p-3 bg-green-500/20 border border-green-500/50 rounded-lg text-green-400 text-sm">{{ session('message') }}</div>@endif

    <div class="grid md:grid-cols-2 gap-6">
        <!-- Form Section -->
        <div>
            <form wire:submit.prevent="createPost" class="space-y-4">
        <div><textarea wire:model="content" placeholder="Share your thoughts..." class="w-full px-4 py-3 rounded-lg bg-slate-700 text-white placeholder-gray-500 border border-blue-700/20 focus:border-blue-700 focus:outline-none transition" rows="4"></textarea>@error('content')<span class="text-red-400 text-sm">{{ $message }}</span>@enderror</div>
        @if(!$albumId)<div><select wire:model="album_id" class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white border border-blue-700/20 focus:border-blue-700 focus:outline-none transition"><option value="">📁 Select an album (optional)</option>@if($albums)@foreach($albums as $album)<option value="{{ $album->id }}">{{ $album->title }}</option>@endforeach @endif</select></div>@endif
        <div><select wire:model="interaction_type" class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white border border-blue-700/20 focus:border-blue-700 focus:outline-none transition text-sm"><option value="all">✨ Allow: All</option><option value="like">👍 Allow: Like</option><option value="comment">💬 Allow: Comment</option><option value="like_comment">👍💬 Allow: Like & Comment</option><option value="none">🔒 No Interactions</option></select></div>
        <div><select wire:model="expiration_hours" class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white border border-blue-700/20 focus:border-blue-700 focus:outline-none transition text-sm"><option value="5">⏱️ Expires in 5 hours</option><option value="10">⏱️ Expires in 10 hours</option><option value="24" selected>⏱️ Expires in 24 hours</option><option value="72">⏱️ Expires in 3 days</option><option value="168">⏱️ Expires in 1 week</option><option value="720">⏱️ Expires in 30 days</option></select></div>

        <!-- File Upload with Progress -->
        <div class="space-y-2">
            <div class="border-2 border-dashed border-blue-700/30 rounded-lg p-4 text-center hover:border-blue-700/50 transition cursor-pointer">
                <input type="file" wire:model="files" multiple accept="image/*,video/*,audio/*,.pdf,.glb,.gltf,.obj,.svg" class="hidden" id="file-upload">
                <label for="file-upload" class="cursor-pointer"><svg class="w-8 h-8 mx-auto mb-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg><p class="text-gray-300 text-sm">Click to upload media (max 10 files, 50 MB each)</p></label>
                @if($files)<div class="mt-2 text-green-400 text-sm">{{ count($files) }} file(s) selected</div>@endif
            </div>

            <!-- Upload Progress Bar -->
            <div wire:loading wire:target="files" class="space-y-2">
                <div class="flex items-center justify-between text-xs text-blue-400">
                    <span>📤 Uploading & Compressing Files...</span>
                    <span class="animate-pulse">Processing...</span>
                </div>
                <div class="w-full bg-slate-700 rounded-full h-2 overflow-hidden border border-blue-700/20">
                    <div class="h-full bg-gradient-to-r from-blue-600 to-cyan-500 animate-pulse" style="width: 75%"></div>
                </div>
            </div>

            <!-- File Upload Errors -->
            @error('files')<span class="text-red-400 text-sm block">{{ $message }}</span>@enderror
            @error('files.*')<span class="text-red-400 text-sm block">{{ $message }}</span>@enderror
        </div>

        <!-- Files Preview -->
        @if(count($files) > 0)
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Selected Files</label>
                <div class="grid grid-cols-2 gap-3">
                    @foreach($files as $index => $file)
                        <div class="relative bg-slate-700/50 rounded-lg p-3 border border-blue-700/20">
                            @php
                                $mimeType = $file->getMimeType();
                                $fileName = $file->getClientOriginalName();
                                $isImage = str_starts_with($mimeType, 'image/');
                                $isVideo = str_starts_with($mimeType, 'video/');
                                $isAudio = str_starts_with($mimeType, 'audio/');
                                $isPdf = str_contains($mimeType, 'pdf');
                                $isModel = str_ends_with($fileName, '.glb') || str_ends_with($fileName, '.gltf') || str_ends_with($fileName, '.obj');
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
                                <button type="button" wire:click="reviewFile({{ $index }})" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white rounded px-2 py-1 text-xs transition">👁️ Review</button>
                                <button type="button" wire:click="removeFile({{ $index }})" class="bg-red-600 hover:bg-red-700 text-white rounded-full p-1 transition">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Submit Button with Loading State -->
        <button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-50 cursor-not-allowed" class="w-full py-3 px-4 bg-gradient-to-r from-blue-700 to-black text-white font-bold rounded-lg hover:shadow-lg hover:shadow-blue-700/50 transition disabled:hover:shadow-none">
            <span wire:loading.remove wire:target="createPost">🚀 Post Now</span>
            <span wire:loading wire:target="createPost" class="flex items-center justify-center gap-2">
                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Posting...
            </span>
        </button>
            </form>
        </div>

        <!-- Tips Section -->
        <div class="bg-slate-700/30 rounded-lg p-4 space-y-4">
            <h4 class="font-bold text-blue-600 text-lg">💡 Tips for Great Posts</h4>
            <div class="space-y-3 text-sm text-gray-300">
                <div class="flex gap-2"><span class="text-lg">📸</span><p>Add eye-catching visuals to get more engagement</p></div>
                <div class="flex gap-2"><span class="text-lg">⏰</span><p>Set expiration times to create urgency</p></div>
                <div class="flex gap-2"><span class="text-lg">🎯</span><p>Choose the right interactions for your content</p></div>
                <div class="flex gap-2"><span class="text-lg">📁</span><p>Organize content in albums</p></div>
                <div class="flex gap-2"><span class="text-lg">✨</span><p>Be authentic and have fun!</p></div>
            </div>
            <hr class="border-slate-600/50">
            <h4 class="font-bold text-blue-600 text-lg">📁 Supported Formats</h4>
            <div class="space-y-2 text-xs text-gray-400">
                <div><span class="text-blue-400">🖼️ Images:</span> JPG, PNG, GIF, WebP</div>
                <div><span class="text-blue-400">🎬 Videos:</span> MP4, WebM, MOV, AVI</div>
                <div><span class="text-blue-400">🎵 Audio:</span> MP3, WAV, AAC, FLAC</div>
                <div><span class="text-blue-400">📄 Documents:</span> PDF</div>
                <div><span class="text-blue-400">🎨 Vector:</span> SVG</div>
                <div><span class="text-blue-400">🎯 3D Models:</span> GLB, GLTF, OBJ</div>
                <div class="text-blue-500 font-semibold">Max: 10 files × 50 MB each</div>
            </div>
        </div>
    </div>

    <!-- File Review Modal -->
    @if($showReviewModal && $reviewFileIndex !== null && isset($files[$reviewFileIndex]))
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-blue-700/30 max-w-2xl w-full p-6 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-white">File Preview</h2>
                    <button type="button" wire:click="closeReviewModal" class="text-gray-400 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="space-y-4">
                    <div class="bg-slate-700/50 rounded-lg p-6 flex items-center justify-center min-h-64">
                        @php
                            $mimeType = $files[$reviewFileIndex]->getMimeType();
                            $fileName = $files[$reviewFileIndex]->getClientOriginalName();
                            $isImage = str_starts_with($mimeType, 'image/');
                            $isVideo = str_starts_with($mimeType, 'video/');
                            $isAudio = str_starts_with($mimeType, 'audio/');
                            $isPdf = str_contains($mimeType, 'pdf');
                            $isModel = str_ends_with($fileName, '.glb') || str_ends_with($fileName, '.gltf') || str_ends_with($fileName, '.obj');
                        @endphp
                        @if($isImage)
                            <img src="{{ $files[$reviewFileIndex]->temporaryUrl() }}" class="max-w-full max-h-64 rounded" alt="Preview" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                            <div class="hidden flex-col items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400 mb-3" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
                                <p class="text-gray-300 font-semibold">{{ $fileName }}</p>
                            </div>
                        @elseif($isVideo)
                            <div class="text-center">
                                <svg class="w-16 h-16 mx-auto text-purple-400 mb-3" fill="currentColor" viewBox="0 0 24 24"><path d="M18 3v2h-2V3H8v2H6V3H4v18h16V3h-2zm-2 16H8v-2h8v2z"/><path d="M6 7h12v8H6z" opacity="0.3"/></svg>
                                <p class="text-gray-300 font-semibold mb-2">{{ $fileName }}</p>
                                <p class="text-gray-400 text-sm">Video File</p>
                            </div>
                        @elseif($isAudio)
                            <div class="text-center">
                                <svg class="w-16 h-16 mx-auto text-blue-400 mb-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v9.28c-.47-.46-1.12-.75-1.84-.75-1.66 0-3 1.34-3 3s1.34 3 3 3c1.66 0 3-1.34 3-3V7h7V3h-8z"/></svg>
                                <p class="text-gray-300 font-semibold mb-2">{{ $fileName }}</p>
                                <p class="text-gray-400 text-sm">Audio File</p>
                            </div>
                        @elseif($isPdf)
                            <div class="text-center">
                                <div class="w-16 h-16 mx-auto mb-3 bg-gradient-to-br from-red-600 to-red-900 rounded-lg flex items-center justify-center">
                                    <span class="text-white font-bold text-xl">PDF</span>
                                </div>
                                <p class="text-gray-300 font-semibold mb-2">{{ $fileName }}</p>
                                <p class="text-gray-400 text-sm">PDF Document</p>
                            </div>
                        @elseif($isModel)
                            <div class="text-center">
                                <svg class="w-16 h-16 mx-auto text-cyan-400 mb-3" fill="currentColor" viewBox="0 0 24 24"><path d="M6 2c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6H6zm7 7V3.5L17.5 9H13z" opacity="0.3"/><path d="M9 11h6v2H9zm0 4h6v2H9z"/></svg>
                                <p class="text-gray-300 font-semibold mb-2">{{ $fileName }}</p>
                                <p class="text-gray-400 text-sm">3D Model</p>
                            </div>
                        @else
                            <div class="text-center">
                                <svg class="w-16 h-16 mx-auto text-gray-400 mb-3" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6z"/></svg>
                                <p class="text-gray-300 font-semibold mb-2">{{ $fileName }}</p>
                            </div>
                        @endif
                    </div>

                    <div class="bg-slate-600/30 rounded-lg p-3 text-sm text-gray-300">
                        <div class="flex justify-between">
                            <span class="text-gray-400">File Size:</span>
                            <span>{{ number_format($files[$reviewFileIndex]->getSize() / 1024, 1) }} KB</span>
                        </div>
                    </div>

                    <div class="flex gap-3 pt-4">
                        <button type="button" wire:click="removeFile({{ $reviewFileIndex }})" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium">Delete</button>
                        <button type="button" wire:click="closeReviewModal" class="flex-1 px-4 py-2 bg-gradient-to-r from-blue-600 to-black text-white rounded-lg hover:shadow-lg hover:shadow-blue-500/50 transition font-semibold">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

