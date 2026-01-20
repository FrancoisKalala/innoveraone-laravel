<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Messages</title>

        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-br from-slate-950 via-blue-950 via-30% via-blue-900 via-60% to-slate-900 relative">
            <!-- Animated background elements -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-700 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse"></div>
                <div class="absolute top-20 right-1/4 w-96 h-96 bg-blue-700 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse" style="animation-delay: 2s"></div>
                <div class="absolute -bottom-8 left-1/2 w-96 h-96 bg-blue-700 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse" style="animation-delay: 4s"></div>
            </div>
            <div class="flex h-screen overflow-hidden relative z-10">
                <livewire:layout.sidebar />
                <main class="flex-1 overflow-y-auto p-4 md:p-8">
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

