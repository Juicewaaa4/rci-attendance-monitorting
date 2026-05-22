<?php

namespace App\Providers;

use App\Models\Account;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AccountServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer([
            'page.school.sections.edit', 
            'page.school.sections.create', 
            'page.school.students.create', 
            'page.school.students.edit', 
            'page.school.students.delete', 
            'page.school.students.edit-multiple', 
            'page.school.students.delete-multiple',
            'page.school.instructors.create', 
            'page.school.instructors.edit', 
            'page.school.instructors.delete',
        ], function ($view) {
            $instructors = Cache::remember('instructors', now()->addMinutes(30), function () {
                return Account::where('role', 'instructor')->get();
            });
        
            $view->with('instructors', $instructors);
        });
    }

    public function register()
    {
        //
    }
}
