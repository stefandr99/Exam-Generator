<?php

namespace App\Providers;

use App\Repository\Eloquent\CourseRepository;
use App\Repository\Eloquent\DidacticRepository;
use App\Repository\Eloquent\ExamRepository;
use App\Repository\Eloquent\UserRepository;
use App\Repository\Interfaces\ICourseRepository;
use App\Repository\Interfaces\IDidacticRepository;
use App\Repository\Interfaces\IExamRepository;
use App\Repository\Interfaces\ISubjectRepository;
use App\Repository\Interfaces\IUserRepository;
use App\Repository\Eloquent\SubjectRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ICourseRepository::class, CourseRepository::class);
        $this->app->bind(IDidacticRepository::class, DidacticRepository::class);
        $this->app->bind(IExamRepository::class, ExamRepository::class);
        $this->app->bind(ISubjectRepository::class, SubjectRepository::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
