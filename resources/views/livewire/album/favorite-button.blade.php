<button wire:click="toggleFavorite"
    class="absolute top-0 right-0 flex items-center justify-center w-12 h-12 transition-all duration-200 border-2 rounded-full shadow-xl bg-gradient-to-br from-pink-500 via-red-500 to-yellow-400 border-white/30 hover:scale-105 hover:border-pink-400 group"
    title="{{ $isFavorited ? 'Remove from Favorites' : 'Add to Favorites' }}">
    <span class="sr-only">{{ $isFavorited ? 'Remove from Favorites' : 'Add to Favorites' }}</span>
    <svg class="w-7 h-7 drop-shadow-lg transition-all duration-200 {{ $isFavorited ? 'text-black-500 scale-110 animate-pulse' : 'text-gray-300 group-hover:text-pink-400' }}"
        fill="{{ $isFavorited ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
    </svg>
    <span class="absolute right-0 z-10 px-2 py-1 text-xs text-white transition-opacity duration-200 rounded opacity-0 pointer-events-none top-14 bg-black/80 group-hover:opacity-100">
        {{ $isFavorited ? 'Favorited' : 'Add to Favorites' }}
    </span>
</button>
