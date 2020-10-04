<?php

namespace RensPhilipsen\NSApi\Resources;

class RouteStation
{

    /**
     * UIC Code of the route station
     *
     * @var string
     */
    public $uicCode;

    /**
     * Medium name of the route station
     *
     * @var string
     */
    public $mediumName;


    public function __construct(array $attributes)
    {
        $this->fill($attributes);
        unset($this->attributes);
    }

    public function fill(array $attributes)
    {
        $this->uicCode = $attributes['uicCode'];
        $this->mediumName = $attributes['mediumName'];
    }
}