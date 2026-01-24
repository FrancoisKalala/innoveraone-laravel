<div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-blue-700/20 p-4">
    @if($isDeleted)
        <p class="text-sm text-gray-400">Comment removed.</p>
    @else
    <div class="flex items-start gap-3">
        <img src="{{ $comment->user->profile_photo_path ? asset('storage/' . $comment->user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($comment->user->name) . '&background=8b5cf6&color=fff' }}" alt="{{ $comment->user->name }}" class="w-10 h-10 rounded-full object-cover flex-shrink-0">
        <div class="flex-1">
            <div class="flex items-center gap-2 mb-2">
                <h4 class="font-bold text-white text-sm">{{ $comment->user->name }}</h4>
                <span class="text-xs text-gray-500">{{ '@' . ($comment->user->username ?? strtolower(str_replace(' ', '', $comment->user->name))) }}</span>
                <span class="text-xs text-gray-600">{{ $comment->created_at->diffForHumans() }}</span>
                @if(auth()->id() === $comment->post->user_id)
                    <button wire:click="togglePin" class="ml-2 px-2 py-1 rounded text-xs font-semibold transition {{ $comment->is_pinned ? 'bg-yellow-400 text-black' : 'bg-slate-700 text-yellow-400 hover:bg-yellow-400 hover:text-black' }}" title="{{ $comment->is_pinned ? 'Unpin' : 'Pin' }} comment">
                        📌
                    </button>
                    <button wire:click="toggleHighlight" class="ml-1 px-2 py-1 rounded text-xs font-semibold transition {{ $comment->is_highlighted ? 'bg-blue-400 text-white' : 'bg-slate-700 text-blue-400 hover:bg-blue-400 hover:text-white' }}" title="{{ $comment->is_highlighted ? 'Unhighlight' : 'Highlight' }} comment">
                        ⭐
                    </button>
                @endif
                @if($comment->is_pinned)
                    <span class="ml-2 px-2 py-1 rounded bg-yellow-400 text-black text-xs font-bold">Pinned</span>
                @endif
                @if($comment->is_highlighted)
                    <span class="ml-1 px-2 py-1 rounded bg-blue-400 text-white text-xs font-bold">Highlighted</span>
                @endif
            </div>
            @if($isEditing)
                <div class="space-y-2 mb-2">
                    <div class="relative">
                        <textarea id="editContent-{{ $comment->id }}" wire:model.defer="editContent" rows="3" class="w-full px-3 py-2 rounded-lg bg-slate-700 text-white placeholder-gray-500 border border-blue-700/30 focus:border-blue-700 focus:outline-none transition text-sm"></textarea>
                        <button type="button" id="emoji-btn-edit-{{ $comment->id }}" class="absolute right-2 top-2 text-xl transition-transform duration-300" onclick="const picker = document.getElementById('emoji-picker-edit-{{ $comment->id }}'); picker.classList.toggle('hidden'); this.classList.toggle('scale-125'); this.classList.toggle('rotate-12');">😊</button>
                        <div id="emoji-picker-edit-{{ $comment->id }}" class="absolute right-2 top-10 z-[9999] bg-slate-800 border border-blue-700/30 rounded-lg p-2 mt-2 hidden shadow-xl" style="max-width: 250px; max-height: 180px; overflow-y: auto;">
                            <div class="flex flex-wrap gap-1">
                                @foreach(['😀','😁','😂','🤣','😃','😄','😅','😆','😉','😊','😋','😎','😍','😘','🥰','😗','😙','😚','🙂','🤗','🤩','🤔','🤨','😐','😑','😶','🙄','😏','😣','😥','😮','🤐','😯','😪','😫','🥱','😴','😌','😛','😜','😝','🤤','😒','😓','😔','😕','🙃','🤑','😲','☹️','🙁','😖','😞','😟','😤','😢','😭','😦','😧','😨','😩','🤯','😬','😰','😱','🥵','🥶','😳','🤪','😵','😡','😠','🤬','😷','🤒','🤕','🤢','🤮','🤧','😇','🥳','🥺','🤠','🤡','🤥','🤫','🤭','🧐','🤓','😈','👿','👹','👺','💀','👻','👽','🤖','💩','😺','😸','😹','😻','😼','😽','🙀','😿','😾'] as $emoji)
                                    <button type="button" class="text-xl p-1 hover:bg-slate-700 rounded" onclick="document.getElementById('editContent-{{ $comment->id }}').value += '{{ $emoji }}'; document.getElementById('editContent-{{ $comment->id }}').dispatchEvent(new Event('input'))">{{ $emoji }}</button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @error('editContent') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    <div class="flex gap-2">
                        <button type="button" wire:click="updateComment" class="px-3 py-1 bg-gradient-to-r from-blue-700 to-black text-white rounded text-xs font-semibold hover:shadow-lg transition">Save</button>
                        <button type="button" wire:click="cancelEdit" class="px-3 py-1 bg-slate-700 text-white rounded text-xs font-semibold hover:bg-slate-600 transition">Cancel</button>
                    </div>
                </div>
            @else
                <p class="text-gray-200 mb-2 text-sm">{{ $comment->content }}</p>
            @endif
            <div class="flex gap-3 text-xs">
                <button wire:click="toggleLike" class="flex items-center gap-1 transition {{ $isLiked ? 'text-red-500' : 'text-gray-400 hover:text-red-500' }}"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>{{ $likeCount }}</button>
                <button wire:click="$toggle('showReplies')" class="text-gray-400 hover:text-blue-600 transition">💬 Reply</button>
                @if($comment->user_id === auth()->id())
                    <button wire:click="startEdit" class="text-gray-400 hover:text-yellow-400 transition">✏️</button>
                    <button wire:click="deleteComment" class="text-gray-400 hover:text-red-500 transition">🗑️</button>
                @endif
            </div>
            @if($showReplies)
                <div class="mt-3 pt-3 border-t border-blue-700/20">
                    <form wire:submit.prevent="addReply" class="space-y-2">
                        <div class="relative">
                            <textarea id="replyContent-{{ $comment->id }}" wire:model="replyContent" placeholder="Write a reply..." class="w-full px-3 py-2 rounded-lg bg-slate-700 text-white placeholder-gray-500 border border-blue-700/20 focus:border-blue-700 focus:outline-none transition text-xs" rows="2"></textarea>
                                <button type="button" id="emoji-btn-reply-{{ $comment->id }}" class="absolute right-2 top-2 text-xl transition-transform duration-300" onclick="const picker = document.getElementById('emoji-picker-{{ $comment->id }}'); picker.classList.toggle('hidden'); this.classList.toggle('scale-125'); this.classList.toggle('rotate-12');">😊</button>
                                <div id="emoji-picker-{{ $comment->id }}" class="absolute right-2 top-10 z-10 bg-slate-800 border border-blue-700/30 rounded-lg p-2 mt-2 hidden shadow-xl" style="max-width: 250px; max-height: 180px; overflow-y: auto;">
                                    <div class="flex flex-wrap gap-1">
                                    @foreach(['😀','😁','😂','🤣','😃','😄','😅','😆','😉','😊','😋','😎','😍','😘','🥰','😗','😙','😚','🙂','🤗','🤩','🤔','🤨','😐','😑','😶','🙄','😏','😣','😥','😮','🤐','😯','😪','😫','🥱','😴','😌','😛','😜','😝','🤤','😒','😓','😔','😕','🙃','🤑','😲','☹️','🙁','😖','😞','😟','😤','😢','😭','😦','😧','😨','😩','🤯','😬','😰','😱','🥵','🥶','😳','🤪','😵','😡','😠','🤬','😷','🤒','🤕','🤢','🤮','🤧','😇','🥳','🥺','🤠','🤡','🤥','🤫','🤭','🧐','🤓','😈','👿','👹','👺','💀','👻','👽','🤖','💩','😺','😸','😹','😻','😼','😽','🙀','😿','😾'] as $emoji)
                                        <button type="button" class="text-xl p-1 hover:bg-slate-700 rounded" onclick="document.getElementById('replyContent-{{ $comment->id }}').value += '{{ $emoji }}'; document.getElementById('replyContent-{{ $comment->id }}').dispatchEvent(new Event('input'))">{{ $emoji }}</button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="px-3 py-1 bg-gradient-to-r from-blue-700 to-black text-white rounded text-xs font-semibold hover:shadow-lg transition">Reply</button>
                    </form>
                </div>
            @endif
            @if($replies->count() > 0)
                <div class="mt-3 space-y-2 pl-3 border-l border-blue-700/20">
                    @foreach($replies as $reply)
                        <div class="bg-slate-700/30 p-2 rounded text-xs">
                            <div class="flex items-start gap-2 mb-1">
                                <img src="{{ $reply->user->profile_photo_path ? asset('storage/' . $reply->user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($reply->user->name) . '&background=8b5cf6&color=fff' }}" alt="{{ $reply->user->name }}" class="w-6 h-6 rounded-full object-cover flex-shrink-0">
                                <div class="flex-1">
                                    <span class="font-semibold text-white">{{ $reply->user->name }}</span>
                                    <span class="text-gray-500 ml-1">{{ $reply->created_at->diffForHumans() }}</span>
                                </div>
                                @if($reply->user_id === auth()->id())
                                    <div class="flex gap-2 text-gray-400">
                                        <button wire:click="startReplyEdit({{ $reply->id }})" class="hover:text-yellow-300 transition" title="Edit reply">✏️</button>
                                        <button wire:click="deleteReply({{ $reply->id }})" class="hover:text-red-400 transition" title="Delete reply">🗑️</button>
                                    </div>
                                @endif
                            </div>
                            @if($editingReplyId === $reply->id)
                                <div class="space-y-2">
                                    <div class="relative">
                                        <textarea id="editingReplyContent-{{ $reply->id }}" wire:model.defer="editingReplyContent" rows="2" class="w-full px-3 py-2 rounded bg-slate-800 text-white border border-blue-700/30 focus:border-blue-700 focus:outline-none transition"></textarea>
                                        <button type="button" id="emoji-btn-edit-reply-{{ $reply->id }}" class="absolute right-2 top-2 text-xl transition-transform duration-300" onclick="const picker = document.getElementById('emoji-picker-edit-reply-{{ $reply->id }}'); picker.classList.toggle('hidden'); this.classList.toggle('scale-125'); this.classList.toggle('rotate-12');">😊</button>
                                        <div id="emoji-picker-edit-reply-{{ $reply->id }}" class="absolute right-2 top-10 z-10 bg-slate-800 border border-blue-700/30 rounded-lg p-2 mt-2 hidden shadow-xl" style="max-width: 250px; max-height: 180px; overflow-y: auto;">
                                            <div class="flex flex-wrap gap-1">
                                                @foreach(['😀','😁','😂','🤣','😃','😄','😅','😆','😉','😊','😋','😎','😍','😘','🥰','😗','😙','😚','🙂','🤗','🤩','🤔','🤨','😐','😑','😶','🙄','😏','😣','😥','😮','🤐','😯','😪','😫','🥱','😴','😌','😛','😜','😝','🤤','😒','😓','😔','😕','🙃','🤑','😲','☹️','🙁','😖','😞','😟','😤','😢','😭','😦','😧','😨','😩','🤯','😬','😰','😱','🥵','🥶','😳','🤪','😵','😡','😠','🤬','😷','🤒','🤕','🤢','🤮','🤧','😇','🥳','🥺','🤠','🤡','🤥','🤫','🤭','🧐','🤓','😈','👿','👹','👺','💀','👻','👽','🤖','💩','😺','😸','😹','😻','😼','😽','🙀','😿','😾'] as $emoji)
                                                    <button type="button" class="text-xl p-1 hover:bg-slate-700 rounded" onclick="document.getElementById('editingReplyContent-{{ $reply->id }}').value += '{{ $emoji }}'; document.getElementById('editingReplyContent-{{ $reply->id }}').dispatchEvent(new Event('input'))">{{ $emoji }}</button>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @error('editingReplyContent') <span class="text-red-400 text-[11px]">{{ $message }}</span> @enderror
                                    <div class="flex gap-2">
                                        <button type="button" wire:click="updateReply" class="px-3 py-1 bg-gradient-to-r from-blue-700 to-black text-white rounded font-semibold hover:shadow-lg transition">Save</button>
                                        <button type="button" wire:click="cancelReplyEdit" class="px-3 py-1 bg-slate-700 text-white rounded font-semibold hover:bg-slate-600 transition">Cancel</button>
                                    </div>
                                </div>
                            @else
                                <p class="text-gray-300">{{ $reply->content }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
    @endif
</div>

