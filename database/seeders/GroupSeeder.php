<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\GroupMessage;
use Illuminate\Database\Seeder;

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        $groupsData = [
            [
                'name' => 'Tech Innovators',
                'description' => 'Discuss latest tech trends and innovations',
            ],
            [
                'name' => 'Photography Club',
                'description' => 'Share tips and showcase your best shots',
            ],
            [
                'name' => 'Startup Founders',
                'description' => 'Network with fellow entrepreneurs',
            ],
            [
                'name' => 'Fitness Motivation',
                'description' => 'Support each other on our fitness journey',
            ],
            [
                'name' => 'Book Lovers',
                'description' => 'Discuss books and share recommendations',
            ],
            [
                'name' => 'Travel Buddies',
                'description' => 'Plan trips and share travel experiences',
            ],
            [
                'name' => 'Music Production',
                'description' => 'For music producers and enthusiasts',
            ],
            [
                'name' => 'Gaming Squad',
                'description' => 'Find teammates and discuss games',
            ],
            [
                'name' => 'Art & Design',
                'description' => 'Share creative work and get feedback',
            ],
            [
                'name' => 'Food Enthusiasts',
                'description' => 'Share recipes and restaurant recommendations',
            ],
        ];

        $groupCount = 0;
        $memberCount = 0;
        $messageCount = 0;

        foreach ($groupsData as $groupData) {
            $creator = $users->random();

            $group = Group::create([
                'name' => $groupData['name'],
                'description' => $groupData['description'],
                'created_by' => $creator->id,
                'slug' => \Illuminate\Support\Str::slug($groupData['name']) . '-' . rand(1000, 9999),
                'created_at' => now()->subDays(rand(0, 180)),
            ]);

            $groupCount++;

            // Add creator as admin member
            GroupMember::create([
                'group_id' => $group->id,
                'user_id' => $creator->id,
                'role' => 'admin',
            ]);
            $memberCount++;

            // Add random members
            $membersToAdd = rand(3, 10);
            $members = $users->where('id', '!=', $creator->id)->random(min($membersToAdd, $users->count() - 1));

            foreach ($members as $member) {
                GroupMember::create([
                    'group_id' => $group->id,
                    'user_id' => $member->id,
                    'role' => 'member',
                ]);
                $memberCount++;
            }

            // Create group messages
            $allGroupMembers = GroupMember::where('group_id', $group->id)->get();
            $messagesInGroup = rand(5, 20);

            $messageTexts = [
                "Hey everyone! 👋",
                "Great to be part of this group!",
                "Anyone free for a meetup this weekend?",
                "Just wanted to share this awesome resource!",
                "What do you all think about this?",
                "Thanks for the warm welcome! 😊",
                "This group is amazing!",
                "Looking forward to learning from you all!",
                "Has anyone tried this before?",
                "Let me know if you need any help!",
                "Exciting times ahead!",
                "Count me in!",
                "That's a great idea!",
                "I totally agree with that!",
                "Can't wait for the next event!",
            ];

            for ($i = 0; $i < $messagesInGroup; $i++) {
                $sender = $allGroupMembers->random();

                GroupMessage::create([
                    'group_id' => $group->id,
                    'user_id' => $sender->user_id,
                    'content' => $messageTexts[array_rand($messageTexts)],
                    'created_at' => $group->created_at->addDays(rand(1, 30))->addHours(rand(0, 23)),
                ]);
                $messageCount++;
            }
        }

        $this->command->info("Created {$groupCount} groups!");
        $this->command->info("Created {$memberCount} group memberships!");
        $this->command->info("Created {$messageCount} group messages!");
    }
}
