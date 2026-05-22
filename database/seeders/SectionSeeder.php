<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SectionSeeder extends Seeder
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

        Section::create([
            'section_id' => '011a0295-00ea-4e3e-babb-204c3f0e36e4',
            'section_name' => '1-1',
            'section_description' => 'This is the first year & first section of BSCRIM students.',
            'section_code' => 'BSCRIM11',
            'program_id' => 'd498fcf5-2d18-4d9e-984b-32314bac8f3a', 
            'department_id' => 'ff0efb3d-d157-4464-8272-fe4eca8247da', 
            'year_level_id' => '59b999b2-6f67-4fc3-a240-cf03d1e05e2b', 
            'school_year_id' => 'f6f9d9bb-6bcb-45ac-b0f5-49c362ec8429', // 2024-2025
        ]);


        Section::create([
            'section_id' =>'6f7123dd-0220-44b8-9de5-6e2df8cf10c8',
            'section_name' => '3-1',
            'section_description' => 'This is the third year & first section of BSIS students.',
            'section_code' => 'BSIS31',
            'program_id' => '8d086be9-1a23-482c-9a9f-095fdb50864f', 
            'department_id' => 'ff0efb3d-d157-4464-8272-fe4eca8247da', 
            'year_level_id' => '04441070-8d5a-44cb-9ad2-728c10880b33', 
            'school_year_id' => 'f6f9d9bb-6bcb-45ac-b0f5-49c362ec8429', // 2024-2025
        ]);

        Section::create([
            'section_id' => '5b0862fe-e1bb-486f-88b9-16bdc885f5f0',
            'section_name' => '3-2',
            'section_description' => 'This is the third year & second section of BSIS students.',
            'section_code' => 'BSIS32',
            'program_id' => '8d086be9-1a23-482c-9a9f-095fdb50864f', 
            'department_id' => 'ff0efb3d-d157-4464-8272-fe4eca8247da', 
            'year_level_id' => '04441070-8d5a-44cb-9ad2-728c10880b33', 
            'school_year_id' => 'f6f9d9bb-6bcb-45ac-b0f5-49c362ec8429', // 2024-2025
        ]);
    }
}
