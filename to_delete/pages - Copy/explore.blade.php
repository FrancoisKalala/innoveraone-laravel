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
<body class="font-sans antialiased bg-slate-900">
    <div class="flex pb-32">
        <livewire:layout.sidebar />
        <main class="flex-1 mb-8 overflow-auto border shadow-2xl backdrop-blur-xl rounded-2xl border-blue-700/20" style="scrollbar-width: thin; scrollbar-color: rgba(168, 85, 247, 0.5) transparent;">
            @livewire('explore')
        </main>
    </div>
    @livewireScripts
</body>
</html>

