<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserSetting;
use Database\Seeders\DefaultTagsSeeder;

class CreateDefaultTagsForExistingUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:create-default-tags';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create default tags for existing users who do not have any tags';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();
        $createdCount = 0;

        foreach ($users as $user) {
            $customTags = UserSetting::getSetting($user->id, 'custom_tags', []);
            
            if (empty($customTags)) {
                DefaultTagsSeeder::createDefaultTagsForUser($user->id);
                $this->info("Created default tags for user: {$user->name} ({$user->email})");
                $createdCount++;
            } else {
                $this->line("User {$user->name} already has tags, skipping...");
            }
        }

        $this->info("Completed! Created default tags for {$createdCount} users.");
    }
}