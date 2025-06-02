<?php

namespace App\Providers;

use App\Policies\AcademyRecordPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('view-student', [AcademyRecordPolicy::class, 'view']);
        Gate::define('update-student', [AcademyRecordPolicy::class, 'update']);
        Gate::define('delete-student', [AcademyRecordPolicy::class, 'delete']);

        Gate::define('view-employee', [AcademyRecordPolicy::class, 'view']);
        Gate::define('update-employee', [AcademyRecordPolicy::class, 'update']);
        Gate::define('delete-employee', [AcademyRecordPolicy::class, 'delete']);

        Gate::define('view-expense', [AcademyRecordPolicy::class, 'view']);
        Gate::define('update-expense', [AcademyRecordPolicy::class, 'update']);
        Gate::define('delete-expense', [AcademyRecordPolicy::class, 'delete']);

        Gate::define('view-sport', [AcademyRecordPolicy::class, 'view']);
        Gate::define('update-sport', [AcademyRecordPolicy::class, 'update']);
        Gate::define('delete-sport', [AcademyRecordPolicy::class, 'delete']);
    }
}
