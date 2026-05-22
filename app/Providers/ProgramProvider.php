<?php

namespace App\Providers;

use App\Models\Program;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class ProgramProvider extends ServiceProvider
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
            // 'page.school.students.create',
            'page.school.students.edit',
            'page.school.students.delete',
            'page.school.students.edit-multiple',
            'page.school.students.delete-multiple',
            'page.school.instructors.create',
            'page.school.instructors.edit',
            'page.school.instructors.delete',
            'page.school.reports.index', // <- Make sure to include your attendance view here
        ], function ($view) {
            $departmentId = request('department_id');

            $programs = $departmentId
                ? Program::where('department_id', $departmentId)->get()
                : Program::all();

            $view->with([
                'programs' => $programs,
            ]);
        });
    }
}
