<?php

namespace Database\Seeders;

use App\Models\SchoolYear;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SchoolYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
                
        SchoolYear::create([
            'school_year_id' => 'd76bc851-ce25-4203-b31c-c00dc6ad92e1',
            'school_year_name' => '2018-2019',
            'school_year_description' => '2018-2019 School Year',
            'school_year_code' => 'SY20182019',
        ]);
        
        SchoolYear::create([
            'school_year_id' => 'dab04ad0-0a27-4030-aad7-ad0a06cdeeee',
            'school_year_name' => '2019-2020',
            'school_year_description' => '2019-2020 School Year',
            'school_year_code' => 'SY20192020',
        ]);
        
        SchoolYear::create([
            'school_year_id' => 'f96159e3-9832-4534-b540-fcf4d1625e68',
            'school_year_name' => '2020-2021',
            'school_year_description' => '2020-2021 School Year',
            'school_year_code' => 'SY20202021',
        ]);
        
        SchoolYear::create([
            'school_year_id' => 'dfeadf3e-7b44-4b18-9cc1-601247266d2e',
            'school_year_name' => '2021-2022',
            'school_year_description' => '2021-2022 School Year',
            'school_year_code' => 'SY20212022',
        ]);
        
        SchoolYear::create([
            'school_year_id' => 'fcf3a1df-89f8-4115-b9b8-faccb6278a49',
            'school_year_name' => '2022-2023',
            'school_year_description' => '2022-2023 School Year',
            'school_year_code' => 'SY20222023',
        ]);
        
        SchoolYear::create([
            'school_year_id' => '9de351e1-7aa1-4dad-91c5-b8c86efc278b',
            'school_year_name' => '2023-2024',
            'school_year_description' => '2023-2024 School Year',
            'school_year_code' => 'SY20232024',
        ]);
        
        SchoolYear::create([
            'school_year_id' => 'f6f9d9bb-6bcb-45ac-b0f5-49c362ec8429',
            'school_year_name' => '2024-2025',
            'school_year_description' => '2024-2025 School Year',
            'school_year_code' => 'SY20242025',
        ]);
    }
}
