<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Budget;
use Faker\Factory as Faker;

class BudgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();
        
        // Budget categories for different expense types
        $budgetCategories = [
            'Food', 'Transportation', 'Housing', 'Utilities', 
            'Entertainment', 'Healthcare', 'Shopping', 'Education'
        ];
        
        // Create budgets for each user
        foreach ([1, 2] as $userId) {
            // Create 3-5 budgets per user
            for ($i = 0; $i < rand(3, 5); $i++) {
                $category = $faker->randomElement($budgetCategories);
                $period = $faker->randomElement(['monthly', 'weekly']);
                $amount = $faker->randomFloat(2, 50, 2000);
                
                // Set start and end dates (optional)
                $startDate = $faker->optional(0.7)->dateTimeBetween('-6 months', '+1 month');
                $endDate = null;
                if ($startDate) {
                    $endDate = $faker->optional(0.5)->dateTimeBetween($startDate, '+12 months');
                }
                
                Budget::create([
                    'user_id' => $userId,
                    'category' => $category,
                    'amount' => $amount,
                    'period' => $period,
                    'start_date' => $startDate ? $startDate->format('Y-m-d') : null,
                    'end_date' => $endDate ? $endDate->format('Y-m-d') : null,
                ]);
            }
        }
    }
} 