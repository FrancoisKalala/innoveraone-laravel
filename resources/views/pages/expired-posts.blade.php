<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Expired Posts') }}
        </h2>
    </x-slot>

    <livewire:layout.sidebar />

    <div class="py-12 pb-32">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @livewire('post.expired-posts')
        </div>
    </div>
</x-app-layout>
