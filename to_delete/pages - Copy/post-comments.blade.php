<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Comments</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="bg-slate-950 text-white pb-32">
    <div class="max-w-4xl mx-auto px-4 py-10 space-y-6 mb-8">
        <div class="p-6 rounded-2xl border border-blue-700/30 bg-slate-900/80">
            <div class="flex items-center gap-3 mb-3">
                <img src="{{ $post->user->profile_photo_path ? asset('storage/' . $post->user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($post->user->name) . '&background=3b82f6&color=fff' }}" class="w-12 h-12 rounded-full" alt="{{ $post->user->name }}">
                <div>
                    <p class="font-semibold">{{ $post->user->name }}</p>
                    <p class="text-sm text-gray-400">{{ $post->created_at->diffForHumans() }}</p>
                </div>
            </div>
            <p class="text-gray-100 text-base leading-relaxed">{{ $post->content }}</p>
        </div>

        @livewire('post.post-comments-page', ['post' => $post], key('post-comments-page-' . $post->id))

        <a href="{{ url()->previous() }}" class="inline-block px-4 py-2 rounded-lg bg-slate-800 border border-blue-700/40 text-white hover:bg-slate-700 transition">Back</a>
    </div>

    @livewireScripts
</body>
</html>
