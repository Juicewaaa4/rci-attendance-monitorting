<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            DepartmentSeeder::class,
            MachineSeeder::class,
            ProgramSeeder::class,
            YearLevelSeeder::class,
            SchoolYearSeeder::class,
            SectionSeeder::class,
            StudentSeeder::class,
            InstructorSeeder::class,
        ]);

    }
}


//php artisan db:seed --class=DatabaseSeeder