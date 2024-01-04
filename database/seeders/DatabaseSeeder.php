<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Pledge;
use App\Models\Campaign;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       // Seed users
       //User::factory(20)->create(); // Create 10 users

       // Seed campaigns
       Campaign::factory(20)->create(); // Create 20 campaigns

       // Seed pledges
       Pledge::factory(30)->create(); // Create 50 pledges

        

         \App\Models\User::factory()->create([
           'firstname' => 'Alex',
           'sirname' => 'Mwai',
           'gender' => 'male',
           'dob' => '2001-08-08',
           'email' => 'alexmwai59@gmail.com',
           'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
           'role'=>'admin',
           'profile'=>'profiles/SkxY4dz20z6ifhEBFeEbdNPRYig0uVnh1UHFnU3T.png'
        ]);
    }
}
