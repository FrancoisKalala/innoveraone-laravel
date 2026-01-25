<?php

namespace App\Livewire\Post;

use App\Models\Post;
use App\Models\PostFile;
use App\Services\FileCompressionService;
use Livewire\Component;
use Livewire\WithFileUploads;

class PostCreateModal extends Component
{
    use WithFileUploads;

    public $show = false;
    public $mode = 'create';
    public ?Post $post = null;
    public $content = '';
    public $expirationHours = 24;
    public $interactionType = 'all';
    public $albumId = null;
    public $albums = [];
    public $newFiles = [];
    public $existingFiles = [];
    public $filesToRemove = [];
    public $showReviewModal = false;
    public $reviewFileType = null; // 'new' or 'existing'
    public $reviewFileIndex = null;
    public $showAlbumInput = false;
    public $newAlbumName = '';
    public $showScheduleInput = false;
    public $publishAt = null;
    public function createAlbum(): void
    {
        $this->validate([
            'newAlbumName' => 'required|string|min:2|max:100',
        ]);
        $album = \App\Models\Album::create([
            'user_id' => auth()->user()->id,
            'title' => $this->newAlbumName,
        ]);
        $this->albums = \App\Models\Album::where('user_id', auth()->user()->id)
            ->orderBy('title')
            ->get();
        $this->albumId = $album->id;
        $this->newAlbumName = '';
        $this->showAlbumInput = false;
    }

    protected $listeners = [
        'openPostCreateModal' => 'openCreate',
        'openPostEditModal' => 'openEdit',
    ];

    public function openCreate(): void
    {
        if (!auth()->user()) {
            return;
        }

        $this->mode = 'create';
        $this->albums = \App\Models\Album::where('user_id', auth()->user()->id)
            ->orderBy('title')
            ->get();
        $this->show = true;
    }

    public function openEdit(int $postId): void
    {
        $post = Post::with('files')->find($postId);

        if (!$post || $post->user_id !== auth()->user()->id) {
            return;
        }

        $this->mode = 'edit';
        $this->post = $post;
        $this->content = $post->content;
        $this->expirationHours = $post->expiration_hours ?? 24;
        $this->interactionType = $post->interaction_type ?? 'all';
        $this->albumId = $post->album_id;
        $this->albums = \App\Models\Album::where('user_id', auth()->user()->id)
            ->orderBy('title')
            ->get();
        $this->existingFiles = $post->files->toArray();
        $this->filesToRemove = [];
        $this->show = true;
    }

    public function removeExistingFile($fileId): void
    {
        $this->filesToRemove[] = $fileId;
        $this->existingFiles = collect($this->existingFiles)
            ->reject(fn($file) => $file['id'] === $fileId)
            ->values()
            ->toArray();
    }

    public function removeNewFile($index): void
    {
        array_splice($this->newFiles, $index, 1);
    }

    public function reviewFile($type, $index): void
    {
        $this->reviewFileType = $type;
        $this->reviewFileIndex = $index;
        $this->showReviewModal = true;
    }

    public function closeReviewModal(): void
    {
        $this->showReviewModal = false;
        $this->reviewFileType = null;
        $this->reviewFileIndex = null;
    }

    public function close(): void
    {
        $this->reset(['show', 'content', 'expirationHours', 'interactionType', 'albumId', 'albums', 'post', 'mode', 'newFiles', 'existingFiles', 'filesToRemove', 'showScheduleInput', 'publishAt']);
        $this->expirationHours = 24;
        $this->interactionType = 'all';
        $this->mode = 'create';
    }

    public function save(): void
    {
        if (!auth()->user()) {
            return;
        }

        $rules = [
            'content' => 'required|string|min:1|max:5000',
            'expirationHours' => 'nullable|integer|min:1|max:168',
            'interactionType' => 'nullable|string',
            'newFiles.*' => 'nullable|file|max:102400',
        ];
        if ($this->showScheduleInput) {
            $rules['publishAt'] = 'required|date|after:now';
        }
        $this->validate($rules);

        if ($this->mode === 'edit' && $this->post) {
            if ($this->post->user_id !== auth()->user()->id) {
                return;
            }

            $this->post->update([
                'content' => $this->content,
                'expiration_hours' => $this->expirationHours,
                'interaction_type' => $this->interactionType,
                'album_id' => $this->albumId,
                'status' => $this->showScheduleInput ? 'scheduled' : 'active',
                'publish_at' => $this->showScheduleInput ? $this->publishAt : null,
            ]);

            // Remove marked files
            foreach ($this->filesToRemove as $fileId) {
                $file = PostFile::find($fileId);
                if ($file && $file->post_id === $this->post->id) {
                    if (file_exists(storage_path('app/public/' . $file->file_path))) {
                        unlink(storage_path('app/public/' . $file->file_path));
                    }
                    $file->delete();
                }
            }

            // Add new files
            $this->uploadFiles($this->post);

            $this->dispatch('postUpdated', postId: $this->post->id);
        } else {
            $post = Post::create([
                'user_id' => auth()->user()->id,
                'content' => $this->content,
                'expiration_hours' => $this->expirationHours,
                'interaction_type' => $this->interactionType,
                'album_id' => $this->albumId,
                'status' => $this->showScheduleInput ? 'scheduled' : 'active',
                'publish_at' => $this->showScheduleInput ? $this->publishAt : null,
            ]);

            $this->uploadFiles($post);

            $this->dispatch('postCreated', postId: $post->id);
        }

        $this->close();
    }

    private function uploadFiles(Post $post): void
    {
        $compressionService = new FileCompressionService();

        foreach ($this->newFiles as $file) {
            // Compress file (WebP for images, H.264 for videos, AAC for audio)
            $compressedFile = $compressionService->compress($file);

            $path = $compressedFile->store('posts', 'public');

            PostFile::create([
                'post_id' => $post->id,
                'file_path' => $path,
                'file_type' => $compressedFile->getMimeType(),
            ]);
        }
    }

    public function render()
    {
        return view('livewire.post.post-create-modal');
    }
}
