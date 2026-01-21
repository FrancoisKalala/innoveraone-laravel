<?php

namespace Database\Seeders;

use App\Models\Album;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ChapterSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        $chapters = [
            [
                'title' => 'Tech News & Updates',
                'description' => 'Latest technology news, updates, and innovations. Share your thoughts on new tech!',
                'visibility' => 'public',
                'category' => 'text',
            ],
            [
                'title' => 'Photography Showcase',
                'description' => 'Beautiful photos from around the world. Share your best shots!',
                'visibility' => 'public',
                'category' => 'photo',
            ],
            [
                'title' => 'Daily Inspiration',
                'description' => 'Motivational quotes, stories, and positive vibes to start your day right.',
                'visibility' => 'public',
                'category' => 'text',
            ],
            [
                'title' => 'Music Lovers',
                'description' => 'Share your favorite tracks, music discoveries, and concert experiences.',
                'visibility' => 'public',
                'category' => 'audio',
            ],
            [
                'title' => 'Foodie Paradise',
                'description' => 'Delicious recipes, restaurant reviews, and food photography.',
                'visibility' => 'public',
                'category' => 'photo',
            ],
            [
                'title' => 'Fitness Journey',
                'description' => 'Workout tips, progress photos, and health motivation.',
                'visibility' => 'public',
                'category' => 'photo',
            ],
            [
                'title' => 'Travel Diaries',
                'description' => 'Share your travel experiences, tips, and beautiful destination photos.',
                'visibility' => 'public',
                'category' => 'photo',
            ],
            [
                'title' => 'Gaming Community',
                'description' => 'Discuss games, share gameplay videos, and connect with fellow gamers.',
                'visibility' => 'public',
                'category' => 'video',
            ],
            [
                'title' => 'Book Club',
                'description' => 'Book recommendations, reviews, and literary discussions.',
                'visibility' => 'public',
                'category' => 'text',
            ],
            [
                'title' => 'Art Gallery',
                'description' => 'Showcase your artwork, digital art, drawings, and creative projects.',
                'visibility' => 'public',
                'category' => 'photo',
            ],
            [
                'title' => 'Entrepreneurship Hub',
                'description' => 'Startup stories, business tips, and entrepreneurial discussions.',
                'visibility' => 'public',
                'category' => 'text',
            ],
            [
                'title' => 'Pet Lovers',
                'description' => 'Adorable pet photos, pet care tips, and funny animal moments.',
                'visibility' => 'public',
                'category' => 'photo',
            ],
            [
                'title' => 'Coding & Development',
                'description' => 'Programming tips, code snippets, and developer discussions.',
                'visibility' => 'public',
                'category' => 'text',
            ],
            [
                'title' => 'Fashion Trends',
                'description' => 'Latest fashion trends, outfit ideas, and style inspiration.',
                'visibility' => 'public',
                'category' => 'photo',
            ],
            [
                'title' => 'Movie Reviews',
                'description' => 'Discuss the latest movies, TV shows, and entertainment.',
                'visibility' => 'public',
                'category' => 'video',
            ],
            // Some private chapters
            [
                'title' => 'Private Thoughts',
                'description' => 'Personal journal entries and private reflections.',
                'visibility' => 'private',
                'category' => 'text',
            ],
            [
                'title' => 'Family Photos',
                'description' => 'Private family moments and memories.',
                'visibility' => 'private',
                'category' => 'photo',
            ],
            [
                'title' => 'Project Ideas',
                'description' => 'Private workspace for brainstorming and project planning.',
                'visibility' => 'private',
                'category' => 'text',
            ],
        ];

        foreach ($chapters as $index => $chapterData) {
            $user = $users->random();

            Album::create([
                'user_id' => $user->id,
                'title' => $chapterData['title'],
                'description' => $chapterData['description'],
                'slug' => Str::slug($chapterData['title']) . '-' . Str::random(6),
                'visibility' => $chapterData['visibility'],
                'category' => $chapterData['category'],
                'publications_count' => 0,
                'views_count' => rand(10, 500),
            ]);
        }

        $this->command->info('Created ' . count($chapters) . ' albums successfully!');
    }
}
