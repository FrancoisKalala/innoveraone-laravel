<form wire:submit.prevent="addComment" class="space-y-3">
    <textarea wire:model.defer="content" rows="3" class="w-full px-4 py-3 rounded-lg bg-slate-800 text-white placeholder-gray-500 border border-blue-700/30 focus:border-blue-700 focus:outline-none transition" placeholder="Add a comment..."></textarea>
    @error('content') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
    <div class="flex justify-end">
        <button type="submit" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-black text-white rounded-lg font-semibold hover:shadow-lg hover:shadow-blue-500/40 transition">Post Comment</button>
    </div>
</form>
