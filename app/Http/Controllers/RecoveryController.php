<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Mail\OTPMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class RecoveryController
{
    public function sendOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Find the account with the given email
        $account = Account::where('email', $request->email)->first();

        if (!$account) {
            return back()->withErrors(['email' => 'No account found with this email.']);
        }

        // Generate a 6-digit OTP
        $otp = rand(100000, 999999);

        // Save OTP to the database
        $account->update(['otp' => $otp]);
        $account->update([
            'otp_expiry' => now()->addMinutes(5)
        ]);

        // Send OTP via email using Laravel Mailable
        Mail::to($account->email)->send(new OTPMail($otp));
        session(['recovery_email' => $request->email]);
        return back()->with('success', 'OTP sent to your email.')->withInput();
    }

    public function confirmOtp(Request $request)
    {
        // Validate the OTP field as a numeric string of exact length (6 digits)
        $request->validate([
            'otp_combined' => 'required|numeric|digits:6', // Ensure it's numeric and exactly 6 digits
        ]);
    
        // Retrieve the email stored in session
        $email = session('recovery_email');
    
        if (!$email) {
            return back()->withErrors(['otp' => 'Session expired. Please request a new OTP.']);
        }
    
        // Find the account with the provided OTP and stored email
        $account = Account::where('email', $email)->where('otp', $request->otp_combined)->first();
    
        if (!$account) {
            return back()->withErrors(['otp' => 'Invalid OTP'])->withInput();
        }
    
        // Check if OTP is expired
        if ($account->otp_expiry && now()->greaterThanOrEqualTo($account->otp_expiry)) {
            // OTP has expired, remove it from the database
            $account->update([
                'otp' => null,
                'otp_expiry' => null
            ]);
    
            return back()->withErrors(['otp' => 'Otp Expired, Request Another'])->withInput();
        }
    
        // If OTP is valid, allow user to proceed
        $account->update(['otp' => null, 'otp_expiry' => null]);
    
        return redirect('/Change/Password')->with('success', 'Tama OTP mo pre!')->withInput();
    }    
}
