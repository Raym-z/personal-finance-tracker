<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserSetting;

class DefaultTagsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        //
    }

    /**
     * Create default tags for a specific user
     */
    public static function createDefaultTagsForUser($userId)
    {
        $defaultTags = [
            // Income tags
            'Salary' => [
                'type' => 'income',
                'color' => '#198754',
                'created_at' => now()->toISOString(),
            ],
            'Freelance' => [
                'type' => 'income',
                'color' => '#0dcaf0',
                'created_at' => now()->toISOString(),
            ],
            'Investment' => [
                'type' => 'income',
                'color' => '#0d6efd',
                'created_at' => now()->toISOString(),
            ],
            'Gift' => [
                'type' => 'income',
                'color' => '#ffc107',
                'created_at' => now()->toISOString(),
            ],
            'Bonus' => [
                'type' => 'income',
                'color' => '#198754',
                'created_at' => now()->toISOString(),
            ],
            'Other' => [
                'type' => 'income',
                'color' => '#6c757d',
                'created_at' => now()->toISOString(),
            ],
            
            // Expense tags
            'Food' => [
                'type' => 'expense',
                'color' => '#dc3545',
                'created_at' => now()->toISOString(),
            ],
            'Transportation' => [
                'type' => 'expense',
                'color' => '#0d6efd',
                'created_at' => now()->toISOString(),
            ],
            'Housing' => [
                'type' => 'expense',
                'color' => '#212529',
                'created_at' => now()->toISOString(),
            ],
            'Utilities' => [
                'type' => 'expense',
                'color' => '#0dcaf0',
                'created_at' => now()->toISOString(),
            ],
            'Entertainment' => [
                'type' => 'expense',
                'color' => '#ffc107',
                'created_at' => now()->toISOString(),
            ],
            'Healthcare' => [
                'type' => 'expense',
                'color' => '#dc3545',
                'created_at' => now()->toISOString(),
            ],
            'Shopping' => [
                'type' => 'expense',
                'color' => '#0d6efd',
                'created_at' => now()->toISOString(),
            ],
            'Education' => [
                'type' => 'expense',
                'color' => '#0dcaf0',
                'created_at' => now()->toISOString(),
            ],
        ];

        // Store all tags as custom tags for this user
        UserSetting::setSetting($userId, 'custom_tags', $defaultTags);
    }
}