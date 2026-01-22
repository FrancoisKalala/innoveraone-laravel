<?php

namespace App\Livewire\Album;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Album;
use App\Enums\AlbumCategory;

class AlbumManager extends Component
{
    public $albums;
    public $showCreateModal = false;
    public $isEditing = false;
    public $editingId = null;
    public $title = '';
    public $description = '';
    public $visibility = 'public';
    public $category = '';
    public $customCategory = '';
    public $useCustomCategory = false;
    public $slug = '';
    public $searchQuery = '';
    public $recentSearches = [];

    public function mount(): void
    {
        $this->recentSearches = session()->get($this->recentSearchSessionKey(), []);
        $this->loadAlbums();
        // If arriving with an edit query param, open the edit modal
        if (request()->has('edit')) {
            $editId = (int) request()->query('edit');
            if ($editId > 0) {
                $this->startEdit($editId);
            }
        }
        // Deep-link create modal fallback
        if (request()->has('create')) {
            $this->openCreateModal();
        }
    }

    public function updatedSearchQuery()
    {
        $this->loadAlbums();
        if (!empty($this->searchQuery)) {
            $this->addRecentSearch($this->searchQuery);
        }
    }

    public function useRecentSearch($index)
    {
        if (!isset($this->recentSearches[$index])) {
            return;
        }
        $term = $this->recentSearches[$index];
        $this->searchQuery = $term;
        $this->addRecentSearch($term);
        $this->loadAlbums();
    }

    protected function addRecentSearch($term)
    {
        $term = trim($term);
        if (mb_strlen($term) < 2) {
            return;
        }
        $filtered = collect($this->recentSearches)
            ->filter(fn($existing) => strcasecmp($existing, $term) !== 0)
            ->values();
        $this->recentSearches = collect([$term])
            ->merge($filtered)
            ->take(10)
            ->values()
            ->toArray();
        session()->put($this->recentSearchSessionKey(), $this->recentSearches);
    }

    protected function recentSearchSessionKey(): string
    {
        $userId = auth()->id();
        return 'recent_searches_albums_' . ($userId ?: 'guest');
    }

    public function loadAlbums(): void
    {
        $userId = Auth::id();
        $this->albums = Album::withCount(['posts', 'views', 'favorites'])
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get();
    }

    public function openCreateModal()
    {
        $this->showCreateModal = true;
        $this->reset(['title', 'description', 'visibility', 'category', 'customCategory', 'useCustomCategory', 'isEditing', 'editingId', 'slug']);
        $this->visibility = 'public';
    }

    public function closeCreateModal()
    {
        $this->showCreateModal = false;
        $this->reset(['title', 'description', 'visibility', 'category', 'customCategory', 'useCustomCategory', 'isEditing', 'editingId', 'slug']);
    }

    public function createAlbum()
    {
        $this->validate([
            'title' => 'required|string|min:3|max:100',
            'description' => 'nullable|string|max:500',
            'visibility' => 'required|in:public,private',
            'category' => $this->useCustomCategory ? 'nullable' : 'nullable|string|max:50',
            'customCategory' => $this->useCustomCategory ? 'required|string|max:50' : 'nullable',
        ]);

        $finalCategory = $this->useCustomCategory ? $this->customCategory : $this->category;

        Album::create([
            'user_id' => Auth::id(),
            'title' => $this->title,
            'description' => $this->description,
            'slug' => Str::slug($this->title . '-' . uniqid()),
            'visibility' => $this->visibility,
            'category' => $finalCategory,
        ]);

        $this->closeCreateModal();
        $this->loadAlbums();
        session()->flash('success', 'Album created successfully!');
    }

    public function startEdit(int $albumId): void
    {
        $album = Album::where('id', $albumId)->where('user_id', Auth::id())->first();
        if (!$album) {
            session()->flash('error', 'Album not found or not yours.');
            return;
        }

        $this->isEditing = true;
        $this->editingId = $album->id;
        $this->title = $album->title;
        $this->description = $album->description;
        $this->visibility = $album->visibility ?? 'public';
        // Populate category into either predefined or custom field
        $this->useCustomCategory = false;
        $this->category = $album->category ?? '';
        $this->customCategory = '';
        $this->slug = $album->slug ?? '';

        $this->showCreateModal = true;
    }

    public function updateAlbum(): void
    {
        if (!$this->isEditing || !$this->editingId) {
            return;
        }

        $this->validate([
            'title' => 'required|string|min:3|max:100',
            'description' => 'nullable|string|max:500',
            'visibility' => 'required|in:public,private',
            'category' => $this->useCustomCategory ? 'nullable' : 'nullable|string|max:50',
            'customCategory' => $this->useCustomCategory ? 'required|string|max:50' : 'nullable',
            'slug' => 'nullable|string|min:3|max:191|unique:albums,slug,' . $this->editingId,
        ]);

        $finalCategory = $this->useCustomCategory ? $this->customCategory : $this->category;

        $album = Album::where('id', $this->editingId)->where('user_id', Auth::id())->first();
        if (!$album) {
            session()->flash('error', 'Album not found or not yours.');
            return;
        }

        $album->update([
            'title' => $this->title,
            'description' => $this->description,
            'visibility' => $this->visibility,
            'category' => $finalCategory,
            'slug' => $this->slug ? Str::slug($this->slug) : $album->slug,
        ]);

        $this->closeCreateModal();
        $this->loadAlbums();
        session()->flash('success', 'Album updated successfully!');
    }

    public function regenerateSlug(): void
    {
        $base = $this->title ?: 'album';
        $this->slug = Str::slug($base . '-' . uniqid());
    }

    public function render()
    {
        return view('livewire.album.album-manager', [
            'categoryOptions' => AlbumCategory::options(),
        ]);
    }
}
