<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Messages</title>

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
                            <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM6 9h12v2H6V9zm13 8H6v-2h13v2zm0-4H6v-2h13v2z"/></svg>
                            Messages
                        </h1>
                        @livewire('message.chat-detail')
                    </div>
                </main>
            </div>
        </div>
        @livewireScripts
    </body>
</html>

