<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmploisTableSeeder extends Seeder
{
    public function run()
    {
        $entrepriseId = DB::table('entreprises')->first()->id;
        
        DB::table('emplois')->insert([
            [
                'entreprise_id' => $entrepriseId,
                'title' => 'Senior PHP Developer',
                'description' => 'We are looking for an experienced PHP developer with Laravel expertise.',
                'location' => 'Paris',
                'salary' => 55000,
                'emploi_type' => 'full-time',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Add more jobs as needed
        ]);
    }
}