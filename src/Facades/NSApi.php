<?php

namespace RensPhilipsen\NSApi\Facades;

use Illuminate\Support\Facades\Facade;

class NSApi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'ns-api';
    }
}