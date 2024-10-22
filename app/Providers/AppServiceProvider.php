<?php

namespace App\Providers;

use App\Repositories\CourseSemesterRepository;
use App\Repositories\CourseRepository;
use App\Repositories\ModuleRepository;
use App\Repositories\RepositoryInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(RepositoryInterface::class, CourseRepository::class);
        $this->app->bind(RepositoryInterface::class, ModuleRepository::class);
        $this->app->bind(RepositoryInterface::class, CourseSemesterRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
