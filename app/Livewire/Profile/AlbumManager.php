<?php

namespace App\Livewire\Profile;

use App\Models\Album;
use Livewire\Component;
use Livewire\WithFileUploads;

class AlbumManager extends Component
{
    use WithFileUploads;

    public $albums;
    public $profilePhoto;
    public $coverPhoto;
    public $showUploadForm = false;

    public function mount()
    {
        $this->loadAlbums();
    }

    public function loadAlbums()
    {
        $this->albums = auth()->user()->albums()->paginate(20);
    }

    public function uploadProfilePhoto()
    {
        $this->validate([
            'profilePhoto' => 'required|image|max:5120',
        ]);

        $path = $this->profilePhoto->store('albums/profile', 'public');

        Album::create([
            'user_id' => auth()->id(),
            'type' => 'profile',
            'file_name' => $this->profilePhoto->getClientOriginalName(),
            'file_path' => $path,
        ]);

        auth()->user()->update([
            'profile_photo_path' => $path,
        ]);

        $this->reset('profilePhoto');
        $this->loadAlbums();
        session()->flash('success', 'Profile photo uploaded!');
    }

    public function uploadCoverPhoto()
    {
        $this->validate([
            'coverPhoto' => 'required|image|max:5120',
        ]);

        $path = $this->coverPhoto->store('albums/cover', 'public');

        Album::create([
            'user_id' => auth()->id(),
            'type' => 'cover',
            'file_name' => $this->coverPhoto->getClientOriginalName(),
            'file_path' => $path,
        ]);

        auth()->user()->update([
            'cover_photo_path' => $path,
        ]);

        $this->reset('coverPhoto');
        $this->loadAlbums();
        session()->flash('success', 'Cover photo uploaded!');
    }

    public function deleteAlbum($albumId)
    {
        $album = Album::find($albumId);

        if ($album && $album->user_id === auth()->id()) {
            $album->delete();
            $this->loadAlbums();
            session()->flash('success', 'Album deleted!');
        }
    }

    public function render()
    {
        return view('livewire.profile.album-manager');
    }
}
