<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Explore</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased">
    <livewire:layout.sidebar />
    <div class="min-h-screen bg-gradient-to-br from-slate-900 via-slate-800 to-black pb-32">
        <main class="flex-1 overflow-y-auto max-w-7xl mx-auto px-4 py-6 mb-8">
            {{ $slot }}
        </main>
    </div>
    @livewireScripts
</body>
</html>

