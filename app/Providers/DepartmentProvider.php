<?php

namespace App\Providers;

use App\Models\Department;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class DepartmentProvider extends ServiceProvider
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
            'page.school.programs.create',
            'page.school.programs.edit',
            'page.school.sections.edit', 
            'page.school.sections.create', 
            'page.school.year-levels.create',
            'page.school.year-levels.edit',
            'page.school.students.create', 
            'page.school.students.edit', 
            'page.school.students.delete', 
            'page.school.students.edit-multiple', 
            'page.school.students.delete-multiple',
            'page.school.instructors.create', 
            'page.school.instructors.edit', 
            'page.school.instructors.delete',
        ], function ($view) { 
            $departments = Cache::remember('departments', now()->addMinutes(30), fn() => Department::all());
            $view->with('departments', $departments);
        });
    }
}
