<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Entreprise;
use App\Models\Chercheur;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('fr_FR');

        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
            'is_active' => true
        ]);

        // Create entreprise users
        for ($i = 0; $i < 20; $i++) {
            $companyName = $faker->company;
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->companyEmail,
                'password' => Hash::make('password'),
                'role' => 'recruteur',
                'email_verified_at' => now(),
                'is_active' => true
            ]);

            Entreprise::create([
                'user_id' => $user->id,
                'company_name' => $companyName,
                'description' => $faker->paragraphs(3, true),
                'website' => $faker->url,
                'location' => $faker->city,
                'industry' => $faker->randomElement(['Technology', 'Finance', 'Healthcare', 'Education', 'Manufacturing', 'Retail', 'Media', 'Consulting']),
                'size' => $faker->randomElement(['1-10', '11-50', '51-200', '201-500', '501-1000', '1000+']),
                'founded_year' => $faker->numberBetween(1950, 2023),
                'linkedin_url' => 'https://linkedin.com/company/' . $faker->slug,
                'logo' => null
            ]);
        }

        // Create chercheur users
        for ($i = 0; $i < 50; $i++) {
            $user = User::create([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'),
                'role' => 'chercheur',
                'email_verified_at' => now(),
                'is_active' => true
            ]);

            Chercheur::create([
                'user_id' => $user->id,
                'title' => $faker->jobTitle,
                'bio' => $faker->paragraphs(2, true),
                'experience' => $faker->randomElement(['0-1 year', '1-3 years', '3-5 years', '5-10 years', '10+ years']),
                'education_level' => $faker->randomElement(['High School', 'Bachelor', 'Master', 'PhD']),
                'current_salary' => $faker->numberBetween(30000, 120000),
                'expected_salary' => $faker->numberBetween(35000, 150000),
                'availability' => $faker->randomElement(['immediate', '2 weeks', '1 month', '3 months']),
                'linkedin_url' => 'https://linkedin.com/in/' . $faker->slug,
                'github_url' => 'https://github.com/' . $faker->userName,
                'portfolio_url' => $faker->url,
                'phone' => $faker->phoneNumber,
                'location' => $faker->city,
                'willing_to_relocate' => $faker->boolean(70),
                'remote_preference' => $faker->randomElement(['office', 'hybrid', 'remote'])
            ]);
        }
    }
} 