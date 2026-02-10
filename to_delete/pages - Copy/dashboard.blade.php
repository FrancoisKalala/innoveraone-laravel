<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- App assets via Vite (Tailwind + Alpine) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="antialiased">
    <div class="flex pb-32 bg-gradient-to-br from-slate-900 via-slate-800 to-black">
        <!-- Sidebar -->
        <livewire:layout.sidebar />

        <!-- Main Content -->
        <main class="flex-1 mb-8 overflow-y-auto border shadow-2xl backdrop-blur-xl rounded-2xl border-blue-700/20" style="scrollbar-width: thin; scrollbar-color: rgba(168, 85, 247, 0.5) transparent;">
            @livewire('feed', ['album' => request('album')])
        </main>
    </div>
    @livewireScripts
</body>
</html>


