<?php

namespace App\Livewire\Post;

use App\Models\Post;
use App\Services\FileCompressionService;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePost extends Component
{
    use WithFileUploads;

    public $content = '';
    public $album_id;
    public $albumId = null;
    public $files = [];
    public $expiration_hours = 24;
    public $interaction_type = 'all';
    public $albums;
    public $showReviewModal = false;
    public $reviewFileIndex = null;
    public $showAlbumInput = false;
    public $newAlbumName = '';
    public function createAlbum(): void
    {
        $this->validate([
            'newAlbumName' => 'required|string|min:2|max:100',
        ]);
        $album = \App\Models\Album::create([
            'user_id' => auth()->user()->id,
            'title' => $this->newAlbumName,
        ]);
        $this->albums = auth()->user()->albums()->get();
        $this->album_id = $album->id;
        $this->newAlbumName = '';
        $this->showAlbumInput = false;
    }

    public function mount($albumId = null)
    {
        $this->albums = auth()->user()->albums()->get();
        if ($albumId) {
            $this->album_id = $albumId;
        }
    }

    public function removeFile($index): void
    {
        array_splice($this->files, $index, 1);
    }

    public function reviewFile($index): void
    {
        $this->reviewFileIndex = $index;
        $this->showReviewModal = true;
    }

    public function closeReviewModal(): void
    {
        $this->showReviewModal = false;
        $this->reviewFileIndex = null;
    }

    public function createPost()
    {
        $this->validate([
            'content' => 'required|string|min:1|max:5000',
            'album_id' => 'nullable|exists:albums,id',
            'files' => 'nullable|array|max:10',
            'files.*' => 'file|max:51200',
            'expiration_hours' => 'required|integer|min:1|max:720',
            'interaction_type' => 'required|in:like,comment,like_comment,all,none',
        ]);

        $post = auth()->user()->posts()->create([
            'content' => $this->content,
            'album_id' => $this->album_id,
            'expiration_hours' => $this->expiration_hours,
            'interaction_type' => $this->interaction_type,
            'status' => 'published',
        ]);

        // Handle file uploads with professional compression
        $compressionService = new FileCompressionService();
        foreach ($this->files as $file) {
            // Compress file (WebP for images, H.264 for videos, AAC for audio)
            $compressedFile = $compressionService->compress($file);

            $path = $compressedFile->store('posts', 'public');
            $post->files()->create([
                'file_path' => $path,
                'file_type' => $compressedFile->getMimeType(),
            ]);
        }

        $this->reset(['content', 'album_id', 'files', 'expiration_hours', 'interaction_type']);
        $this->dispatch('postCreated', postId: $post->id);
        $this->dispatch('close-modal');
        session()->flash('success', 'Post created successfully!');
    }

    public function render()
    {
        return view('livewire.post.create-post');
    }
}
