<?php

use Livewire\Volt\Component;

new class extends Component
{
}; ?>

<div x-data="{ showCreatePost: false }" @close-modal.window="showCreatePost = false">
    <!-- Action Buttons (top-right) - Global -->
    <div class="fixed top-6 right-6 z-50 flex flex-col gap-4">
        <!-- Profile Button -->
        <a href="{{ route('profile') }}" class="w-14 h-14 bg-gradient-to-r from-purple-700 to-black rounded-full shadow-2xl shadow-purple-700/50 hover:shadow-purple-700/70 hover:scale-110 transition-all duration-300 flex items-center justify-center group" aria-label="Profile">
            <svg class="w-7 h-7 text-white group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        </a>

        <!-- Create Post Button -->
        <button @click="showCreatePost = true" class="w-14 h-14 bg-gradient-to-r from-blue-700 to-black rounded-full shadow-2xl shadow-blue-700/50 hover:shadow-blue-700/70 hover:scale-110 transition-all duration-300 flex items-center justify-center group">
            <svg class="w-7 h-7 text-white group-hover:rotate-90 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        </button>


        <!-- Album Button -->
        <a href="{{ route('albums') }}" class="w-14 h-14 bg-gradient-to-r from-indigo-700 to-black rounded-full shadow-2xl shadow-indigo-700/50 hover:shadow-indigo-700/70 hover:scale-110 transition-all duration-300 flex items-center justify-center group" aria-label="Albums">
            <svg class="w-7 h-7 text-white group-hover:rotate-12 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </a>

        <!-- Unread Notifications Button -->
        @livewire('notification.unread-notifications')

        <!-- Create Post Modal -->
        <div x-show="showCreatePost"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4"
             style="display: none;">
            <div @click="showCreatePost = false" class="fixed inset-0 bg-black/50 backdrop-blur-sm"></div>
            <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl border border-blue-700/30 max-w-4xl w-full p-6 relative z-10 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17 10.5V7c0-.55-.45-1-1-1H4c-.55 0-1 .45-1 1v10c0 .55.45 1 1 1h12c.55 0 1-.45 1-1v-3.5l4 4v-11l-4 4z"/></svg>
                        Create New Post
                    </h2>
                    <button @click="showCreatePost = false" class="text-gray-400 hover:text-white transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                <div>
                    @livewire('post.create-post', key('global-create-post'))
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Tab Bar (global navigation) -->
    <nav class="fixed inset-x-0 bottom-0 z-40 bg-slate-900/85 backdrop-blur-md border-t border-white/10">
        <div class="max-w-4xl mx-auto px-4 py-2 flex items-center justify-center gap-8 text-sm font-semibold text-gray-300">
            <a href="{{ route('dashboard') }}" class="group flex flex-col items-center gap-2 transition-all duration-200 {{ request()->routeIs('dashboard', 'dashboard.*') ? 'text-white' : 'hover:text-white' }}" aria-label="Home">
                <div class="w-14 h-14 rounded-full flex items-center justify-center transition-all duration-200 {{ request()->routeIs('dashboard', 'dashboard.*') ? 'bg-gradient-to-r from-blue-700 to-black border border-blue-500/50 shadow-2xl shadow-blue-700/40 scale-105' : 'bg-slate-800/60 border border-white/5 group-hover:bg-slate-700/70 group-hover:scale-105' }}">
                    <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M3 12l2-2 7-7 7 7 2 2v9a1 1 0 01-1 1h-5v-6H9v6H4a1 1 0 01-1-1z"/></svg>
                </div>
                <span class="text-xs">Home</span>
            </a>

            <a href="{{ route('explore') }}" class="group flex flex-col items-center gap-2 transition-all duration-200 {{ request()->routeIs('explore', 'explore.*') ? 'text-white' : 'hover:text-white' }}" aria-label="Explore">
                <div class="w-14 h-14 rounded-full flex items-center justify-center transition-all duration-200 {{ request()->routeIs('explore', 'explore.*') ? 'bg-gradient-to-r from-blue-700 to-black border border-blue-500/50 shadow-2xl shadow-blue-700/40 scale-105' : 'bg-slate-800/60 border border-white/5 group-hover:bg-slate-700/70 group-hover:scale-105' }}">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m21 21-4.35-4.35M10 18a8 8 0 1 1 0-16 8 8 0 0 1 0 16z"/></svg>
                </div>
                <span class="text-xs">Explore</span>
            </a>

            <a href="{{ route('messages') }}" class="group flex flex-col items-center gap-2 transition-all duration-200 {{ request()->routeIs('messages', 'messages.*') ? 'text-white' : 'hover:text-white' }}" aria-label="Messages">
                <div class="w-14 h-14 rounded-full flex items-center justify-center transition-all duration-200 {{ request()->routeIs('messages', 'messages.*') ? 'bg-gradient-to-r from-blue-700 to-black border border-blue-500/50 shadow-2xl shadow-blue-700/40 scale-105' : 'bg-slate-800/60 border border-white/5 group-hover:bg-slate-700/70 group-hover:scale-105' }}">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                </div>
                <span class="text-xs">Messages</span>
            </a>

            <a href="{{ route('contacts-manager') }}" class="group flex flex-col items-center gap-2 transition-all duration-200 {{ request()->routeIs('contacts-manager', 'contacts-manager.*') ? 'text-white' : 'hover:text-white' }}" aria-label="Contacts">
                <div class="w-14 h-14 rounded-full flex items-center justify-center transition-all duration-200 {{ request()->routeIs('contacts-manager', 'contacts-manager.*') ? 'bg-gradient-to-r from-blue-700 to-black border border-blue-500/50 shadow-2xl shadow-blue-700/40 scale-105' : 'bg-slate-800/60 border border-white/5 group-hover:bg-slate-700/70 group-hover:scale-105' }}">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <span class="text-xs">Contacts</span>
            </a>

            <a href="{{ route('groups') }}" class="group flex flex-col items-center gap-2 transition-all duration-200 {{ request()->routeIs('groups', 'groups.*') ? 'text-white' : 'hover:text-white' }}" aria-label="Groups">
                <div class="w-14 h-14 rounded-full flex items-center justify-center transition-all duration-200 {{ request()->routeIs('groups', 'groups.*') ? 'bg-gradient-to-r from-blue-700 to-black border border-blue-500/50 shadow-2xl shadow-blue-700/40 scale-105' : 'bg-slate-800/60 border border-white/5 group-hover:bg-slate-700/70 group-hover:scale-105' }}">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 12H9m6 0a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <span class="text-xs">Groups</span>
            </a>
        </div>
    </nav>
</div>

