<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use App\Models\Notification;
use Carbon\Carbon;

class NotifyPostEvents extends Command
{
    protected $signature = 'posts:notify-events';
    protected $description = 'Send notifications for scheduled and expiring posts.';

    public function handle()
    {
        $now = Carbon::now();
        $notifyMinutesArr = [20, 10, 5];
        // Scheduled posts notifications
        foreach ($notifyMinutesArr as $minutes) {
            $from = $now->copy();
            $to = $now->copy()->addMinutes($minutes);
            $posts = Post::where('status', 'scheduled')
                ->where('already_deleted', false)
                ->where('already_expired', false)
                ->whereNotNull('publish_at')
                ->where('publish_at', '>', $from)
                ->where('publish_at', '<=', $to)
                ->get();
            foreach ($posts as $post) {
                $type = "scheduled_post_upcoming_{$minutes}";
                if (!Notification::where('user_id', $post->user_id)
                    ->where('type', $type)
                    ->where('notifiable_type', Post::class)
                    ->where('notifiable_id', $post->id)
                    ->exists()) {
                    Notification::create([
                        'user_id' => $post->user_id,
                        'type' => $type,
                        'message' => "Your scheduled post will be published in {$minutes} minutes.",
                        'notifiable_type' => Post::class,
                        'notifiable_id' => $post->id,
                        'is_read' => false,
                    ]);
                }
            }
        }
        // Published notification
        $posts = Post::where('status', 'published')
            ->whereNotNull('publish_at')
            ->where('publish_at', '<=', $now)
            ->get();
        foreach ($posts as $post) {
            $type = 'scheduled_post_published';
            if (!Notification::where('user_id', $post->user_id)
                ->where('type', $type)
                ->where('notifiable_type', Post::class)
                ->where('notifiable_id', $post->id)
                ->exists()) {
                Notification::create([
                    'user_id' => $post->user_id,
                    'type' => $type,
                    'message' => 'Your scheduled post has been published.',
                    'notifiable_type' => Post::class,
                    'notifiable_id' => $post->id,
                    'is_read' => false,
                ]);
            }
        }
        // Expiring posts notifications
        foreach ($notifyMinutesArr as $minutes) {
            $from = $now->copy();
            $to = $now->copy()->addMinutes($minutes);
            $posts = Post::where('status', 'published')
                ->where('already_expired', false)
                ->whereNotNull('expiration_hours')
                ->whereRaw('DATE_ADD(created_at, INTERVAL expiration_hours HOUR) > ?', [$from])
                ->whereRaw('DATE_ADD(created_at, INTERVAL expiration_hours HOUR) <= ?', [$to])
                ->get();
            foreach ($posts as $post) {
                $type = "post_expiring_{$minutes}";
                if (!Notification::where('user_id', $post->user_id)
                    ->where('type', $type)
                    ->where('notifiable_type', Post::class)
                    ->where('notifiable_id', $post->id)
                    ->exists()) {
                    Notification::create([
                        'user_id' => $post->user_id,
                        'type' => $type,
                        'message' => "Your post will expire in {$minutes} minutes.",
                        'notifiable_type' => Post::class,
                        'notifiable_id' => $post->id,
                        'is_read' => false,
                    ]);
                }
            }
        }
        // Expired notification
        $posts = Post::where('status', 'published')
            ->where('already_expired', true)
            ->get();
        foreach ($posts as $post) {
            $type = 'post_expired';
            if (!Notification::where('user_id', $post->user_id)
                ->where('type', $type)
                ->where('notifiable_type', Post::class)
                ->where('notifiable_id', $post->id)
                ->exists()) {
                Notification::create([
                    'user_id' => $post->user_id,
                    'type' => $type,
                    'message' => 'Your post has expired.',
                    'notifiable_type' => Post::class,
                    'notifiable_id' => $post->id,
                    'is_read' => false,
                ]);
            }
        }
        $this->info('Notifications for scheduled and expiring posts sent.');
    }
}
