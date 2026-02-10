<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }} - Expired Posts</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-900 font-sans antialiased">
    <div class="flex pb-32">
        <livewire:layout.sidebar />
        <main class="flex-1 overflow-auto mb-8">
            @livewire('post.expired-posts')
        </main>
    </div>
    @livewireScripts
</body>
</html>
