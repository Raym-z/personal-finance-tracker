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
        $descriptions = [
            'Salary', 'Freelance Project', 'Groceries', 'Dining Out', 'Electricity Bill',
            'Internet Bill', 'Gym Membership', 'Coffee', 'Shopping', 'Gift', 'Bonus', 'Rent',
            'Car Payment', 'Insurance', 'Medical', 'Travel', 'Entertainment', 'Subscription'
        ];

        foreach ([1, 2] as $userId) {
            for ($i = 0; $i < 10; $i++) {
                $type = $faker->randomElement($types);
                $amount = $type === 'income'
                    ? $faker->randomFloat(2, 500, 5000)
                    : $faker->randomFloat(2, 10, 500);
                Transaction::create([
                    'description' => $faker->randomElement($descriptions),
                    'amount' => $amount,
                    'type' => $type,
                    'user_id' => $userId,
                    'created_at' => $faker->dateTimeBetween('-2 months', 'now'),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}