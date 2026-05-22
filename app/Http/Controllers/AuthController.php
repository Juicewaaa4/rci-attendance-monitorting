<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Machine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController
{
    public function login(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string', // Either email or machine name
            'password' => 'required|string',
        ]);
    
        $credentials = $request->only('identifier', 'password');
    
        // First, check if it's an email (for Account) or machine_name (for Machine)
        if (filter_var($credentials['identifier'], FILTER_VALIDATE_EMAIL)) {
            // It's an account (email)
            $user = Account::where('email', $credentials['identifier'])->first();
            $guard = 'web'; // Use 'web' guard for Account
        } else {
            // It's a machine (machine_name)
            $user = Machine::where('machine_name', $credentials['identifier'])->first();
            $guard = 'machine'; // Use 'machine' guard for Machine
        }
    
        // Authenticate
        if ($user && Hash::check($credentials['password'], $user->password)) {
            // Login using the appropriate guard
            Auth::guard($guard)->login($user);
    
            // Redirect based on user type
            if ($user instanceof Machine) {
                return redirect()->intended('/Scan')->with('success', 'Login successful!');
            }
    
            return redirect()->intended('/Dashboard')->with('success', 'Login successful!');
        }
    
        // If credentials are invalid
        return back()->withErrors(['identifier' => 'Invalid credentials'])->withInput();
    }    
    

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        return redirect('/')->with('success', 'Logged out successfully.');
    }
}
