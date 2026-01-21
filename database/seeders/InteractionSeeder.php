<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Like;
use App\Models\Follower;
use App\Models\Contact;
use App\Models\CommentLike;
use App\Models\CommentDislike;
use Illuminate\Database\Seeder;

class InteractionSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $posts = Post::all();

        // Create Likes
        $likeCount = 0;
        foreach ($posts as $post) {
            $likersCount = rand(1, 15);
            $likers = $users->random(min($likersCount, $users->count()));

            foreach ($likers as $liker) {
                // Don't like own posts every time
                if ($liker->id === $post->user_id && rand(0, 10) > 2) {
                    continue;
                }

                Like::create([
                    'user_id' => $liker->id,
                    'likeable_type' => 'App\\Models\\Post',
                    'likeable_id' => $post->id,
                    'created_at' => $post->created_at->addMinutes(rand(1, 300)),
                ]);
                $likeCount++;
            }
        }

        // Create Comments
        $commentCount = 0;
        $comments = [];

        foreach ($posts as $post) {
            $commentsOnPost = rand(0, 8);

            for ($i = 0; $i < $commentsOnPost; $i++) {
                $commenter = $users->random();
                $commentTexts = [
                    "Great post! 👏",
                    "Love this! ❤️",
                    "Totally agree with you!",
                    "This is amazing! 🔥",
                    "Thanks for sharing!",
                    "So true! 💯",
                    "Interesting perspective!",
                    "Can't wait to try this!",
                    "Exactly what I needed to hear today!",
                    "This made my day! 😊",
                    "Inspiring! Keep it up!",
                    "Wow, impressive!",
                    "I needed to see this today 🙏",
                    "Absolutely brilliant!",
                    "This is gold! ✨",
                ];

                $comment = Comment::create([
                    'user_id' => $commenter->id,
                    'post_id' => $post->id,
                    'content' => $commentTexts[array_rand($commentTexts)],
                    'created_at' => $post->created_at->addMinutes(rand(5, 400)),
                ]);

                $comments[] = $comment;
                $commentCount++;

                // Add likes/dislikes to comments
                $commentLikers = rand(0, 5);
                $selectedLikers = $users->random(min($commentLikers, $users->count()));

                foreach ($selectedLikers as $likerUser) {
                    if (rand(0, 10) > 2) {
                        CommentLike::firstOrCreate([
                            'user_id' => $likerUser->id,
                            'comment_id' => $comment->id,
                        ]);
                    } else {
                        CommentDislike::firstOrCreate([
                            'user_id' => $likerUser->id,
                            'comment_id' => $comment->id,
                        ]);
                    }
                }
            }
        }

        // Create Followers
        $followerCount = 0;
        foreach ($users as $user) {
            $followCount = rand(3, 12);
            $usersToFollow = $users->where('id', '!=', $user->id)->random(min($followCount, $users->count() - 1));

            foreach ($usersToFollow as $userToFollow) {
                Follower::create([
                    'follower_id' => $user->id,
                    'following_id' => $userToFollow->id,
                    'created_at' => now()->subDays(rand(0, 60)),
                ]);
                $followerCount++;
            }
        }

        // Create Contacts (accepted friendships)
        $contactCount = 0;
        foreach ($users as $user) {
            $contactsCount = rand(2, 8);
            $potentialContacts = $users->where('id', '>', $user->id)->random(min($contactsCount, $users->where('id', '>', $user->id)->count()));

            foreach ($potentialContacts as $contact) {
                // Create bidirectional contact relationship
                Contact::create([
                    'user_id' => $user->id,
                    'contact_id' => $contact->id,
                    'status' => 'accepted',
                    'created_at' => now()->subDays(rand(0, 90)),
                ]);

                Contact::create([
                    'user_id' => $contact->id,
                    'contact_id' => $user->id,
                    'status' => 'accepted',
                    'created_at' => now()->subDays(rand(0, 90)),
                ]);

                $contactCount += 2;
            }
        }

        // Create some pending contact requests
        $pendingCount = 0;
        foreach ($users->take(10) as $user) {
            $requester = $users->where('id', '!=', $user->id)->random();

            // Check if contact doesn't already exist
            $exists = Contact::where('user_id', $requester->id)
                ->where('contact_id', $user->id)
                ->exists();

            if (!$exists) {
                Contact::create([
                    'user_id' => $requester->id,
                    'contact_id' => $user->id,
                    'status' => 'pending',
                    'created_at' => now()->subDays(rand(0, 7)),
                ]);
                $pendingCount++;
            }
        }

        $this->command->info("Created {$likeCount} likes!");
        $this->command->info("Created {$commentCount} comments!");
        $this->command->info("Created {$followerCount} follower relationships!");
        $this->command->info("Created {$contactCount} contacts ({$pendingCount} pending requests)!");
    }
}
