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
            for ($month = 1; $month <= 12; $month++) {
                $year = 2025;
                // Income: 2-3 per month
                for ($i = 0; $i < rand(2, 3); $i++) {
                    $date = Carbon::create($year, $month, rand(1, 28), rand(0, 23), rand(0, 59));
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
                    $date = Carbon::create($year, $month, rand(1, 28), rand(0, 23), rand(0, 59));
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
            }
        }
    }
}