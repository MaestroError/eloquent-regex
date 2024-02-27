<?php

namespace Maestroerror\EloquentRegex\Facades;

use Illuminate\Support\Facades\Facade;

class EloquentRegex extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'eloquentregex';
    }
}
