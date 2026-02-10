<?php

use Illuminate\Support\Facades\Route;
use App\Models\Post;
use App\Http\Controllers\TagSearchController;
use App\Livewire\Album\AlbumManager;
use App\Livewire\Album\AlbumPost;
use App\Livewire\Contact\ContactUser;
use App\Livewire\Profile\FollowerUser;
use App\Livewire\Contact\ContactsManager;
use App\Livewire\Explore;
use App\Livewire\Feed;
use App\Livewire\Group\GroupDetail;
use App\Livewire\Message\ChatDetail;
use App\Livewire\Post\ExpiredPosts;
use App\Livewire\Post\ScheduledPosts;
use App\Livewire\Profile\FollowersManager;
use App\Livewire\User\UserPosts;
use App\Livewire\Profile\UserProfile;
use App\Models\User;

Route::view('/', 'welcome');

Route::get('dashboard', Feed::class)
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::get('/profile', App\Livewire\Profile\Profile::class)
    ->middleware(['auth'])
    ->name('profile');

Route::get('/user/{user}/profile', UserProfile::class)
    ->middleware(['auth'])
    ->name('user.profile');

Route::view('/guest-feed', 'pages.guest-feed')->name('guest.feed');

// App sections
Route::get('/messages', ChatDetail::class)
    ->middleware(['auth'])
    ->name('messages');

Route::get('/contacts-manager', ContactsManager::class)
    ->middleware(['auth'])
    ->name('contacts-manager');

Route::get('/followers-manager', FollowersManager::class)
    ->middleware(['auth'])
    ->name('followers-manager');

Route::get('/user/{user}/posts', UserPosts::class)
    ->middleware(['auth'])
    ->name('user.posts');

Route::get('/album/{album}/posts', AlbumPost::class)->name('album.posts');
Route::get('/album/{album}/view', AlbumPost::class)->name('album.view');

use App\Livewire\Album\UserAlbums;
Route::get('/user/{user}/albums', UserAlbums::class)->middleware(['auth'])->name('user.albums');
Route::get('/contact/{id}', ContactUser::class)->name('contact.user');
Route::get('/follower/{id}', FollowerUser::class)->name('follower.user');

Route::get('/groups', GroupDetail::class)
    ->middleware(['auth'])
    ->name('groups');

Route::get('/albums', AlbumManager::class)
    ->middleware(['auth'])
    ->name('albums');

Route::get('/explore', Explore::class)
    ->middleware(['auth'])
    ->name('explore');


Route::get('/scheduled-posts', ScheduledPosts::class)
    ->middleware(['auth'])
    ->name('scheduled-posts');

Route::get('/expired-posts', ExpiredPosts::class)
    ->middleware(['auth'])
    ->name('expired-posts');

Route::get('/posts/{post}/comments', \App\Livewire\Post\PostCommentsPage::class)
    ->middleware(['auth'])
    ->name('posts.comments');

Route::get('/search', [TagSearchController::class, 'index'])->name('search.tag');

require __DIR__.'/auth.php';
