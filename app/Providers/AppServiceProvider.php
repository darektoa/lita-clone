<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Xendit\Xendit;

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
        $xenditKey  = env('XENDIT_MODE') === 'production' ? env('XENDIT_KEY') : env('XENDIT_KEY_DEV');

        Paginator::useBootstrap();
        Xendit::setApiKey($xenditKey);
    }
}
