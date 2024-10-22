<?php

namespace App\Providers;

use App\Models\CourseSemester;
use App\Models\Course;
use App\Models\Module;
use App\Policies\CourseSemesterPolicy;
use App\Policies\CoursePolicy;
use App\Policies\ModulePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // Define your model policies here
        Course::class => CoursePolicy::class,
        Module::class => ModulePolicy::class,
        CourseSemester::class => CourseSemesterPolicy::class,
    ];

    public function boot()
    {
        $this->registerPolicies();
    }
}
