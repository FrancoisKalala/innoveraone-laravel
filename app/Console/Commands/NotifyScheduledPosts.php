<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use App\Models\Notification;
use Carbon\Carbon;

class NotifyScheduledPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:notify-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify users before their scheduled posts are published.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $notifyMinutes = 10; // Notify 10 minutes before publish
        $now = Carbon::now();
        $notifyTime = $now->copy()->addMinutes($notifyMinutes);

        $posts = Post::where('status', 'scheduled')
            ->where('already_deleted', false)
            ->where('already_expired', false)
            ->whereNotNull('publish_at')
            ->where('publish_at', '>', $now)
            ->where('publish_at', '<=', $notifyTime)
            ->get();

        $count = 0;
        foreach ($posts as $post) {
            // Avoid duplicate notifications: check if notification exists for this post and user
            $alreadyNotified = Notification::where('user_id', $post->user_id)
                ->where('type', 'scheduled_post_upcoming')
                ->where('notifiable_type', Post::class)
                ->where('notifiable_id', $post->id)
                ->exists();
            if ($alreadyNotified) continue;

            Notification::create([
                'user_id' => $post->user_id,
                'type' => 'scheduled_post_upcoming',
                'message' => 'Your scheduled post will be published soon.',
                'notifiable_type' => Post::class,
                'notifiable_id' => $post->id,
                'is_read' => false,
            ]);
            $count++;
        }

        $this->info("Notified users for {$count} upcoming scheduled post(s).");
    }
}
