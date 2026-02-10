<div class="p-6 border bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 rounded-2xl border-blue-700/20">
    @if(session()->has('success'))<div class="p-3 mb-4 text-sm text-green-400 border rounded-lg bg-green-500/20 border-green-500/50">{{ session('success') }}</div>@endif
    <div class="grid gap-8 md:grid-cols-2">
        <div><h3 class="flex items-center gap-2 mb-4 text-xl font-bold text-white"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>Profile Photo</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-center w-40 h-40 mx-auto overflow-hidden border-4 rounded-full border-blue-700/50 bg-slate-700/50">
                    <a href="{{ route('user.profile', auth()->user()->id) }}" class="flex items-center justify-center w-40 h-40 mx-auto overflow-hidden border-4 rounded-full border-blue-700/50 bg-slate-700/50">
                        @if(auth()->user()->profile_photo_path)
                            <img src="{{ asset('storage/' . auth()->user()->profile_photo_path) }}" alt="Profile" class="object-cover w-full h-full">
                        @else
                            <svg class="w-20 h-20 text-gray-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                        @endif
                    </a>
                </div>
                <div class="p-6 text-center transition border-2 border-dashed rounded-lg cursor-pointer border-blue-700/30 hover:border-blue-700/50">
                    <input type="file" wire:model="profilePhoto" accept="image/*" class="hidden" id="profile-upload">
                    <label for="profile-upload" class="cursor-pointer"><svg class="w-8 h-8 mx-auto mb-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg><p class="text-sm text-gray-300">Click to upload profile photo</p></label>
                </div>
                @if($profilePhoto)<button wire:click="uploadProfilePhoto" class="w-full px-4 py-2 font-semibold text-white transition rounded-lg bg-gradient-to-r from-blue-700 to-black hover:shadow-lg">Upload Profile Photo</button>@endif
            </div>
        </div>
        <div><h3 class="flex items-center gap-2 mb-4 text-xl font-bold text-white"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>Cover Photo</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-center w-full h-40 overflow-hidden border-4 rounded-lg border-blue-700/50 bg-slate-700/50">
                    @if(auth()->user()->cover_photo_path)
                        <img src="{{ asset('storage/' . auth()->user()->cover_photo_path) }}" alt="Cover" class="object-cover w-full h-full">
                    @else
                        <svg class="w-16 h-16 text-gray-600" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2z"/></svg>
                    @endif
                </div>
                <div class="p-6 text-center transition border-2 border-dashed rounded-lg cursor-pointer border-blue-700/30 hover:border-blue-700/50">
                    <input type="file" wire:model="coverPhoto" accept="image/*" class="hidden" id="cover-upload">
                    <label for="cover-upload" class="cursor-pointer"><svg class="w-8 h-8 mx-auto mb-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg><p class="text-sm text-gray-300">Click to upload cover photo</p></label>
                </div>
                @if($coverPhoto)<button wire:click="uploadCoverPhoto" class="w-full px-4 py-2 font-semibold text-white transition rounded-lg bg-gradient-to-r from-blue-700 to-black hover:shadow-lg">Upload Cover Photo</button>@endif
            </div>
        </div>
    </div>
    <div class="mt-10"><h3 class="flex items-center gap-2 mb-4 text-xl font-bold text-white"><svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M21 19V5c0-1.1-.9-2-2-2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2zm-5-11l-2.5 3.5-2.5-3.5-4 5h12l-3-5z"/></svg>My Albums ({{ $albums->total() }})</h3>
        @if($albums->count() > 0)<div class="grid grid-cols-2 gap-4 md:grid-cols-3 lg:grid-cols-4">@foreach($albums as $album)<div class="relative overflow-hidden transition border rounded-lg cursor-pointer group border-blue-700/30 hover:border-blue-700 aspect-square"><img src="{{ asset('storage/' . $album->file_path) }}" alt="Album" class="object-cover w-full h-full transition group-hover:scale-110"><div class="absolute inset-0 flex items-center justify-center transition bg-black/0 group-hover:bg-black/50"><div class="space-y-2 transition opacity-0 group-hover:opacity-100"><p class="text-xs text-center text-white">@if($album->type === 'profile')👤 Profile@elseif($album->type === 'cover')🏞️ Cover@else📸 Photo@endif</p>@if(isset($unviewedCounts[$album->id]) && $unviewedCounts[$album->id] > 0)<span class="px-2 py-0.5 text-xs font-semibold rounded-lg bg-pink-500/20 border border-pink-500/40 text-pink-400 animate-pulse">{{ $unviewedCounts[$album->id] }} new</span>@endif<button wire:click="deleteAlbum({{ $album->id }})" class="block px-3 py-1 mx-auto text-xs text-white transition rounded bg-red-500/80 hover:bg-red-600">Delete</button></div></div></div>@endforeach</div>
        {{ $albums->links('pagination::tailwind') }}@else<div class="py-12 text-center border rounded-lg bg-slate-700/30 border-blue-700/20"><p class="text-gray-400">No photos yet. Upload some!</p></div>@endif
    </div>
</div>

