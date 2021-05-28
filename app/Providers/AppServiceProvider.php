<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
//aggiunto per forzare https
use Illuminate\Support\Facades\URL;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        //aggiunto per forzare https
        URL::forceScheme('https');
    }
}
