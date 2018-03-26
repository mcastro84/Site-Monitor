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
        $this->app->bind('App\Repositories\Contracts\WebsiteMonitorServiceInterface', 'App\Repositories\WebsiteMonitorService');
        $this->app->bind('App\Repositories\Contracts\WebsiteMonitorRepositoryInterface', 'App\Repositories\WebsiteMonitorRepository');
    }
}