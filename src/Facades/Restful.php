<?php

namespace KFoobar\Restful\Facades;

use Illuminate\Support\Facades\Facade;

class Restful extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return \KFoobar\Restful\LaravelRestful::class;
    }
}
