<?php

namespace App\Providers;

use App\Models\Section;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class SectionProvider extends ServiceProvider
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
            'page.school.instructors.create', 
            'page.school.instructors.edit', 
            'page.school.instructors.delete',
        ], function ($view) {
            $sections = Cache::remember('sections', now()->addMinutes(30), function () {
                return  Section::all(); 
            });
    
            $view->with('sections', $sections);
        });
    }
}
