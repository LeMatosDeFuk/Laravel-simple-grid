<?php

namespace LeMatosDeFuk;

use Illuminate\Support\ServiceProvider;

/**
 * @author Matěj Černý <me@cernymatej.cz>
 */
class GridServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'LeMatosDeFuk');

        $this->publishes([
                             __DIR__.'/../resources/views' => resource_path('views/vendor/LeMatosDeFuk'),
                         ]);
    }

}