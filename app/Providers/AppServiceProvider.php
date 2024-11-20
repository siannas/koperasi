<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\ServiceProvider;

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
        \App\Jurnal::observe(\App\Observers\JurnalObserver::class);

        Carbon::setLocale(config('app.locale'));

        view()->composer('*', function ($view) {
            $view->with('tipe', \App\Tipe::all());
        });
    }
}
