<?php

namespace App\Providers;

use App\Models\City;
use App\Models\ClassModel;
use App\Models\Department;
use App\Models\Faculty;
use App\Models\Group;
use App\Models\University;
use App\Repositories\City\CityRepository;
use App\Repositories\Department\DepartmentRepository;
use App\Repositories\Faculty\FacultyRepository;
use App\Repositories\Group\GroupRepository;
use App\Repositories\ModelClass\ModelClassRepository;
use App\Repositories\Student\StudentRepository;
use App\Repositories\University\UniversityRepository;
use App\Services\City\CityService;
use App\Services\Department\DepartmentService;
use App\Services\Faculty\FacultyService;
use App\Services\Group\GroupService;
use App\Services\ModelClass\ModelClassService;
use App\Services\Student\StudentService;
use App\Services\University\UniversityService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
      // 🔹 Service - Repository binding'leri
        $this->app->bind(CityService::class, function ($app) {
            return new CityService(new CityRepository(new City()));
        });

        $this->app->bind(UniversityService::class, function ($app) {
            return new UniversityService(new UniversityRepository(new University()));
        });

        $this->app->bind(FacultyService::class, function ($app) {
            return new FacultyService(new FacultyRepository(new Faculty()));
        });

        $this->app->bind(DepartmentService::class, function ($app) {
            return new DepartmentService(new DepartmentRepository(new Department()));
        });

        $this->app->bind(ModelClassService::class, function ($app) {
            return new ModelClassService(new ModelClassRepository(new ClassModel()));
        });

        $this->app->bind(GroupService::class, function ($app) {
            return new GroupService(new GroupRepository(new Group()));
        });

          $this->app->bind(StudentService::class, function ($app) {
            return new StudentService($app->make(StudentRepository::class));
        });

        // Geliştirici ortamında debug helper aktif et (opsiyonel)
        if ($this->app->environment('local')) {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
