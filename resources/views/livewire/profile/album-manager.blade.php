<div class="bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 rounded-2xl border border-blue-700/20 p-6">
    @if(session()->has('success'))<div class="mb-4 p-3 bg-green-500/20 border border-green-500/50 rounded-lg text-green-400 text-sm">{{ session('success') }}</div>@endif
    <div class="grid md:grid-cols-2 gap-8">
        <div><h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>Profile Photo</h3>
            <div class="space-y-4">
                <div class="w-40 h-40 mx-auto rounded-full overflow-hidden border-4 border-blue-700/50 bg-slate-700/50 flex items-center justify-center">@if(auth()->user()->profile_photo_path)<img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="Profile" class="w-full h-full object-cover">@else<svg class="w-20 h-20 text-gray-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>@endif</div>
                <div class="border-2 border-dashed border-blue-700/30 rounded-lg p-6 text-center hover:border-blue-700/50 transition cursor-pointer">
                    <input type="file" wire:model="profilePhoto" accept="image/*" class="hidden" id="profile-upload">
                    <label for="profile-upload" class="cursor-pointer"><svg class="w-8 h-8 mx-auto mb-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg><p class="text-gray-300 text-sm">Click to upload profile photo</p></label>
                </div>
                @if($profilePhoto)<button wire:click="uploadProfilePhoto" class="w-full py-2 px-4 bg-gradient-to-r from-blue-700 to-black text-white rounded-lg font-semibold hover:shadow-lg transition">Upload Profile Photo</button>@endif
            </div>
        </div>
        <div><h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>Cover Photo</h3>
            <div class="space-y-4">
                <div class="w-full h-40 rounded-lg overflow-hidden border-4 border-blue-700/50 bg-slate-700/50 flex items-center justify-center">@if(auth()->user()->cover_photo_path)<img src="{{ asset('storage/' . auth()->user()->cover_photo_path) }}" alt="Cover" class="w-full h-full object-cover">@else<svg class="w-16 h-16 text-gray-600" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2z"/></svg>@endif</div>
                <div class="border-2 border-dashed border-blue-700/30 rounded-lg p-6 text-center hover:border-blue-700/50 transition cursor-pointer">
                    <input type="file" wire:model="coverPhoto" accept="image/*" class="hidden" id="cover-upload">
                    <label for="cover-upload" class="cursor-pointer"><svg class="w-8 h-8 mx-auto mb-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg><p class="text-gray-300 text-sm">Click to upload cover photo</p></label>
                </div>
                @if($coverPhoto)<button wire:click="uploadCoverPhoto" class="w-full py-2 px-4 bg-gradient-to-r from-blue-700 to-black text-white rounded-lg font-semibold hover:shadow-lg transition">Upload Cover Photo</button>@endif
            </div>
        </div>
    </div>
    <div class="mt-10"><h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zm-5-11l-2.5 3.5-2.5-3.5-4 5h12l-3-5z"/></svg>My Albums ({{ $albums->total() }})</h3>
        @if($albums->count() > 0)<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">@foreach($albums as $album)<div class="group relative rounded-lg overflow-hidden border border-blue-700/30 hover:border-blue-700 transition aspect-square cursor-pointer"><img src="{{ asset('storage/' . $album->file_path) }}" alt="Album" class="w-full h-full object-cover group-hover:scale-110 transition"><div class="absolute inset-0 bg-black/0 group-hover:bg-black/50 transition flex items-center justify-center"><div class="space-y-2 opacity-0 group-hover:opacity-100 transition"><p class="text-white text-xs text-center">@if($album->type === 'profile')👤 Profile@elseif($album->type === 'cover')🏞️ Cover@else📸 Photo@endif</p><button wire:click="deleteAlbum({{ $album->id }})" class="block mx-auto px-3 py-1 bg-red-500/80 text-white rounded text-xs hover:bg-red-600 transition">Delete</button></div></div></div>@endforeach</div>
        {{ $albums->links('pagination::tailwind') }}@else<div class="text-center py-12 bg-slate-700/30 rounded-lg border border-blue-700/20"><p class="text-gray-400">No photos yet. Upload some!</p></div>@endif
    </div>
</div>

