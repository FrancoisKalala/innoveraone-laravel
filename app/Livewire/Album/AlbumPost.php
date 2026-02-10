<?php

namespace App\Livewire\Album;

use App\Models\Album;
use App\Models\Post;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class AlbumPost extends Component
{
    use WithPagination;

    public $albumId;
    public $album;
    public $perPage;
    public $hasMore = true;
    public $isFavorited = false;

    public function mount($album)
    {
        $this->albumId = $album;
        $this->album = Album::with('user')->findOrFail($album);
        $this->isFavorited = $this->album->favorites()->where('user_id', Auth::id())->exists();
        $this->perPage = 15;
        $this->markAllPostsViewed();
    }

    public function markAllPostsViewed()
    {
        $userId = Auth::id();
        if (!$userId) return;
        $postIds = Post::where('album_id', $this->albumId)
            ->where('already_deleted', false)
            ->where('already_expired', false)
            ->pluck('id');
        foreach ($postIds as $postId) {
            $exists = DB::table('album_post_views')
                ->where('album_id', $this->albumId)
                ->where('user_id', $userId)
                ->where('post_id', $postId)
                ->exists();
            if (!$exists) {
                DB::table('album_post_views')->insert([
                    'album_id' => $this->albumId,
                    'user_id' => $userId,
                    'post_id' => $postId,
                    'viewed_at' => now(),
                ]);
            }
        }
    }

    public function loadMore()
    {
        $this->perPage += 15;
    }

    public function toggleFavorite()
    {
        $authUser = Auth::user();
        if (!$authUser) return;
        $favorite = $this->album->favorites()->where('user_id', $authUser->id)->first();
        if ($favorite) {
            $favorite->delete();
            $this->isFavorited = false;
        } else {
            $this->album->favorites()->create([
                'user_id' => $authUser->id,
                'favorited_at' => now(),
            ]);
            $this->isFavorited = true;
        }
    }

        public function markPostViewed($postId)
    {
        $userId = Auth::id();
        if (!$userId) return;
        $exists = DB::table('album_post_views')
            ->where('album_id', $this->albumId)
            ->where('user_id', $userId)
            ->where('post_id', $postId)
            ->exists();
        if (!$exists) {
            DB::table('album_post_views')->insert([
                'album_id' => $this->albumId,
                'user_id' => $userId,
                'post_id' => $postId,
                'viewed_at' => now(),
            ]);
        }
    }

    public function render()
    {
        $userId = Auth::id();
        $viewedPostIds = collect();
        if ($userId) {
            $viewedPostIds = DB::table('album_post_views')
                ->where('album_id', $this->albumId)
                ->where('user_id', $userId)
                ->pluck('post_id');
        }
        $posts = Post::where('album_id', $this->albumId)
            ->where('already_deleted', false)
            ->where('already_expired', false)
            ->with(['user', 'album', 'likes', 'comments', 'media'])
            ->get();

        // Sort: unviewed first (by date desc), then viewed (by date desc)
        $unviewed = $posts->filter(function($post) use ($viewedPostIds) {
            return !$viewedPostIds->contains($post->id);
        })->sortByDesc('created_at');
        $viewed = $posts->filter(function($post) use ($viewedPostIds) {
            return $viewedPostIds->contains($post->id);
        })->sortByDesc('created_at');
        $sortedPosts = $unviewed->concat($viewed)->values();

        $total = $sortedPosts->count();
        $this->hasMore = $total > $this->perPage;
        $paginated = $sortedPosts->slice(0, $this->perPage);

        return view('livewire.album.album-post', [
            'album' => $this->album,
            'posts' => $paginated,
            'hasMore' => $this->hasMore,
            'isFavorited' => $this->isFavorited,
            'viewedPostIds' => $viewedPostIds,
        ]);
    }
}
