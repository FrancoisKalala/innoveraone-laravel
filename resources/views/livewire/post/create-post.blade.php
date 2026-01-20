<div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-blue-700/20 p-6">
    @if(session()->has('message'))<div class="mb-4 p-3 bg-green-500/20 border border-green-500/50 rounded-lg text-green-400 text-sm">{{ session('message') }}</div>@endif
    <div class="grid md:grid-cols-2 gap-6">
        <div>
            <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/></svg>Create Post</h3>
            <form wire:submit.prevent="createPost" class="space-y-4">
                <div><textarea wire:model="content" placeholder="Share your thoughts..." class="w-full px-4 py-3 rounded-lg bg-slate-700 text-white placeholder-gray-500 border border-blue-700/20 focus:border-blue-700 focus:outline-none transition" rows="4"></textarea>@error('content')<span class="text-red-400 text-sm">{{ $message }}</span>@enderror</div>
                <div><select wire:model="chapter_id" class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white border border-blue-700/20 focus:border-blue-700 focus:outline-none transition"><option value="">📁 Select an album (optional)</option>@foreach($chapters as $chapter)<option value="{{ $chapter->id }}">{{ $chapter->title }}</option>@endforeach</select></div>
                <div><select wire:model="interaction_type" class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white border border-blue-700/20 focus:border-blue-700 focus:outline-none transition text-sm"><option value="all">✨ Allow: Like, Dislike & Comment</option><option value="like">👍 Allow: Like Only</option><option value="dislike">👎 Allow: Dislike Only</option><option value="comment">💬 Allow: Comment Only</option><option value="like_comment">👍💬 Allow: Like & Comment</option><option value="like_dislike">👍👎 Allow: Like & Dislike</option><option value="none">🔒 No Interactions</option></select></div>
                <div><select wire:model="expiration_hours" class="w-full px-4 py-2 rounded-lg bg-slate-700 text-white border border-blue-700/20 focus:border-blue-700 focus:outline-none transition text-sm"><option value="5">⏱️ Expires in 5 hours</option><option value="10">⏱️ Expires in 10 hours</option><option value="24" selected>⏱️ Expires in 24 hours</option><option value="72">⏱️ Expires in 3 days</option><option value="168">⏱️ Expires in 1 week</option><option value="720">⏱️ Expires in 30 days</option></select></div>
                <div class="border-2 border-dashed border-blue-700/30 rounded-lg p-4 text-center hover:border-blue-700/50 transition cursor-pointer">
                    <input type="file" wire:model="files" multiple accept="image/*,video/*" class="hidden" id="file-upload">
                    <label for="file-upload" class="cursor-pointer"><svg class="w-8 h-8 mx-auto mb-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg><p class="text-gray-300 text-sm">Click to upload media (max 10 files)</p></label>
                    @if($files)<div class="mt-2 text-green-400 text-sm">{{ count($files) }} file(s) selected</div>@endif
                </div>
                <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-blue-700 to-black text-white font-bold rounded-lg hover:shadow-lg hover:shadow-blue-700/50 transition">🚀 Post Now</button>
            </form>
        </div>
        <div class="bg-slate-700/30 rounded-lg p-4 space-y-4">
            <h4 class="font-bold text-blue-600 text-lg">💡 Tips for Great Posts</h4>
            <div class="space-y-3 text-sm text-gray-300">
                <div class="flex gap-2"><span class="text-lg">📸</span><p>Add eye-catching visuals to get more engagement</p></div>
                <div class="flex gap-2"><span class="text-lg">⏰</span><p>Set expiration times to create urgency</p></div>
                <div class="flex gap-2"><span class="text-lg">🎯</span><p>Choose the right interactions for your content</p></div>
                <div class="flex gap-2"><span class="text-lg">📁</span><p>Organize content in albums</p></div>
                <div class="flex gap-2"><span class="text-lg">✨</span><p>Be authentic and have fun!</p></div>
            </div>
        </div>
    </div>
</div>

