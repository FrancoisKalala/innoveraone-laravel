<div class="relative">
    <button wire:click="toggleShow" class="w-14 h-14 bg-gradient-to-r from-pink-700 to-black rounded-full shadow-2xl shadow-pink-700/50 hover:shadow-pink-700/70 hover:scale-110 transition-all duration-300 flex items-center justify-center group" aria-label="Notifications">
        <svg class="w-7 h-7 text-white group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        @if(count($notifications) > 0)
            <span class="absolute top-2 right-2 bg-red-500 text-white text-xs rounded-full px-2 py-0.5">{{ count($notifications) }}</span>
        @endif
    </button>
    @if($show)
        <div class="absolute right-0 mt-2 w-80 bg-slate-800 border border-pink-700/30 rounded-xl shadow-lg z-50 max-h-96 overflow-y-auto">
            <div class="p-4">
                <h3 class="text-lg font-bold text-white mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5 text-pink-400" fill="currentColor" viewBox="0 0 24 24"><path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V4a2 2 0 10-4 0v1.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    Unread Notifications
                </h3>
                @if(count($notifications) === 0)
                    <div class="text-gray-400 text-center py-8">No unread notifications</div>
                @else
                    <ul class="divide-y divide-slate-700">
                        @foreach($notifications as $notification)
                            <li class="py-3 flex items-start gap-3">
                                <div class="flex-1">
                                    <div class="text-white">{{ $notification->message }}</div>
                                    <div class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</div>
                                </div>
                                <button wire:click="markAsRead({{ $notification->id }})" class="ml-2 px-3 py-1 text-xs bg-pink-700/70 text-white rounded-lg hover:bg-pink-800 transition">Mark as read</button>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    @endif
</div>
