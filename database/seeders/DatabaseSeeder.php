<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Entreprise;
use App\Models\Chercheur;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin'
        ]);

        // Create sample company
        $company = User::create([
            'name' => 'Sample Company',
            'email' => 'company@example.com',
            'password' => bcrypt('password'),
            'role' => 'entreprise'
        ]);

        Entreprise::create([
            'user_id' => $company->id,
            'company_name' => 'Tech Corp',
            'company_description' => 'Leading tech company',
            'website' => 'https://techcorp.com'
        ]);

        // Create sample job seeker
        $jobSeeker = User::create([
            'name' => 'Job Seeker',
            'email' => 'seeker@example.com',
            'password' => bcrypt('password'),
            'role' => 'chercheur'
        ]);

        Chercheur::create([
            'user_id' => $jobSeeker->id,
            'resume' => 'sample_resume.pdf',
            'skills' => 'PHP, Laravel, MySQL',
            'experience' => '5 years',
            'education' => 'Bachelor in Computer Science'
        ]);
    }
}
