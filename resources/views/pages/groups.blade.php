<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Groups</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-black pb-32">
            <div class="flex">
                <livewire:layout.sidebar />
                <main class="flex-1 overflow-y-auto p-4 md:p-8 mb-8">
                    <div class="max-w-7xl mx-auto">
                        <h1 class="text-4xl font-bold text-white mb-8 flex items-center gap-3">
                            <svg class="w-8 h-8 text-purple-400" fill="currentColor" viewBox="0 0 24 24"><path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            Groups
                        </h1>
                        @livewire('group.group-detail')
                    </div>
                </main>
            </div>
        </div>
        @livewireScripts
    </body>
</html>

