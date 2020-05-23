<?php

namespace Rensphilipsen\NSApi\Actions;

use RensPhilipsen\NSApi\Resources\Station;

trait ManagesStations
{

    /**
     * Get all stations
     *
     * @return mixed
     */
    public function getStations()
    {
        return $this->transformCollection(
            $this->get('stations'),
            Station::class
        );
    }

}