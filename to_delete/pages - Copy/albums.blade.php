<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Albums</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-black pb-32">
            <div class="flex">
                <livewire:layout.sidebar />
                <main class="flex-1 overflow-y-auto p-4 md:p-8 mb-8">
                    <div class="max-w-6xl mx-auto">
                        @if(request('album'))
                            @livewire('album.album-detail', ['albumId' => request('album')])
                        @else
                            @livewire('album.album-manager')
                        @endif
                    </div>
                </main>
            </div>
        </div>
        @livewireScripts
    </body>
</html>

