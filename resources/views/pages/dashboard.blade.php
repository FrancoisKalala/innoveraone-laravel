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
    <div class="flex bg-gradient-to-br from-slate-900 via-slate-800 to-black pb-32">
        <!-- Sidebar -->
        <livewire:layout.sidebar />

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto mb-8">
            @livewire('feed', ['album' => request('album')])
        </main>
    </div>
    @livewireScripts
</body>
</html>


