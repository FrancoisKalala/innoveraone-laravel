<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use App\Models\Notification;
use Carbon\Carbon;

class ExpirePosts extends Command
{
    protected $signature = 'posts:expire';
    protected $description = 'Expire posts whose expiration time has arrived.';

    public function handle()
    {
        $now = Carbon::now();
        $posts = Post::where('status', 'published')
            ->where('already_expired', false)
            ->whereNotNull('expiration_hours')
            ->whereRaw('DATE_ADD(created_at, INTERVAL expiration_hours HOUR) <= ?', [$now])
            ->get();
        $count = 0;
        foreach ($posts as $post) {
            $post->already_expired = true;
            $post->save();
            Notification::create([
                'user_id' => $post->user_id,
                'type' => 'post_expired',
                'message' => 'Your post has expired.',
                'notifiable_type' => Post::class,
                'notifiable_id' => $post->id,
                'is_read' => false,
            ]);
            $count++;
        }
        $this->info("Expired {$count} post(s).");
    }
}
