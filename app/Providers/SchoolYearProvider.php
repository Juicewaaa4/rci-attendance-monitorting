<?php

namespace App\Providers;

use App\Models\SchoolYear;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class SchoolYearProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        View::composer([
            'page.school.sections.edit', 
            'page.school.sections.create', 
            'page.school.students.create', 
            'page.school.students.edit', 
            'page.school.students.delete', 
            'page.school.students.edit-multiple', 
            'page.school.students.delete-multiple'
        ], function ($view) {
            $schoolyears = Cache::remember('schoolyears', now()->addMinutes(30), function () {
                return  SchoolYear::all(); 
            });
    
            $view->with('schoolyears', $schoolyears);
        });
    }
}
