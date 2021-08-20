<?php

namespace LeMatosDeFuk;

use Illuminate\Support\ServiceProvider;
use LeMatosDeFuk\Notifications\Delete;
use Livewire\Livewire;

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
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'lematosdefuk');

        $this->publishes([
                             __DIR__ . '/../resources/views' => resource_path('views/vendor/lematosdefuk'),
                         ], 'lematosdefuk-views');

        Livewire::component('lematosdefuk::delete', Delete::class);
    }

}