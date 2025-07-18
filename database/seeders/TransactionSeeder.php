<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Transaction;
use Faker\Factory as Faker;

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
            for ($i = 0; $i < 10; $i++) {
                $type = $faker->randomElement($types);
                $amount = $type === 'income'
                    ? $faker->randomFloat(2, 500, 5000)
                    : $faker->randomFloat(2, 10, 500);
                
                $tag = $type === 'income' 
                    ? $faker->randomElement($incomeTags)
                    : $faker->randomElement($expenseTags);
                
                Transaction::create([
                    'description' => $faker->randomElement($descriptions),
                    'amount' => $amount,
                    'type' => $type,
                    'tag' => $tag,
                    'user_id' => $userId,
                    'created_at' => $faker->dateTimeBetween('-2 months', 'now'),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}