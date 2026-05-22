<?php

namespace Database\Seeders;

use App\Models\YearLevel;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class YearLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Year Levels for Degree Programs
        YearLevel::create([
            'year_level_id' => '59b999b2-6f67-4fc3-a240-cf03d1e05e2b',
            'year_level_name' => 'Freshman',
            'year_level_description' => 'First year students in the program.',
            'year_level_code' => 'FR',
            'department_id' => 'ff0efb3d-d157-4464-8272-fe4eca8247da'
        ]);

        YearLevel::create([
            'year_level_id' => '6192391e-5f7c-45cb-b207-103abcd6c97e',
            'year_level_name' => 'Sophomore',
            'year_level_description' => 'Second year students in the program.',
            'year_level_code' => 'SO',
            'department_id' => 'ff0efb3d-d157-4464-8272-fe4eca8247da'
        ]);

        YearLevel::create([
            'year_level_id' => '04441070-8d5a-44cb-9ad2-728c10880b33',
            'year_level_name' => 'Junior',
            'year_level_description' => 'Third year students in the program.',
            'year_level_code' => 'JR',
            'department_id' => 'ff0efb3d-d157-4464-8272-fe4eca8247da'
        ]);

        YearLevel::create([
            'year_level_id' => '2aba5252-2316-41cb-b684-23297d4e52fd',
            'year_level_name' => 'Senior',
            'year_level_description' => 'Fourth year students in the program.',
            'year_level_code' => 'SR',
            'department_id' => 'ff0efb3d-d157-4464-8272-fe4eca8247da'
        ]);

        // Year Levels for TESDA Programs
        YearLevel::create([
            'year_level_id' => '73dd1099-a692-4715-b509-003ad375a105',
            'year_level_name' => 'NC I',
            'year_level_description' => 'National Certificate Level I',
            'year_level_code' => 'NC-I',
            'department_id' => 'd9ac0a35-045e-49a4-ab2e-6295384d0cd3'
        ]);

        YearLevel::create([
            'year_level_id' => '0d236c2d-a93f-4f7d-a2c1-a9df6f589b00',
            'year_level_name' => 'NC II',
            'year_level_description' => 'National Certificate Level II',
            'year_level_code' => 'NC-II',
            'department_id' => 'd9ac0a35-045e-49a4-ab2e-6295384d0cd3'
        ]);

        YearLevel::create([
            'year_level_id' => 'c6c1b744-621c-440e-8aa7-feef8d092ad2',
            'year_level_name' => 'NC III',
            'year_level_description' => 'National Certificate Level III',
            'year_level_code' => 'NC-III',
            'department_id' => 'd9ac0a35-045e-49a4-ab2e-6295384d0cd3'
        ]);

        YearLevel::create([
            'year_level_id' => '74d3368c-7a6d-4216-a4c7-60a25e019024',
            'year_level_name' => 'NC IV',
            'year_level_description' => 'National Certificate Level IV',
            'year_level_code' => 'NC-IV',
            'department_id' => 'd9ac0a35-045e-49a4-ab2e-6295384d0cd3'
        ]);

        // Year Levels for Short Courses
        YearLevel::create([
            'year_level_id' => 'db94f569-efd6-48b0-88df-4288d3e45683',
            'year_level_name' => 'Short Course I',
            'year_level_description' => 'First level of short courses.',
            'year_level_code' => 'SC-I',
            'department_id' => 'd9ac0a35-045e-49a4-ab2e-6295384d0cd3'
        ]);

        YearLevel::create([
            'year_level_id' => '1356f1dc-b283-4f46-be44-fe88ec88ae3b',
            'year_level_name' => 'Short Course II',
            'year_level_description' => 'Second level of short courses.',
            'year_level_code' => 'SC-II',
            'department_id' => 'd9ac0a35-045e-49a4-ab2e-6295384d0cd3'
        ]);

        YearLevel::create([
            'year_level_id' => 'f7a48665-cca4-4312-8365-38a03c0489b2',
            'year_level_name' => 'Short Course III',
            'year_level_description' => 'Third level of short courses.',
            'year_level_code' => 'SC-III',
            'department_id' => 'd9ac0a35-045e-49a4-ab2e-6295384d0cd3'
        ]);


        // Year Levels for Deped Programs
        YearLevel::create([
            'year_level_id' => '1f13fb85-d402-4bfe-81d3-130200b4864c',
            'year_level_name' => 'Grade 11',
            'year_level_description' => 'First year of Senior High School.',
            'year_level_code' => 'SHS11',
            'department_id' => '9db8b20e-4f40-4c52-a5b3-8bceec9e67ed'
        ]);

        YearLevel::create([
            'year_level_id' => 'd975c665-d977-4fd1-abe5-9efc5ef1e1e7',
            'year_level_name' => 'Grade 12',
            'year_level_description' => 'Second year of Senior High School.',
            'year_level_code' => 'SHS12',
            'department_id' => '9db8b20e-4f40-4c52-a5b3-8bceec9e67ed'
        ]);
    }
}
