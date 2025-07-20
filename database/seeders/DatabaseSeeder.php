<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Raymond Willy Tan',
            'email' => 'raymondwillytan@gmail.com',
            'password' => Hash::make('hellohello123'),
        ]);
        User::create([
            'name' => 'Test User 2',
            'email' => 'user2@example.com',
            'password' => Hash::make('password'),
        ]);
        $this->call([
            TransactionSeeder::class,
            BudgetSeeder::class,
            GoalSeeder::class,
        ]);
    }
}