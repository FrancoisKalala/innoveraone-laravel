<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting database seeding...');

        // Run seeders in order
        $this->call([
            UserSeeder::class,
            ChapterSeeder::class,
            PostSeeder::class,
            InteractionSeeder::class,
            GroupSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('✅ Database seeding completed successfully!');
        $this->command->info('📧 Login with any user: email@innovera.com | password: password');
        $this->command->info('👤 Example: john@innovera.com / password');
    }
}
