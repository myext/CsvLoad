<?php

namespace Zvg\CsvLoad;

use Illuminate\Support\ServiceProvider;

class CsvLoadServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->publishes([
            __DIR__.'/../views' => resource_path('views/vendor/zvg'),
        ]);

        $this->publishes([
            __DIR__.'/config' => base_path('config'),
        ]);

        $this->publishes([
            __DIR__.'/../img' => public_path('zvg/img'),
        ]);

        $this->publishes([
            __DIR__.'/../lang' => resource_path('lang/vendor/zvg'),
        ]);

        require __DIR__ . '/Http/routes.php';

        $this->loadViewsFrom(__DIR__.'/../views', 'zvg');

        $this->mergeConfigFrom(__DIR__ . '/config/zvg.php', 'zvg');

        $this->loadTranslationsFrom(__DIR__.'/../lang', 'zvg');
    }

    public function register()
    {
        //
    }
}
