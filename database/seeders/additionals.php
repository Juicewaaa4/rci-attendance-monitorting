<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Support\Str;
use App\Models\StudentRecord;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class additionals extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Section::create([
            'section_id' => 'b49bde58-5298-4568-b2d5-8b2216156d4b',
            'section_name' => '1-1',
            'section_description' => 'This is the  first year & first section of BSIS students.',
            'section_code' => 'BSIS11',
            'program_id' => '8d086be9-1a23-482c-9a9f-095fdb50864f', 
            'department_id' => 'ff0efb3d-d157-4464-8272-fe4eca8247da', 
            'year_level_id' => '59b999b2-6f67-4fc3-a240-cf03d1e05e2b', 
            'school_year_id' => 'f6f9d9bb-6bcb-45ac-b0f5-49c362ec8429', // 2024-2025
        ]);
    }
}
