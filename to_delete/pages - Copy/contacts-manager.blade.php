<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacts - InnoveraOne</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-900">
    <div class="flex pb-32">
        <!-- Sidebar Navigation -->
        @livewire('layout.sidebar')

        <!-- Main Content -->
        <main class="flex-1 overflow-auto mb-8">
            @livewire('contact.contacts-manager')
        </main>
    </div>
    @livewireScripts
</body>
</html>
