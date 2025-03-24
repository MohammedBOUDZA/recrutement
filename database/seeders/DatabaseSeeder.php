<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Entreprise;
use App\Models\Chercheur;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin'
        ]);

        // Create entreprises users and profiles
        $entrepriseUsers = [
            [
                'name' => 'Tech Solutions',
                'email' => 'tech@example.com',
                'password' => Hash::make('password'),
                'role' => 'entreprise',
                'company' => [
                    'company_name' => 'Tech Solutions',
                    'description' => 'Leading technology company',
                    'website' => 'https://techsolutions.com',
                    'location' => 'Paris',
                    'industry' => 'Technology'
                ]
            ],
            [
                'name' => 'Digital Systems',
                'email' => 'digital@example.com',
                'password' => Hash::make('password'),
                'role' => 'entreprise',
                'company' => [
                    'company_name' => 'Digital Systems',
                    'description' => 'Digital transformation experts',
                    'website' => 'https://digitalsystems.com',
                    'location' => 'Lyon',
                    'industry' => 'Software'
                ]
            ],
            [
                'name' => 'Web Innovate',
                'email' => 'web@example.com',
                'password' => Hash::make('password'),
                'role' => 'entreprise',
                'company' => [
                    'company_name' => 'Web Innovate',
                    'description' => 'Web development company',
                    'website' => 'https://webinnovate.com',
                    'location' => 'Marseille',
                    'industry' => 'Web Services'
                ]
            ],
        ];

        foreach ($entrepriseUsers as $userData) {
            $company = $userData['company'];
            unset($userData['company']);
            
            $user = User::create($userData);
            $company['user_id'] = $user->id;
            Entreprise::create($company);
        }

        // Create chercheur user and profile
        $chercheur = User::create([
            'name' => 'Test Seeker',
            'email' => 'seeker@example.com',
            'password' => Hash::make('password'),
            'role' => 'chercheur'
        ]);

        Chercheur::create([
            'user_id' => $chercheur->id,
            'cv' => 'default_cv.pdf',
            'skills' => 'PHP, Laravel, MySQL',
            'experience' => '5 years'
        ]);

        // Seed emplois
        DB::table('emplois')->insert([
            [
                'entreprise_id' => 1,
                'title' => 'Senior PHP Developer',
                'description' => 'We are looking for an experienced PHP developer with Laravel expertise.',
                'location' => 'Paris',
                'salary' => 55000,
                'emploi_type' => 'full-time',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'entreprise_id' => 2,
                'title' => 'Frontend Developer',
                'description' => 'Join our team as a frontend specialist working with modern frameworks.',
                'location' => 'Lyon',
                'salary' => 45000,
                'emploi_type' => 'full-time',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
