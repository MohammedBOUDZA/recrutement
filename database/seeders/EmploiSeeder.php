<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Emploi;
use App\Models\Entreprise;
use App\Models\JobCategory;
use App\Models\Skill;
use Faker\Factory as Faker;

class EmploiSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('fr_FR');
        $entreprises = Entreprise::all();
        $categories = JobCategory::all();
        $skills = Skill::all();

        $employmentTypes = ['full-time', 'part-time', 'contract', 'temporary', 'internship'];
        $experienceLevels = ['entry', 'junior', 'mid-level', 'senior', 'lead'];
        $educationLevels = ['High School', 'Bachelor', 'Master', 'PhD'];

        foreach ($entreprises as $entreprise) {
            // Each company posts 1-5 jobs
            $numJobs = $faker->numberBetween(1, 5);
            
            for ($i = 0; $i < $numJobs; $i++) {
                $salaryMin = $faker->numberBetween(30000, 80000);
                $salaryMax = $faker->numberBetween($salaryMin + 10000, $salaryMin + 50000);
                
                $emploi = Emploi::create([
                    'entreprise_id' => $entreprise->id,
                    'title' => $faker->jobTitle,
                    'description' => $faker->paragraphs(4, true),
                    'requirements' => $faker->paragraphs(2, true),
                    'benefits' => $faker->paragraphs(2, true),
                    'location' => $faker->city,
                    'remote' => $faker->boolean(30),
                    'hybrid' => $faker->boolean(40),
                    'salary_min' => $salaryMin,
                    'salary_max' => $salaryMax,
                    'salary_type' => 'yearly',
                    'employment_type' => $faker->randomElement($employmentTypes),
                    'experience_level' => $faker->randomElement($experienceLevels),
                    'education_level' => $faker->randomElement($educationLevels),
                    'urgent' => $faker->boolean(10),
                    'featured' => $faker->boolean(20),
                    'status' => 'active',
                    'expires_at' => $faker->dateTimeBetween('+1 month', '+3 months'),
                    'created_at' => $faker->dateTimeBetween('-2 months', 'now'),
                    'updated_at' => now()
                ]);

                // Attach 1-3 random categories
                $emploi->categories()->attach(
                    $categories->random(rand(1, 3))->pluck('id')->toArray()
                );

                // Attach 3-8 random skills with random proficiency levels
                $randomSkills = $skills->random(rand(3, 8));
                foreach ($randomSkills as $skill) {
                    $emploi->skills()->attach($skill->id, [
                        'level' => $faker->randomElement(['beginner', 'intermediate', 'advanced', 'expert'])
                    ]);
                }

                // Add 2-5 screening questions
                $questions = [];
                $numQuestions = $faker->numberBetween(2, 5);
                for ($j = 0; $j < $numQuestions; $j++) {
                    $questions[] = $faker->sentence . '?';
                }
                $emploi->questions = json_encode($questions);
                $emploi->save();
            }
        }
    }
} 