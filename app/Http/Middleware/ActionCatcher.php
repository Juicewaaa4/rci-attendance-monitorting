<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class ActionCatcher
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */ 
    public function handle(Request $request, Closure $next): Response
    {
        $protectedRoutes = [
            'process/login','process/logout',
            'process/create/account','process/edit/own/account','process/edit/other/account','process/confirm/password','process/change/password','process/delete/account',
            'process/send/top','process/confirm/otp',
            'process/create/department','process/update/department','process/delete/department',
            'process/create/program','process/update/program','process/delete/program',
            'process/create/year-level','process/update/year-level','process/delete/year-level',
            'process/create/school-year','process/update/school-year','process/delete/school-year',
            'process/create/section','process/edit/section','process/delete/section',
            'process/create/student-record','process/edit/student-record','process/delete/student-record','process/import/student-record',
            'process/login/rfid',
        ];

        if (in_array($request->path(), $protectedRoutes)) {
            if (!Auth::check()) {
                return Redirect::route('login')->with('error', 'Unauthorized access to an Action route');
            }
            return redirect()->back()->with('error', 'Unauthorized access to an Action route');
        }

        return $next($request);
    }
}

