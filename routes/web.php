<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

Route::get('/guest-feed', \App\Livewire\GuestFeed::class)->name('guest.feed');

// App sections
Route::view('/messages', 'messages')
    ->middleware(['auth'])
    ->name('messages');

Route::view('/contacts', 'contacts')
    ->middleware(['auth'])
    ->name('contacts');

Route::view('/groups', 'groups')
    ->middleware(['auth'])
    ->name('groups');

Route::view('/albums', 'albums')
    ->middleware(['auth'])
    ->name('albums');

Route::get('/explore', \App\Livewire\Explore::class)
    ->middleware(['auth'])
    ->name('explore');

Route::view('/expired-posts', 'expired-posts')
    ->middleware(['auth'])
    ->name('expired-posts');

require __DIR__.'/auth.php';
