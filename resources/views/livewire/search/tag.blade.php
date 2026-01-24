@extends('layouts.app')

@section('content')
    <div class="max-w-3xl py-8 mx-auto">
        <h1 class="mb-6 text-2xl font-bold text-blue-600">Posts with #{{ $tag }}</h1>
        @if($posts->count())
            <div class="space-y-6">
                @foreach($posts as $post)
                    @livewire('post.post-card', ['post' => $post], key($post->id))
                @endforeach
            </div>
            <div class="mt-6">{{ $posts->links() }}</div>
        @else
            <div class="text-lg text-gray-500">No posts found for this hashtag.</div>
        @endif
    </div>
@endsection
