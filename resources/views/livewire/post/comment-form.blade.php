<form wire:submit.prevent="addComment" class="space-y-3">
    <div class="relative">
        <textarea id="commentContent" wire:model.defer="content" rows="3" class="w-full px-4 py-3 rounded-lg bg-slate-800 text-white placeholder-gray-500 border border-blue-700/30 focus:border-blue-700 focus:outline-none transition" placeholder="Add a comment..."></textarea>
        <button type="button" id="emoji-btn-comment" class="absolute right-2 top-2 text-xl transition-transform duration-300" onclick="const picker = document.getElementById('emoji-picker-comment'); picker.classList.toggle('hidden'); this.classList.toggle('scale-125'); this.classList.toggle('rotate-12');">😊</button>
        <div id="emoji-picker-comment" class="absolute right-2 top-10 z-10 bg-slate-800 border border-blue-700/30 rounded-lg p-2 mt-2 hidden shadow-xl" style="max-width: 250px; max-height: 180px; overflow-y: auto;">
            <div class="flex flex-wrap gap-1">
                @foreach(['😀','😁','😂','🤣','😃','😄','😅','😆','😉','😊','😋','😎','😍','😘','🥰','😗','😙','😚','🙂','🤗','🤩','🤔','🤨','😐','😑','😶','🙄','😏','😣','😥','😮','🤐','😯','😪','😫','🥱','😴','😌','😛','😜','😝','🤤','😒','😓','😔','😕','🙃','🤑','😲','☹️','🙁','😖','😞','😟','😤','😢','😭','😦','😧','😨','😩','🤯','😬','😰','😱','🥵','🥶','😳','🤪','😵','😡','😠','🤬','😷','🤒','🤕','🤢','🤮','🤧','😇','🥳','🥺','🤠','🤡','🤥','🤫','🤭','🧐','🤓','😈','👿','👹','👺','💀','👻','👽','🤖','💩','😺','😸','😹','😻','😼','😽','🙀','😿','😾'] as $emoji)
                    <button type="button" class="text-xl p-1 hover:bg-slate-700 rounded" onclick="document.getElementById('commentContent').value += '{{ $emoji }}'; document.getElementById('commentContent').dispatchEvent(new Event('input'))">{{ $emoji }}</button>
                @endforeach
            </div>
        </div>
    </div>
    @error('content') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
    <div class="flex justify-end">
        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-black text-white rounded-lg font-semibold hover:shadow-lg hover:shadow-blue-500/40 transition">Post Comment</button>
    </div>
</form>
