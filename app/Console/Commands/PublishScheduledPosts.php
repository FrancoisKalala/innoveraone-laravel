<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;
use Carbon\Carbon;

class PublishScheduledPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'posts:publish-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish scheduled posts whose publish_at time has arrived.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $posts = Post::where('status', 'scheduled')
            ->where('publish_at', '<=', $now)
            ->where('already_deleted', false)
            ->where('already_expired', false)
            ->get();

        $count = 0;
        foreach ($posts as $post) {
            $post->status = 'published';
            $post->publish_at = null;
            $post->save();
            $count++;
        }

        $this->info("Published {$count} scheduled post(s).");
    }
}
