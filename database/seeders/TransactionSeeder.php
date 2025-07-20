<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaction;
use Faker\Factory as Faker;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();
        $types = ['income', 'expense'];
        
        $incomeTags = ['Salary', 'Freelance', 'Investment', 'Gift', 'Bonus', 'Other'];
        $expenseTags = ['Food', 'Transportation', 'Housing', 'Utilities', 'Entertainment', 'Healthcare', 'Shopping', 'Education', 'Other'];
        
        $descriptions = [
            'Monthly salary payment', 'Freelance web development', 'Grocery shopping', 'Restaurant dinner', 'Electricity bill payment',
            'Internet service bill', 'Gym membership fee', 'Coffee shop visit', 'Clothing purchase', 'Birthday gift', 'Performance bonus', 'Monthly rent',
            'Car loan payment', 'Health insurance', 'Medical consultation', 'Vacation trip', 'Movie tickets', 'Netflix subscription'
        ];

        // Update existing transactions that don't have tags
        $existingTransactions = Transaction::whereNull('tag')->get();
        foreach ($existingTransactions as $transaction) {
            $tag = $transaction->type === 'income' 
                ? $faker->randomElement($incomeTags)
                : $faker->randomElement($expenseTags);
            
            $transaction->update(['tag' => $tag]);
        }

        foreach ([1, 2] as $userId) {
            $currentDate = Carbon::now();
            $startDate = Carbon::create(2024, 1, 1); // Start from January 1, 2024
            
            // Create transactions for the past year up to today
            $currentMonth = $startDate->copy();
            
            while ($currentMonth->lte($currentDate)) {
                $year = $currentMonth->year;
                $month = $currentMonth->month;
                
                // Income: 2-3 per month
                for ($i = 0; $i < rand(2, 3); $i++) {
                    // Generate a random day in the current month, but not in the future
                    $maxDay = min(28, $currentMonth->daysInMonth);
                    $day = rand(1, $maxDay);
                    
                    $date = Carbon::create($year, $month, $day, rand(0, 23), rand(0, 59));
                    
                    // Skip if the date is in the future
                    if ($date->gt($currentDate)) {
                        continue;
                    }
                    
                    $tag = $faker->randomElement($incomeTags);
                    $amount = $faker->randomFloat(2, 1000, 8000);
                    Transaction::create([
                        'description' => $faker->randomElement($descriptions),
                        'amount' => $amount,
                        'type' => 'income',
                        'tag' => $tag,
                        'user_id' => $userId,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);
                }
                
                // Expenses: 8-12 per month
                for ($i = 0; $i < rand(8, 12); $i++) {
                    // Generate a random day in the current month, but not in the future
                    $maxDay = min(28, $currentMonth->daysInMonth);
                    $day = rand(1, $maxDay);
                    
                    $date = Carbon::create($year, $month, $day, rand(0, 23), rand(0, 59));
                    
                    // Skip if the date is in the future
                    if ($date->gt($currentDate)) {
                        continue;
                    }
                    
                    $tag = $faker->randomElement($expenseTags);
                    $amount = $faker->randomFloat(2, 10, 1200);
                    Transaction::create([
                        'description' => $faker->randomElement($descriptions),
                        'amount' => $amount,
                        'type' => 'expense',
                        'tag' => $tag,
                        'user_id' => $userId,
                        'created_at' => $date,
                        'updated_at' => $date,
                    ]);
                }
                
                // Move to next month
                $currentMonth->addMonth();
            }
        }
    }
}