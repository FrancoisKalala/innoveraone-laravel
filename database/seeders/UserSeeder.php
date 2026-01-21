<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create main test user
        User::create([
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'john@innovera.com',
            'password' => Hash::make('password'),
            'bio' => 'Software developer & tech enthusiast. Love sharing ideas and connecting with creative minds! 🚀',
            'email_verified_at' => now(),
        ]);

        // Create Francois user
        User::create([
            'name' => 'Francois',
            'username' => 'francois',
            'email' => 'francoisdieudonne214@gmail.com',
            'password' => Hash::make('francois'),
            'bio' => 'Welcome to Innovera! 🎯',
            'email_verified_at' => now(),
        ]);

        // Create additional sample users
        $users = [
            [
                'name' => 'Sarah Mitchell',
                'username' => 'sarahm',
                'email' => 'sarah@innovera.com',
                'bio' => 'Digital artist | Creating beauty through pixels ✨🎨',
            ],
            [
                'name' => 'Alex Rivera',
                'username' => 'alexrivera',
                'email' => 'alex@innovera.com',
                'bio' => 'Adventure seeker | Photography lover 📸🌍',
            ],
            [
                'name' => 'Jordan Lee',
                'username' => 'jordanlee',
                'email' => 'jordan@innovera.com',
                'bio' => 'Entrepreneur | Building the future one startup at a time 💡',
            ],
            [
                'name' => 'Emma Chen',
                'username' => 'emmachen',
                'email' => 'emma@innovera.com',
                'bio' => 'UX Designer | Making the web beautiful & accessible 🎯',
            ],
            [
                'name' => 'Michael Torres',
                'username' => 'michaelt',
                'email' => 'michael@innovera.com',
                'bio' => 'Music producer | Beats & vibes 🎵🎧',
            ],
            [
                'name' => 'Lisa Anderson',
                'username' => 'lisaanderson',
                'email' => 'lisa@innovera.com',
                'bio' => 'Fitness coach | Health is wealth 💪🥗',
            ],
            [
                'name' => 'David Kim',
                'username' => 'davidkim',
                'email' => 'david@innovera.com',
                'bio' => 'Data scientist | Numbers tell stories 📊',
            ],
            [
                'name' => 'Sophie Laurent',
                'username' => 'sophiel',
                'email' => 'sophie@innovera.com',
                'bio' => 'Fashion blogger | Style is a way to say who you are 👗✨',
            ],
            [
                'name' => 'Ryan Murphy',
                'username' => 'ryanm',
                'email' => 'ryan@innovera.com',
                'bio' => 'Gaming streamer | Live every day 🎮',
            ],
            [
                'name' => 'Olivia Brown',
                'username' => 'oliviab',
                'email' => 'olivia@innovera.com',
                'bio' => 'Food blogger | Life is too short for bad food 🍕😋',
            ],
            [
                'name' => 'James Wilson',
                'username' => 'jamesw',
                'email' => 'james@innovera.com',
                'bio' => 'Travel vlogger | Exploring the world one country at a time ✈️',
            ],
            [
                'name' => 'Mia Rodriguez',
                'username' => 'miarodriguez',
                'email' => 'mia@innovera.com',
                'bio' => 'Writer | Words are my paintbrush 📝',
            ],
            [
                'name' => 'Chris Evans',
                'username' => 'chrise',
                'email' => 'chris@innovera.com',
                'bio' => 'Photographer | Capturing moments that matter 📷',
            ],
            [
                'name' => 'Nina Patel',
                'username' => 'ninap',
                'email' => 'nina@innovera.com',
                'bio' => 'Yoga instructor | Find your inner peace 🧘‍♀️',
            ],
            [
                'name' => 'Tom Harris',
                'username' => 'tomh',
                'email' => 'tom@innovera.com',
                'bio' => 'Tech reviewer | Unboxing the future 📦🔌',
            ],
            [
                'name' => 'Rachel Green',
                'username' => 'rachelg',
                'email' => 'rachel@innovera.com',
                'bio' => 'Interior designer | Making spaces beautiful 🏡',
            ],
            [
                'name' => 'Kevin Zhang',
                'username' => 'kevinz',
                'email' => 'kevin@innovera.com',
                'bio' => 'Crypto enthusiast | Blockchain believer 💰',
            ],
            [
                'name' => 'Amanda Scott',
                'username' => 'amandas',
                'email' => 'amanda@innovera.com',
                'bio' => 'Pet lover | My dogs are my world 🐕❤️',
            ],
            [
                'name' => 'Daniel Park',
                'username' => 'danielp',
                'email' => 'daniel@innovera.com',
                'bio' => 'Coffee addict | Barista by day, coder by night ☕💻',
            ],
            [
                'name' => 'Isabella Martinez',
                'username' => 'isabellam',
                'email' => 'isabella@innovera.com',
                'bio' => 'Makeup artist | Beauty is an art form 💄✨',
            ],
        ];

        foreach ($users as $userData) {
            User::create([
                'name' => $userData['name'],
                'username' => $userData['username'],
                'email' => $userData['email'],
                'password' => Hash::make('password'),
                'bio' => $userData['bio'],
                'email_verified_at' => now(),
            ]);
        }

        $this->command->info('Created ' . (count($users) + 1) . ' users successfully!');
    }
}
