<?php

namespace Database\Seeders;

use App\Models\Program;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Degree Programs
        Program::create([
            'program_id' => '4dce2ca1-457c-488f-8c22-4087a3e0c62b',
            'program_name' => 'Bachelor of Science in Tourism Management', 
            'program_description' => 'A program focused on tourism planning, development, management, and business.',
            'program_code' => 'BSTM',
            'department_id' => 'ff0efb3d-d157-4464-8272-fe4eca8247da'
        ]);

        Program::create([
            'program_id' => '8d086be9-1a23-482c-9a9f-095fdb50864f',
            'program_name' => 'Bachelor of Science in Information Systems',
            'program_description' => 'A program focused on the integration of information technology solutions and business processes.',
            'program_code' => 'BSIS',
            'department_id' => 'ff0efb3d-d157-4464-8272-fe4eca8247da'
        ]);

        Program::create([
            'program_id' => '851eac2d-044f-4796-9308-15f7ed9a62a9',
            'program_name' => 'Bachelor of Science in Applied Information Science',
            'program_description' => 'A program focusing on practical applications of information science and data systems.',
            'program_code' => 'BSAIS',
            'department_id' => 'ff0efb3d-d157-4464-8272-fe4eca8247da'
        ]);

        Program::create([
            'program_id' => '8f3697e3-c267-4b19-92f4-76094f127f62',
            'program_name' => 'Bachelor of Science in Nursing',
            'program_description' => 'A program that prepares students to become registered nurses and provide health care.',
            'program_code' => 'BSNURSING',
            'department_id' => 'ff0efb3d-d157-4464-8272-fe4eca8247da'
        ]);

        Program::create([
            'program_id' => 'd498fcf5-2d18-4d9e-984b-32314bac8f3a',
            'program_name' => 'Bachelor of Science in Criminology',
            'program_description' => 'A program that studies criminal behavior, law enforcement, and the justice system.',
            'program_code' => 'BSCRIM',
            'department_id' => 'ff0efb3d-d157-4464-8272-fe4eca8247da'
        ]);

        
        Program::create([
            'program_id' => 'a062d667-91f4-4760-9368-e10ed0084be7',
            'program_name' => 'Bachelor in Early Childhood Education',
            'program_description' => 'A program that prepares students for a career in education, focusing on early childhood education.',
            'program_code' => 'BECED',
            'department_id' => 'ff0efb3d-d157-4464-8272-fe4eca8247da'
        ]);

        Program::create([
            'program_id' => '07cbdf3e-73eb-45ee-9ac8-261cef31872c',
            'program_name' => 'Bachelor of Technical-Vocational Teacher Education',
            'program_description' => 'A program that prepares students for a career in technical-vocational education.',
            'program_code' => 'BTVTED',
            'department_id' => 'ff0efb3d-d157-4464-8272-fe4eca8247da'
        ]);

        Program::create([
            'program_id' => '92911195-b857-4b46-a12e-77825eb16b9c',
            'program_name' => 'Bachelor of Science in Civil Engineering',
            'program_description' => 'A program that prepares students for a career in civil engineering, focusing on infrastructure and construction.',
            'program_code' => 'BSCE',
            'department_id' => 'ff0efb3d-d157-4464-8272-fe4eca8247da'
        ]);

        
        Program::create([
            'program_id' => '8c8a8bfe-e217-4388-8b67-65879b059204',
            'program_name' => 'Bachelor of Science in Entrepreneurship',
            'program_description' => 'A program that focuses on developing entrepreneurial skills and business management.',
            'program_code' => 'BSENTREP',
            'department_id' => 'ff0efb3d-d157-4464-8272-fe4eca8247da'
        ]);

        // TESDA Courses
        // Preset Department ID for TESDA 'd9ac0a35-045e-49a4-ab2e-6295384d0cd3'
        Program::create([
            'program_id' => '7108c928-272d-4e7f-8912-5cc13ff4c88f',
            'program_name' => 'NC II Cookery',
            'program_description' => 'A TESDA-accredited course that covers core competencies required to prepare hot meals and work in a commercial kitchen environment.',
            'program_code' => 'TESDA-NCII-COOK',
            'department_id' => 'd9ac0a35-045e-49a4-ab2e-6295384d0cd3'
        ]);

        Program::create([
            'program_id' => 'b4d22fa5-07e3-419f-8595-6124b4a76678',
            'program_name' => 'NC II Bread and Pastry Production',
            'program_description' => 'TESDA course focused on preparing and producing bakery and pastry products.',
            'program_code' => 'TESDA-NCII-BPP',
            'department_id' => 'd9ac0a35-045e-49a4-ab2e-6295384d0cd3'
        ]);

        // DepEd SHS Programs (Grades 11–12 Tracks)
        // Preset Department ID for SHS '9db8b20e-4f40-4c52-a5b3-8bceec9e67ed'
        Program::create([
            'program_id' => 'cb9c1b50-9590-447c-9cf5-23471f3d729a',
            'program_name' => 'Academic Track – STEM',
            'program_description' => 'Senior High School academic track focusing on Science, Technology, Engineering, and Mathematics.',
            'program_code' => 'SHS-ACAD-STEM',
            'department_id' => '9db8b20e-4f40-4c52-a5b3-8bceec9e67ed'
        ]);

        Program::create([
            'program_id' => 'acea45d7-5151-4f98-b9c5-de53fd6f7056',
            'program_name' => 'Academic Track – ABM',
            'program_description' => 'Senior High School academic track focusing on Accountancy, Business, and Management.',
            'program_code' => 'SHS-ACAD-ABM',
            'department_id' => '9db8b20e-4f40-4c52-a5b3-8bceec9e67ed'
        ]);
    }
}
