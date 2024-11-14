<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
  //
        $this->app->bind('App\Repositories\Contracts\UserRepositoryInterface', 'App\Repositories\UserRepository');
        $this->app->bind('App\Repositories\Contracts\UserTypeRepositoryInterface', 'App\Repositories\UserTypeRepository');
        $this->app->bind('App\Repositories\Contracts\FeedbackRepositoryInterface', 'App\Repositories\FeedbackRepository');
        $this->app->bind('App\Repositories\Contracts\DownloadRepositoryInterface', 'App\Repositories\DownloadRepository');

    }
}
