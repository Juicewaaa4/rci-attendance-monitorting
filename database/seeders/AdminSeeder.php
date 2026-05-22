<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Account;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'example@gmail.com';

        // Only create admin if not already existing
        if (!Account::where('email', $email)->exists()) {
            Account::create([
                'account_id' => Str::uuid(),
                'first_name' => 'Admin',
                'last_name' => 'RCI',
                'email' => $email,
                'password' => Hash::make('159753'), // Default password
                'role' => 'Admin',
                'otp' => null,
            ]);
        }
    }
}
