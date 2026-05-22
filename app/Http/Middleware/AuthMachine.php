<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthMachine
    {
        /**
         * Handle an incoming request.
         *
         * @param  \Illuminate\Http\Request  $request
         * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
         * @return \Symfony\Component\HttpFoundation\Response
         */
        public function handle(Request $request, Closure $next): Response
        {
            // Check if the machine is authenticated using the 'machine' guard
            if (!Auth::guard('machine')->check()) {
                return redirect()->route('login')->with('error', 'You are not a Machine');
            }
    
            // Proceed with the request if authenticated
            return $next($request);
        }
    }