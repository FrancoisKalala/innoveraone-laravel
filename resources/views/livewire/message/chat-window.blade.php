<div class="h-full flex flex-col bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900">
    <div class="flex flex-1 overflow-hidden gap-4 p-4">
        <div class="w-full md:w-72 bg-slate-800/50 rounded-2xl border border-blue-500/20 flex flex-col overflow-hidden">
            <div class="p-4 border-b border-blue-500/20">
                <div class="relative">
                    <input type="text" placeholder="Search conversations..." class="w-full px-4 py-2 pl-10 rounded-lg bg-slate-700 text-white placeholder-gray-500 border border-blue-500/20 focus:border-blue-700 focus:outline-none transition text-sm">
                    <svg class="w-4 h-4 absolute left-3 top-2.5 text-gray-500" fill="currentColor" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
                </div>
            </div>
            <div class="flex-1 overflow-y-auto space-y-1 p-2">
                @forelse($conversations as $conv)<button wire:click="selectConversation({{ $conv->contact_id }})" class="w-full text-left p-3 rounded-lg transition flex items-center gap-3 {{ $selectedConversation === $conv->contact_id ? 'bg-gradient-to-r from-blue-700 to-black' : 'hover:bg-slate-700' }}"><img src="{{ $conv->contact->profile_photo_path ? asset('storage/' . $conv->contact->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($conv->contact->name) . '&background=8b5cf6&color=fff' }}" alt="{{ $conv->contact->name }}" class="w-10 h-10 rounded-full object-cover flex-shrink-0"><div class="flex-1 min-w-0"><p class="font-semibold text-white truncate text-sm">{{ $conv->contact->name }}</p><p class="text-xs {{ $selectedConversation === $conv->contact_id ? 'text-white/70' : 'text-gray-400' }} truncate">@{{ $conv->contact->username }}</p></div></button>@empty<div class="text-center py-8 text-gray-400"><p class="text-sm">No conversations yet</p></div>@endforelse
            </div>
        </div>
        @if($selectedConversation)<div class="flex-1 bg-slate-800/50 rounded-2xl border border-blue-500/20 flex flex-col overflow-hidden">
            <div class="bg-slate-700/50 border-b border-blue-500/20 p-4 flex items-center gap-3">
                <img src="{{ $contact->profile_photo_path ? asset('storage/' . $contact->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($contact->name) . '&background=8b5cf6&color=fff' }}" alt="{{ $contact->name }}" class="w-10 h-10 rounded-full object-cover">
                <div class="flex-1"><h3 class="font-bold text-white">{{ $contact->name }}</h3><p class="text-xs text-gray-400">@{{ $contact->username }}</p></div>
                <button class="p-2 hover:bg-slate-700 rounded-lg transition"><svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 24 24"><path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/></svg></button>
            </div>
            <div class="flex-1 overflow-y-auto p-4 space-y-3">@forelse($messages as $message)<div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}"><div class="max-w-xs px-4 py-2 rounded-2xl {{ $message->sender_id === auth()->id() ? 'bg-gradient-to-r from-blue-700 to-black text-white' : 'bg-slate-700 text-gray-100' }}"><p class="text-sm">{{ $message->content }}</p><p class="text-xs {{ $message->sender_id === auth()->id() ? 'text-white/70' : 'text-gray-500' }} mt-1">{{ $message->created_at->format('H:i') }}@if($message->sender_id === auth()->id() && $message->is_read)✓✓@endif</p></div></div>@empty<div class="text-center py-12 text-gray-400"><p>👋 Start a conversation</p></div>@endforelse</div>
            <div class="bg-slate-700/50 border-t border-blue-500/20 p-4">
                <form wire:submit.prevent="sendMessage" class="flex gap-3">
                    <input type="text" wire:model="messageContent" placeholder="Type a message..." class="flex-1 px-4 py-2 rounded-full bg-slate-800 text-white placeholder-gray-500 border border-blue-500/20 focus:border-blue-700 focus:outline-none transition">
                    <button type="submit" class="p-2 bg-gradient-to-r from-blue-700 to-black text-white rounded-full hover:shadow-lg transition"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M16.6915026,12.4744748 L3.50612381,13.2599618 C3.19218622,13.2599618 3.03521743,13.4170592 3.03521743,13.5741566 L1.15159189,20.0151496 C0.8376543,20.8006365 0.99,21.89 1.77946707,22.52 C2.41,22.99 3.50612381,23.1 4.13399899,22.8429026 L21.714504,14.0454487 C22.6563168,13.5741566 23.1272231,12.6315722 22.9702544,11.6889879 L4.13399899,1.16346272 C3.34915502,0.9 2.40734225,1.00636533 1.77946707,1.4776575 C0.994623095,2.10604706 0.837654326,3.0486314 1.15159189,3.99021575 L3.03521743,10.4310088 C3.03521743,10.5881061 3.34915502,10.7452035 3.50612381,10.7452035 L16.6915026,11.5306905 C16.6915026,11.5306905 17.1624089,11.5306905 17.1624089,12.0019827 C17.1624089,12.4744748 16.6915026,12.4744748 16.6915026,12.4744748 Z"/></svg></button>
                </form>
            </div>
        </div>@else<div class="flex-1 bg-slate-800/50 rounded-2xl border border-blue-500/20 flex items-center justify-center"><div class="text-center"><div class="w-20 h-20 bg-gradient-to-br from-blue-700 to-black rounded-full mx-auto mb-4 opacity-20"></div><p class="text-gray-400">Select a conversation to start chatting</p></div></div>@endif
    </div>
</div>

