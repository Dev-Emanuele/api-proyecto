<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         // Generate 10 verified users
         User::factory()->count(10)->create();

         // Generate 5 unverified users
         User::factory()->unverified()->count(5)->create();
    }
}
