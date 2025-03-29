<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Emploi;
use App\Models\Application;
use App\Models\Chercheur;
use Faker\Factory as Faker;

class ApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('fr_FR');
        $chercheurs = Chercheur::with('user')->get();
        $emplois = Emploi::all();
        $statuses = ['draft', 'submitted', 'reviewing', 'shortlisted', 'interviewed', 'offered', 'rejected', 'withdrawn'];

        foreach ($chercheurs as $chercheur) {
            // Each job seeker applies to 2-8 jobs
            $appliedJobs = $emplois->random(rand(2, 8));
            
            foreach ($appliedJobs as $emploi) {
                $application = Application::create([
                    'user_id' => $chercheur->user->id,
                    'emplois_id' => $emploi->id,
                    'cover_letter' => $faker->paragraphs(3, true),
                    'status' => $faker->randomElement($statuses),
                    'answers' => json_encode(array_map(function() use ($faker) {
                        return $faker->sentence(10);
                    }, json_decode($emploi->questions ?? '[]'))),
                    'current_company' => $faker->company,
                    'current_position' => $faker->jobTitle,
                    'current_salary' => $faker->numberBetween(30000, 120000),
                    'salary_expectation' => $faker->numberBetween(35000, 150000),
                    'available_start_date' => $faker->dateTimeBetween('now', '+3 months'),
                    'relocation_willingness' => $faker->boolean(30),
                    'portfolio_url' => $faker->url,
                    'linkedin_url' => 'https://linkedin.com/' . $faker->userName,
                    'github_url' => 'https://github.com/' . $faker->userName,
                    'created_at' => $faker->dateTimeBetween($emploi->created_at, 'now'),
                    'updated_at' => now()
                ]);

                // 30% chance to add notes
                if ($faker->boolean(30)) {
                    $application->notes = $faker->paragraphs(2, true);
                    $application->save();
                }
            }

            // Each job seeker saves 3-10 jobs (some might overlap with applications)
            $savedJobs = $emplois->random(rand(3, 10));
            $chercheur->user->savedJobs()->attach(
                $savedJobs->pluck('id')->toArray(),
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
} 