<div>
    @if($show)
    <div x-data
         class="fixed inset-0 z-50 flex items-center justify-center p-4">

        <div @click="$wire.close()" class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>

    <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-blue-700/30 max-w-2xl w-full p-6 relative z-10 max-h-[90vh] overflow-y-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <h2 class="flex items-center gap-2 text-2xl font-bold text-white">
                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M16 2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H4v-2h10v2zm0-3H4V9h10v2zm0-3H4V6h10v2z"/>
                </svg>
                Forward Post
            </h2>
            <button @click="$wire.close()" class="text-gray-400 transition hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Post Preview -->
        @if($post)
            <div class="p-4 mb-6 border bg-slate-700/50 border-blue-700/20 rounded-xl">
                <p class="mb-2 text-sm text-gray-400">Original Post:</p>
                <p class="text-white">{{ Str::limit($post->content, 100) }}</p>
            </div>
        @endif

        <!-- Message Input -->
        <div class="mb-6">
            <label class="block mb-2 text-sm font-medium text-gray-300">Add a message (optional)</label>
            <textarea
                wire:model="forwardMessage"
                placeholder="Add your message..."
                class="w-full px-4 py-3 text-white placeholder-gray-400 transition border resize-none rounded-xl bg-slate-700 border-blue-700/20 focus:border-blue-700/50 focus:ring-2 focus:ring-blue-700/30 focus:outline-none"
                rows="3"
            ></textarea>
        </div>

        <!-- Search Contacts -->
        <div class="mb-6">
            <label class="block mb-2 text-sm font-medium text-gray-300">Select Contacts</label>
            <input
                type="text"
                wire:model.live="searchTerm"
                placeholder="Search contacts..."
                class="w-full px-4 py-2 mb-3 text-white placeholder-gray-400 transition border rounded-xl bg-slate-700 border-blue-700/20 focus:border-blue-700/50 focus:outline-none"
            >

            <!-- Contacts List -->
            <div class="space-y-2 overflow-y-auto max-h-64">
                @forelse($filteredContacts as $contact)
                    <label class="flex items-center p-3 transition rounded-lg cursor-pointer hover:bg-slate-700/50">
                        <input
                            type="checkbox"
                            wire:click="toggleContact({{ $contact['id'] }})"
                            @checked(in_array($contact['id'], $selectedContacts))
                            class="w-4 h-4 text-blue-600 border-blue-700 rounded bg-slate-600 focus:ring-blue-500"
                        >
                        <div class="flex-1 ml-3">
                            <p class="font-medium text-white">{{ $contact['name'] }}</p>
                            <p class="text-sm text-gray-400">@{{ $contact['username'] }}</p>
                        </div>
                        @if(in_array($contact['id'], $selectedContacts))
                            <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                    </label>
                @empty
                    <div class="py-8 text-center">
                        <p class="text-gray-400">No contacts found</p>
                    </div>
                @endforelse
            </div>

            @if(empty($filteredContacts) && !$searchTerm && empty($contacts))
                <div class="py-8 text-center">
                    <p class="text-gray-400">You don't have any contacts yet</p>
                </div>
            @endif
        </div>

        <!-- Selected Contacts Count -->
        @if(!empty($selectedContacts))
            <div class="p-3 mb-6 border rounded-lg bg-blue-700/20 border-blue-700/30">
                <p class="text-sm text-blue-400">{{ count($selectedContacts) }} contact(s) selected</p>
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex gap-3">
            <button
                @click="$wire.close()"
                class="flex-1 py-2.5 px-4 bg-slate-700 text-white rounded-lg font-semibold hover:bg-slate-600 transition"
            >
                Cancel
            </button>
            <button
                wire:click="forwardPost"
                wire:loading.attr="disabled"
                @disabled(empty($selectedContacts) || $isForwarding)
                class="flex-1 py-2.5 px-4 bg-gradient-to-r from-blue-700 to-black text-white rounded-lg font-semibold hover:shadow-lg hover:shadow-blue-700/50 transition disabled:opacity-50 disabled:cursor-not-allowed"
            >
                <span wire:loading.remove>Forward Post</span>
                <span wire:loading>
                    <svg class="inline w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Forwarding...
                </span>
            </button>
        </div>
    </div>
    </div>
    @endif
</div>
