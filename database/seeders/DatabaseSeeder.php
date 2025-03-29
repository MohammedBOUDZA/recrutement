<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        $this->call([
            JobCategoryAndSkillSeeder::class,  // First create categories and skills
            UserSeeder::class,                 // Then create users and their profiles
            EmploiSeeder::class,               // Then create jobs
            ApplicationSeeder::class,          // Finally create applications and saved jobs
        ]);

        Schema::enableForeignKeyConstraints();
    }
}
