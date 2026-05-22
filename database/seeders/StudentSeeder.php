<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use App\Models\StudentRecord;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StudentRecord::create([
            'record_id' => Str::uuid(),
            'student_id' => '0987654321',
            'first_name' => 'John',
            'middle_name' => 'Doe',
            'last_name' => 'Smith',
            'school_year_id' => 'f6f9d9bb-6bcb-45ac-b0f5-49c362ec8429', //2024-2025
            'section_id' => '5b0862fe-e1bb-486f-88b9-16bdc885f5f0', // BSIS 3-2
            'year_level_id' => '04441070-8d5a-44cb-9ad2-728c10880b33', // 3rd Year
            'program_id' => '8d086be9-1a23-482c-9a9f-095fdb50864f',  // BSIS
            'department_id' => 'ff0efb3d-d157-4464-8272-fe4eca8247da',  // Ched
            'rfid' => '1234567890'
        ]);
        StudentRecord::create(
            [
                'record_id' => Str::uuid(),
                'student_id' => '1234567890',
                'first_name' => 'Jane',
                'middle_name' => 'Doe',
                'last_name' => 'Smith',
                'school_year_id' => 'f6f9d9bb-6bcb-45ac-b0f5-49c362ec8429', //2024-2025
                'section_id' => '5b0862fe-e1bb-486f-88b9-16bdc885f5f0', // BSIS 3-2
                'year_level_id' => '04441070-8d5a-44cb-9ad2-728c10880b33', // 3rd Year
                'program_id' => '8d086be9-1a23-482c-9a9f-095fdb50864f',  // BSIS
                'department_id' => 'ff0efb3d-d157-4464-8272-fe4eca8247da',  // Ched
                'rfid' => '0987654321'
            ]
        );
        StudentRecord::create(
            [
                'record_id' => Str::uuid(),
                'student_id' => '2032-ffas',
                'first_name' => 'Jonathan',
                'middle_name' => 'Fernando',
                'last_name' => 'Bonifacio',
                'school_year_id' => 'f6f9d9bb-6bcb-45ac-b0f5-49c362ec8429', //2024-2025
                'section_id' => '011a0295-00ea-4e3e-babb-204c3f0e36e4', // CRIM 1-1
                'year_level_id' => '59b999b2-6f67-4fc3-a240-cf03d1e05e2b', // 1st Year
                'program_id' => 'd498fcf5-2d18-4d9e-984b-32314bac8f3a',  // CRIM
                'department_id' => 'ff0efb3d-d157-4464-8272-fe4eca8247da',  // Ched
                'rfid' => '456456'
            ]
        );
    }
}
