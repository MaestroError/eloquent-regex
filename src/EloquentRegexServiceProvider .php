<?php

namespace Maestroerror\EloquentRegex;

use Illuminate\Support\ServiceProvider;

class EloquentRegexServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('eloquentregex', function ($app) {
            return new \Maestroerror\EloquentRegex\EloquentRegex();
        });
    }

    public function boot()
    {
        // booting code
    }
}
