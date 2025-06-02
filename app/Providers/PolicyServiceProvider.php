<?php

namespace App\Providers;

use App\Models\Employee;
use App\Models\Expense;
use App\Models\Sport;
use App\Models\Student;
use App\Policies\AcademyRecordPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class PolicyServiceProvider extends ServiceProvider
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
        Gate::policy(Student::class, AcademyRecordPolicy::class);
        Gate::policy(Employee::class, AcademyRecordPolicy::class);
        Gate::policy(Sport::class, AcademyRecordPolicy::class);
        Gate::policy(Expense::class, AcademyRecordPolicy::class);
    }
}
