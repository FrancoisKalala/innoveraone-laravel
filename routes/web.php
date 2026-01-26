<?php

use Illuminate\Support\Facades\Route;
use App\Models\Post;
use App\Http\Controllers\TagSearchController;

Route::view('/', 'welcome');

Route::view('dashboard', 'pages.dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'pages.profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/guest-feed', \App\Livewire\GuestFeed::class)->name('guest.feed');

// App sections
Route::view('/messages', 'pages.messages')
    ->middleware(['auth'])
    ->name('messages');

Route::view('/contacts-manager', 'pages.contacts-manager')
    ->middleware(['auth'])
    ->name('contacts-manager');

Route::view('/contacts', 'pages.contacts')
    ->middleware(['auth'])
    ->name('contacts');

Route::view('/followers-manager', 'pages.followers-manager')
    ->middleware(['auth'])
    ->name('followers-manager');

Route::get('/user/{user}/posts', function (\App\Models\User $user) {
    return view('pages.user-posts', ['user' => $user]);
})->middleware(['auth'])->name('user.posts');

Route::get('/user/{user}/albums', function (\App\Models\User $user) {
    return view('pages.user-albums', ['user' => $user]);
})->middleware(['auth'])->name('user.albums');

Route::view('/groups', 'pages.groups')
    ->middleware(['auth'])
    ->name('groups');

Route::view('/albums', 'pages.albums')
    ->middleware(['auth'])
    ->name('albums');

Route::view('/explore', 'pages.explore')
    ->middleware(['auth'])
    ->name('explore');

Route::view('/expired-posts', 'pages.expired-posts')
    ->middleware(['auth'])
    ->name('expired-posts');

Route::get('/posts/{post}/comments', function (Post $post) {
    $post->load(['user', 'comments.user']);
    return view('pages.post-comments', [
        'post' => $post,
        'comments' => $post->comments()->latest()->paginate(15),
    ]);
})->middleware(['auth'])->name('posts.comments');

Route::get('/search', [TagSearchController::class, 'index'])->name('search.tag');

require __DIR__.'/auth.php';
