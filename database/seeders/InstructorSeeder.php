<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Assignment;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class InstructorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Account::create([
            'account_id' => 'b49bde58-5298-4568-b2d5-8b2216156d4b',
            'first_name' => 'John Victor',
            'middle_name' => 'Dela Cruz',
            'last_name' => 'Henson',
            'email' => 'hensonjohnvictor@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'Instructor',
        ]);

        Assignment::create([
            'assignment_id' => Str::uuid(),
            'instructor_id' => 'b49bde58-5298-4568-b2d5-8b2216156d4b',
            'section_id' => '5b0862fe-e1bb-486f-88b9-16bdc885f5f0',
            'program_id' => '8d086be9-1a23-482c-9a9f-095fdb50864f',
            'department_id' => 'ff0efb3d-d157-4464-8272-fe4eca8247da',
        ]);
    }
}
