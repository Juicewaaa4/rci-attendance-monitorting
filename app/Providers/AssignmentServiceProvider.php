<?php

namespace App\Providers;

use App\Models\Assignment;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class AssignmentServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer(['*'], function ($view) { 
            $assignments = Cache::remember('assignments', now()->addMinutes(30), fn() => Assignment::all());
            $view->with('assignments', $assignments);
        });
    }    

    public function register()
    {
        //
    }
}
