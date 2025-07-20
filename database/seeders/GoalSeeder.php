<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Goal;
use Faker\Factory as Faker;

class GoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();
        
        // Goal names for different financial goals
        $goalNames = [
            'Emergency Fund', 'Vacation Fund', 'New Car', 'Home Down Payment',
            'Investment Portfolio', 'Wedding Fund', 'Business Startup', 'Education Fund',
            'Retirement Savings', 'Home Renovation', 'New Laptop', 'Gaming Setup'
        ];
        
        // Create goals for each user
        foreach ([1, 2] as $userId) {
            // Create 2-4 goals per user
            for ($i = 0; $i < rand(2, 4); $i++) {
                $name = $faker->randomElement($goalNames);
                $targetAmount = $faker->randomFloat(2, 1000, 50000);
                $currentAmount = $faker->randomFloat(2, 0, $targetAmount * 0.8); // Current amount up to 80% of target
                $targetDate = $faker->optional(0.8)->dateTimeBetween('+3 months', '+3 years');
                
                Goal::create([
                    'user_id' => $userId,
                    'name' => $name,
                    'target_amount' => $targetAmount,
                    'current_amount' => $currentAmount,
                    'target_date' => $targetDate ? $targetDate->format('Y-m-d') : null,
                ]);
            }
        }
    }
} 