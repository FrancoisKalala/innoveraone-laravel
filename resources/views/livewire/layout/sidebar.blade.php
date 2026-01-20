<?php

use Livewire\Volt\Component;

new class extends Component
{
    public $collapsed = false;
    public array $items = [];

    public function toggle(): void
    {
        $this->collapsed = ! $this->collapsed;
    }

    public function mount(): void
    {
        $user = auth()->user();

        $this->items = [
            [
                'label' => 'Feed',
                'url' => route('dashboard'),
                'active' => request()->routeIs('dashboard'),
                'icon' => 'home',
                'badge' => null,
            ],
            [
                'label' => 'Explore',
                'url' => route('explore'),
                'active' => request()->routeIs('explore'),
                'icon' => 'search',
                'badge' => null,
            ],
            [
                'label' => 'Messages',
                'url' => route('messages'),
                'active' => request()->routeIs('messages'),
                'icon' => 'chat',
                'badge' => null,
            ],
            [
                'label' => 'Contacts',
                'url' => route('contacts'),
                'active' => request()->routeIs('contacts'),
                'icon' => 'users',
                'badge' => method_exists($user, 'contacts') ? $user->contacts()->count() : null,
            ],
            [
                'label' => 'Groups',
                'url' => route('groups'),
                'active' => request()->routeIs('groups'),
                'icon' => 'group',
                'badge' => method_exists($user, 'groups') ? $user->groups()->count() : null,
            ],
            [
                'label' => 'Albums',
                'url' => route('albums'),
                'active' => request()->routeIs('albums*'),
                'icon' => 'book',
                'badge' => method_exists($user, 'chapters') ? $user->chapters()->count() : null,
            ],
            [
                'label' => 'Profile',
                'url' => route('profile'),
                'active' => request()->routeIs('profile'),
                'icon' => 'user',
                'badge' => null,
            ],
        ];
    }
}; ?>

<aside class="{{ $collapsed ? 'w-16' : 'w-64' }} bg-slate-900/80 backdrop-blur-xl border-r border-blue-700/20 flex flex-col transition-all duration-200">
    <!-- Header -->
    <div class="p-4 border-b border-blue-700/20">
        <div class="flex items-center justify-between gap-3">
            <div class="w-10 h-10 bg-gradient-to-br from-blue-700 to-black rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/50">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm0-13c-2.76 0-5 2.24-5 5s2.24 5 5 5 5-2.24 5-5-2.24-5-5-5z"/>
                </svg>
            </div>
            @if (! $collapsed)
                <h1 class="text-xl font-bold bg-gradient-to-r from-blue-600 to-black bg-clip-text text-transparent">InnoveraOne</h1>
            @endif
            <button wire:click="toggle" class="ml-auto p-2 rounded-lg text-gray-300 hover:text-white hover:bg-white/5" aria-label="Toggle sidebar">
                @if ($collapsed)
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12h16M14 6l6 6-6 6"/></svg>
                @else
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4m6 6l-6-6 6-6"/></svg>
                @endif
            </button>
        </div>
    </div>

    <!-- Menu -->
    <nav class="flex-1 p-4 space-y-2">
        @foreach ($items as $item)
            <a href="{{ $item['url'] }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition
                {{ $item['active'] ? 'bg-gradient-to-r from-blue-700 to-black text-white shadow-lg shadow-blue-500/30' : 'text-gray-300 hover:bg-slate-800/50 hover:text-white' }}">
                @switch($item['icon'])
                    @case('home')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        @break
                    @case('search')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        @break
                    @case('chat')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        @break
                    @case('users')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                        @break
                    @case('group')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        @break
                    @case('book')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        @break
                    @case('user')
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        @break
                @endswitch
                @if (! $collapsed)
                    <span class="font-medium">{{ $item['label'] }}</span>
                    @if (!is_null($item['badge']))
                        <span class="ml-auto px-2 py-0.5 text-xs rounded-lg bg-white/10 border border-blue-700/30 text-blue-200">
                            {{ $item['badge'] }}
                        </span>
                    @endif
                @endif
            </a>
        @endforeach
    </nav>

    <!-- User Info & Logout -->
    <div class="p-4 border-t border-blue-700/20">
        <div class="flex items-center gap-3 mb-3 px-2">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-700 to-black flex items-center justify-center text-white font-bold">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            @if (! $collapsed)
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ auth()->user()->email }}</p>
                </div>
            @endif
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 rounded-xl bg-red-500/10 text-red-400 hover:bg-red-500/20 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                @if (! $collapsed)
                    <span class="text-sm font-medium">Logout</span>
                @endif
            </button>
        </form>
    </div>
</aside>

