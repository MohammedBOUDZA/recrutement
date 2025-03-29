<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\JobCategory;
use App\Models\Skill;

class JobCategoryAndSkillSeeder extends Seeder
{
    public function run(): void
    {
        // Job Categories
        $categories = [
            'Development' => ['Web Development', 'Mobile Development', 'Software Engineering', 'DevOps', 'Database Administration'],
            'Design' => ['UI Design', 'UX Design', 'Graphic Design', 'Product Design', 'Motion Design'],
            'Marketing' => ['Digital Marketing', 'Content Marketing', 'SEO', 'Social Media', 'Email Marketing'],
            'Sales' => ['Business Development', 'Account Management', 'Sales Management', 'Inside Sales', 'Outside Sales'],
            'Management' => ['Project Management', 'Product Management', 'Team Management', 'Operations Management'],
            'Finance' => ['Accounting', 'Financial Analysis', 'Investment Banking', 'Risk Management'],
            'Human Resources' => ['Recruitment', 'HR Management', 'Training', 'Compensation & Benefits'],
            'Customer Service' => ['Customer Support', 'Technical Support', 'Account Support', 'Client Success'],
            'Data' => ['Data Analysis', 'Data Science', 'Business Intelligence', 'Machine Learning'],
            'Legal' => ['Corporate Law', 'Legal Counsel', 'Compliance', 'Contract Management']
        ];

        foreach ($categories as $mainCategory => $subCategories) {
            $parent = JobCategory::create(['name' => $mainCategory]);
            foreach ($subCategories as $subCategory) {
                JobCategory::create([
                    'name' => $subCategory,
                    'parent_id' => $parent->id
                ]);
            }
        }

        // Skills
        $skills = [
            // Programming Languages
            'PHP', 'JavaScript', 'Python', 'Java', 'C#', 'Ruby', 'Go', 'Swift', 'Kotlin',
            // Frameworks
            'Laravel', 'React', 'Vue.js', 'Angular', 'Django', 'Spring Boot', 'ASP.NET',
            // Databases
            'MySQL', 'PostgreSQL', 'MongoDB', 'Redis', 'Elasticsearch',
            // Tools & Platforms
            'Docker', 'Kubernetes', 'AWS', 'Git', 'Jenkins', 'Jira',
            // Design
            'Figma', 'Adobe XD', 'Photoshop', 'Illustrator', 'Sketch',
            // Marketing
            'Google Analytics', 'SEO', 'Content Marketing', 'Social Media Marketing',
            // Soft Skills
            'Communication', 'Leadership', 'Problem Solving', 'Team Management',
            // Business
            'Project Management', 'Agile', 'Scrum', 'Business Analysis',
            // Data
            'SQL', 'Data Analysis', 'Machine Learning', 'Power BI', 'Tableau'
        ];

        foreach ($skills as $skillName) {
            Skill::create(['name' => $skillName]);
        }
    }
} 