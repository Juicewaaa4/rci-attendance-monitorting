<?php

namespace App\Providers;

use App\Models\StudentRecord;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class StudentProvider extends ServiceProvider
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
            $students = Cache::remember('students', now()->addMinutes(30), function () {
                return StudentRecord::All();
            });
        
            $view->with('students', $students);
        });        
    }
}
