<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Followers</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen pb-32 bg-gradient-to-br from-slate-900 via-slate-800 to-black" x-data="{ activeTab: 'followers' }">
        <div class="flex">
            <livewire:layout.sidebar />
            <main class="flex-1 mb-8 overflow-y-auto">
                <div class="max-w-6xl px-4 py-8 mx-auto">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <p class="text-sm font-semibold text-blue-400">Connections</p>
                            <h1 class="text-3xl font-bold text-white">Followers & Following</h1>
                            <p class="text-gray-400">Manage who follows you and who you follow.</p>
                        </div>
                    </div>

                    <!-- Tabs -->
                    <div class="flex gap-2 mb-6 border-b border-blue-700/20">
                        <button @click="activeTab = 'followers'" :class="activeTab === 'followers' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-white'" class="px-6 py-3 font-semibold transition">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                Followers ({{ auth()->user()->followers()->count() }})
                            </span>
                        </button>
                        <button @click="activeTab = 'following'" :class="activeTab === 'following' ? 'text-blue-400 border-b-2 border-blue-400' : 'text-gray-400 hover:text-white'" class="px-6 py-3 font-semibold transition">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.89 1.97 1.74 1.97 2.95V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/></svg>
                                Following ({{ auth()->user()->following()->count() }})
                            </span>
                        </button>
                    </div>

                    <!-- Followers List -->
                    <div x-show="activeTab === 'followers'" class="space-y-3">
                        @forelse(auth()->user()->followers()->get() as $followerRel)
                            @php
                                $user = $followerRel->follower;
                            @endphp
                            @if ($user)
                                <div class="p-4 transition duration-200 bg-white border border-gray-200 rounded-lg dark:bg-slate-800 dark:border-blue-700/30 hover:shadow-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center flex-1 space-x-4">
                                            <div class="flex items-center justify-center w-12 h-12 text-lg font-bold text-white rounded-full bg-gradient-to-br from-blue-400 to-blue-600">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $user->name }}</h3>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->username }}</p>
                                                @if (auth()->user()->isFollowing($user))
                                                    <span class="inline-flex items-center px-2 py-1 mt-1 text-xs font-medium text-green-800 bg-green-100 rounded-full dark:bg-green-900/30 dark:text-green-300">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 00-1.414 0L9 11.586 6.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l7-7a1 1 0 000-1.414z" clip-rule="evenodd" /></svg>
                                                        Following
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center ml-4 space-x-2">
                                            <a href="{{ route('user.posts', $user->id) }}" class="px-3 py-2 text-xs font-medium text-blue-600 transition rounded dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20">Posts <span class="font-semibold">{{ $user->posts()->count() }}</span></a>
                                            <a href="{{ route('user.albums', $user->id) }}" class="px-3 py-2 text-xs font-medium text-blue-600 transition rounded dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20">Albums <span class="font-semibold">{{ $user->albums()->count() }}</span></a>
                                            @if ($user->isFollowing(auth()->user()))
                                                <button class="px-3 py-2 text-xs font-medium text-white transition bg-blue-600 rounded hover:bg-blue-700 start-conversation" data-user-id="{{ $user->id }}">Message</button>
                                            @endif
                                            @if (!auth()->user()->isFollowing($user))
                                                <button class="px-3 py-2 text-xs font-medium text-white transition bg-green-600 rounded hover:bg-green-700 follow-btn" data-user-id="{{ $user->id }}" data-action="follow">Follow</button>
                                            @else
                                                <button class="px-3 py-2 text-xs font-medium text-gray-700 transition bg-gray-200 rounded dark:text-gray-300 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 follow-btn" data-user-id="{{ $user->id }}" data-action="unfollow">Unfollow</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="py-16 text-center bg-white border border-gray-200 rounded-lg dark:bg-slate-800 dark:border-blue-700/30">
                                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <p class="mt-4 text-sm font-medium text-gray-500 dark:text-gray-400">You have no followers yet</p>
                                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Start creating great content to attract followers</p>
                            </div>
                        @endforelse
                    </div>

                    <!-- Following List -->
                    <div x-show="activeTab === 'following'" class="space-y-3">
                        @forelse(auth()->user()->following()->get() as $followingRel)
                            @php
                                $user = $followingRel->following;
                                $followsBack = auth()->user()->followers()->where('follower_id', $user->id)->exists();
                            @endphp
                            @if ($user)
                                <div class="p-4 transition duration-200 bg-white border border-gray-200 rounded-lg dark:bg-slate-800 dark:border-blue-700/30 hover:shadow-lg">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center flex-1 space-x-4">
                                            <div class="flex items-center justify-center w-12 h-12 text-lg font-bold text-white rounded-full bg-gradient-to-br from-purple-400 to-purple-600">
                                                {{ strtoupper(substr($user->name, 0, 1)) }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $user->name }}</h3>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->username }}</p>
                                                @if ($followsBack)
                                                    <span class="inline-flex items-center px-2 py-1 mt-1 text-xs font-medium text-green-800 bg-green-100 rounded-full dark:bg-green-900/30 dark:text-green-300">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 00-1.414 0L9 11.586 6.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l7-7a1 1 0 000-1.414z" clip-rule="evenodd" /></svg>
                                                        Follows you
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center ml-4 space-x-2">
                                            <a href="{{ route('user.posts', $user->id) }}" class="px-3 py-2 text-xs font-medium text-blue-600 transition rounded dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20">Posts <span class="font-semibold">{{ $user->posts()->count() }}</span></a>
                                            <a href="{{ route('user.albums', $user->id) }}" class="px-3 py-2 text-xs font-medium text-blue-600 transition rounded dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20">Albums <span class="font-semibold">{{ $user->albums()->count() }}</span></a>
                                            @if (!auth()->user()->isFollowing($user))
                                                <button class="px-3 py-2 text-xs font-medium text-white transition bg-green-600 rounded hover:bg-green-700 follow-btn" data-user-id="{{ $user->id }}" data-action="follow">Follow</button>
                                            @else
                                                <button class="px-3 py-2 text-xs font-medium text-gray-700 transition bg-gray-200 rounded dark:text-gray-300 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 follow-btn" data-user-id="{{ $user->id }}" data-action="unfollow">Unfollow</button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @empty
                            <div class="py-16 text-center bg-white border border-gray-200 rounded-lg dark:bg-slate-800 dark:border-blue-700/30">
                                <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                                </svg>
                                <p class="mt-4 text-sm font-medium text-gray-500 dark:text-gray-400">You are not following anyone yet</p>
                                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Explore and follow users to stay updated</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </main>
        </div>
    </div>

    @livewireScripts
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Message button handler
            document.querySelectorAll('.start-conversation').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    const userId = this.getAttribute('data-user-id');
                    fetch('/api/conversations/create', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({ user_id: userId })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.conversation_id) {
                            window.location.href = `/conversations/${data.conversation_id}`;
                        }
                    });
                });
            });

            // Follow/Unfollow button handler - Instagram style
            document.querySelectorAll('.follow-btn').forEach(function (btn) {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    const userId = this.getAttribute('data-user-id');
                    const action = this.getAttribute('data-action');
                    const button = this;
                    const originalText = button.innerText;

                    console.log(`${action} user ${userId}`);

                    // Show loading state
                    button.disabled = true;
                    button.innerText = action === 'follow' ? 'Following...' : 'Unfollowing...';

                    fetch(`/api/follow/${userId}`, {
                        method: action === 'follow' ? 'POST' : 'DELETE',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                        },
                    })
                    .then(response => {
                        console.log(`Response status: ${response.status}`);
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Response data:', data);
                        if (data.success) {
                            if (action === 'follow') {
                                // Change to Unfollow button
                                button.setAttribute('data-action', 'unfollow');
                                button.innerText = 'Following';
                                button.className = 'text-xs font-medium text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-700 hover:bg-red-500 hover:text-white dark:hover:bg-red-600 dark:hover:text-white rounded px-3 py-2 transition follow-btn unfollow-btn';
                                console.log('Successfully followed user');
                            } else {
                                // Change to Follow button
                                button.setAttribute('data-action', 'follow');
                                button.innerText = 'Follow';
                                button.className = 'text-xs font-medium text-white bg-green-600 hover:bg-green-700 rounded px-3 py-2 transition follow-btn';
                                console.log('Successfully unfollowed user');
                            }
                        } else {
                            // Revert button on error
                            button.innerText = originalText;
                            button.disabled = false;
                            console.error('Error:', data.message);
                        }
                        button.disabled = false;
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        button.innerText = originalText;
                        button.disabled = false;
                    });
                });

                // Add hover effect for unfollow button (Instagram style)
                btn.addEventListener('mouseenter', function () {
                    if (this.getAttribute('data-action') === 'unfollow') {
                        this.innerText = 'Unfollow';
                    }
                });

                btn.addEventListener('mouseleave', function () {
                    if (this.getAttribute('data-action') === 'unfollow') {
                        this.innerText = 'Following';
                    }
                });
            });
        });
    </script>
</body>
</html>
