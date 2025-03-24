<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserAndProfileSeeder extends Seeder
{
    public function run()
    {
        // Create users first
        $users = [
            [
                'name' => 'Enterprise User 1',
                'email' => 'enterprise1@example.com',
                'password' => Hash::make('password'),
                'role' => 'entreprise',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Job Seeker 1',
                'email' => 'seeker1@example.com',
                'password' => Hash::make('password'),
                'role' => 'chercheur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);

        // Create entreprises
        DB::table('entreprises')->insert([
            [
                'user_id' => 1,
                'company_name' => 'Tech Corp',
                'description' => 'Leading tech company',
                'website' => 'https://techcorp.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Create chercheurs
        DB::table('chercheurs')->insert([
            [
                'user_id' => 2,
                'cv' => 'cv1.pdf',
                'skills' => 'PHP, Laravel, MySQL',
                'experience' => '5 years',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}