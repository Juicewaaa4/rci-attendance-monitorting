<?php

namespace Database\Seeders;

use App\Models\Machine;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MachineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Machines
        Machine::create([
            'machine_id' => Str::uuid(),
            'machine_name' => 'Machine1',
            'password' => Hash::make('159753'), // Default password
        ]);
    }
}
