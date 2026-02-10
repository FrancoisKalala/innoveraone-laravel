<div>
    @if($show)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm" wire:key="post-update-modal">
        <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-blue-700/30 max-w-2xl w-full p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-white">Edit Post</h2>
                <button type="button" wire:click="close" class="text-gray-400 transition hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <form wire:submit.prevent="update" class="space-y-4">
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-300">Content</label>
                    <textarea wire:model.defer="content" rows="6" class="w-full px-4 py-3 text-white placeholder-gray-500 transition border rounded-lg bg-slate-700 border-blue-700/20 focus:border-blue-700 focus:outline-none" placeholder="Update your post..."></textarea>
                    @error('content') <span class="mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
                </div>
                <!-- Add other fields as needed, matching post-create-modal style -->
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
                        @error('interactionType') <span class="mt-1 text-xs text-red-400">{{ $message }}</span> @enderror
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
                <div class="flex justify-end gap-2">
                    <button type="button" wire:click="close" class="px-4 py-2 text-gray-300 rounded bg-slate-700 hover:bg-slate-800">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-white bg-blue-700 rounded hover:bg-blue-800">Update</button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
