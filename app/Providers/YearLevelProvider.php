<?php

namespace App\Providers;

use App\Models\YearLevel;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class YearLevelProvider extends ServiceProvider
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
            'page.school.students.delete-multiple',
            'page.school.reports.index', // <- Make sure to include your attendance view here
        ], function ($view) {
            $departmentId = request('department_id');

            $yearlevels = $departmentId
                ? YearLevel::where('department_id', $departmentId)->get()
                : YearLevel::all();

            // Pass the paginated yearlevels to the view
            $view->with('yearlevels', $yearlevels);
        });
    }
}
