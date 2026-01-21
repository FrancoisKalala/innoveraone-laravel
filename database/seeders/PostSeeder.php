<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use App\Models\Album;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $publicAlbums = Album::where('visibility', 'public')->get();
        $privateAlbums = Album::where('visibility', 'private')->get();

        $postContents = [
            "Just launched my new project! 🚀 Feeling amazing about the progress. Can't wait to share more details soon!",
            "Beautiful sunset today. Nature is incredible 🌅 Sometimes you just need to stop and appreciate the moment.",
            "Anyone want to collaborate on a new startup? 💡 Looking for passionate developers and designers!",
            "Finally finished reading this amazing book! Highly recommend it to everyone 📚",
            "Made the best pasta today! 🍝 Recipe in the comments if anyone wants it!",
            "Hit a new personal record at the gym today! 💪 Progress feels so good. Keep pushing!",
            "This code finally works! After 3 hours of debugging 😅 The feeling is unmatched.",
            "Coffee tastes better when you're working on something you love ☕️",
            "Traveling to Japan next month! Any recommendations? 🇯🇵✈️",
            "New music playlist just dropped! Perfect for coding sessions 🎵",
            "My cat did the funniest thing today 😹 Pets make life so much better.",
            "Great meeting today! Exciting things coming soon 🎯",
            "Learning a new programming language. Never too late to grow! 📖",
            "Trying out a new recipe tonight. Wish me luck! 🍳",
            "Just finished a 5K run! Feels great to stay active 🏃‍♂️",
            "Working on a new design project. Can't show it yet but it's 🔥",
            "Best decision I made was starting this journey. Grateful every day 🙏",
            "Anyone else obsessed with productivity apps? Found a great one!",
            "Weekend vibes! Time to relax and recharge 😌",
            "Debugging is 90% of programming. The other 10% is writing bugs 😂",
            "New camera gear arrived! Can't wait to test it out 📸",
            "Sometimes the best ideas come at 3 AM 💡",
            "Progress update: Things are moving faster than expected!",
            "Just discovered an amazing coffee shop in the city ☕",
            "Teaching myself graphic design. It's challenging but fun!",
            "Movie recommendation: Just watched an incredible film!",
            "This view from my office is unbeatable 🏞️",
            "Meal prep Sunday! Staying healthy this week 🥗",
            "New blog post is live! Check it out and let me know your thoughts.",
            "Collaboration makes everything better. Grateful for my team! 🤝",
            "Found a bug in production. Time to fix it! 🐛",
            "Morning meditation hits different. Starting the day right 🧘‍♀️",
            "Just reached 1000 followers! Thank you all so much! 🎉",
            "Weekend project: Building something cool with Arduino 🤖",
            "Best pizza in town hands down! 🍕 Trust me on this.",
            "Finally upgraded my workspace setup. Productivity 📈",
            "Learning never stops. Just enrolled in a new course!",
            "This playlist is perfect for studying 🎧",
            "Grateful for all the support you've shown! 💜",
            "New personal best in chess today! ♟️",
            "Working from a beach today. Living the dream 🏖️",
            "Just finished a great workout session! Feeling energized ⚡",
            "Pro tip: Always backup your code. Learned this the hard way 😅",
            "Amazing conference today! Met so many inspiring people.",
            "Late night coding session. Coffee is my best friend ☕💻",
            "This sunset looks like a painting 🎨",
            "New shoes arrived! Time to break them in 👟",
            "Celebrating small wins today! Every step counts 🎊",
            "Just discovered an incredible podcast series!",
            "My plant is finally blooming! 🌱 Patience pays off.",
        ];

        $interactionTypes = ['all', 'like', 'dislike', 'comment', 'like_comment', 'like_dislike', 'none'];
        $expirationHours = [5, 10, 24, 72, 168, 720];

        // Create posts for public albums
        foreach ($publicAlbums as $album) {
            $postCount = rand(3, 8);

            for ($i = 0; $i < $postCount; $i++) {
                $user = $users->random();
                $createdAt = now()->subDays(rand(0, 30))->subHours(rand(0, 23));

                Post::create([
                    'user_id' => $user->id,
                    'album_id' => $album->id,
                    'content' => $postContents[array_rand($postContents)],
                    'interaction_type' => $interactionTypes[array_rand($interactionTypes)],
                    'expiration_hours' => rand(0, 10) > 3 ? 720 : $expirationHours[array_rand($expirationHours)],
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }

            // Update album publications count
            $album->update(['publications_count' => $postCount]);
        }

        // Create some posts for private albums
        foreach ($privateAlbums as $album) {
            $postCount = rand(1, 3);

            for ($i = 0; $i < $postCount; $i++) {
                $content = $postContents[array_rand($postContents)];

                Post::create([
                    'user_id' => $album->user_id, // Private albums only by owner
                    'album_id' => $album->id,
                    'content' => "Private thought: " . $postContents[array_rand($postContents)],
                    'interaction_type' => 'all',
                    'expiration_hours' => 720,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }

            $album->update(['publications_count' => $postCount]);
        }

        $totalPosts = Post::count();
        $this->command->info("Created {$totalPosts} posts successfully!");
    }
}
