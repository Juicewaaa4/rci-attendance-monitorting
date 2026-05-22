<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'department_id' => '9db8b20e-4f40-4c52-a5b3-8bceec9e67ed',
                'department_name' => '(DEPED) Senior High School',
                'department_code' => 'SHS',
                'department_description' => 'Department of Senior High School'
            ],
            [
                'department_id' => 'd9ac0a35-045e-49a4-ab2e-6295384d0cd3',
                'department_name' => '(TESDA) Technical Education and Skills Development Authority',
                'department_code' => 'TESDA',
                'department_description' => 'Technical Education and Skills Development Authority'
            ],
            [
                'department_id' => 'ff0efb3d-d157-4464-8272-fe4eca8247da',
                'department_name' => '(CHED) Commission on Higher Education',
                'department_code' => 'CHED',
                'department_description' => 'Commission on Higher Education'
            ],
        ];

        foreach ($departments as $dept) {
            if (!Department::where('department_id', $dept['department_id'])->exists()) {
                Department::create($dept);
            }
        }
    }
}
