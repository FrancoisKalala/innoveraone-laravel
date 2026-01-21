<div>
    @if(session()->has('message'))<div class="mb-4 p-3 bg-green-500/20 border border-green-500/50 rounded-lg text-green-400 text-sm">{{ session('message') }}</div>@endif
    
    <div class="grid md:grid-cols-2 gap-6">
        <!-- Form Section -->
        <div>
            <form wire:submit.prevent="createPost" class="space-y-4">
        <div><textarea wire:model="content" placeholder="Share your thoughts..." class="w-full px-4 py-3 rounded-lg bg-slate-700 text-white placeholder-gray-500 border border-blue-700/20 focus:border-blue-700 focus:outline-none transition" rows="4"></textarea>@error('content')<span class="text-red-400 text-sm">{{ $message }}</span>@enderror</div>
        @if(!$albumId)<div><select wire:model="album_id" class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white border border-blue-700/20 focus:border-blue-700 focus:outline-none transition"><option value="">📁 Select an album (optional)</option>@if($albums)@foreach($albums as $album)<option value="{{ $album->id }}">{{ $album->title }}</option>@endforeach @endif</select></div>@endif
        <div><select wire:model="interaction_type" class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white border border-blue-700/20 focus:border-blue-700 focus:outline-none transition text-sm"><option value="all">✨ Allow: Like & Comment</option><option value="like">👍 Allow: Like Only</option><option value="comment">💬 Allow: Comment Only</option><option value="like_comment">👍💬 Allow: Like & Comment</option><option value="none">🔒 No Interactions</option></select></div>
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
</div>

