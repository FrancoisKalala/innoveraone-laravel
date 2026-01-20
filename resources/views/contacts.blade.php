<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Contacts</title>

        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-br from-slate-950 via-purple-950 via-30% via-pink-950 via-60% to-slate-900 relative">
            <!-- Animated background elements -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute top-0 left-1/4 w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse"></div>
                <div class="absolute top-20 right-1/4 w-96 h-96 bg-pink-600 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse" style="animation-delay: 2s"></div>
                <div class="absolute -bottom-8 left-1/2 w-96 h-96 bg-purple-600 rounded-full mix-blend-multiply filter blur-3xl opacity-10 animate-pulse" style="animation-delay: 4s"></div>
            </div>
            <div class="flex relative z-10">
                <livewire:layout.sidebar />
                <main class="flex-1 overflow-y-auto p-4 md:p-8">
                    <div class="max-w-7xl mx-auto">
                        <h1 class="text-4xl font-bold text-white mb-8 flex items-center gap-3">
                            <svg class="w-8 h-8 text-purple-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                            Contacts
                        </h1>
                        @livewire('contact.contact-detail')
                    </div>
                </main>
            </div>
        </div>
        @livewireScripts
    </body>
</html>

