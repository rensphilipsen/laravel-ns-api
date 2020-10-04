<?php

namespace Rensphilipsen\NSApi\Actions;

use RensPhilipsen\NSApi\Resources\Departure;
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

    /**
     * Get all departures by station code
     *
     * @param string $code
     * @param int $maxJourneys
     * @return mixed
     */
    public function getDeparturesByStationCode(string $code, int $maxJourneys = 32)
    {
        return $this->transformCollection(
            $this->get("departures?station=$code&maxJourneys=$maxJourneys")['departures'],
            Departure::class
        );
    }

}