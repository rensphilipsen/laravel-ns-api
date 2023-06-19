<?php

namespace Rensphilipsen\NSApi\Actions;

use RensPhilipsen\NSApi\MakesHttpRequests;
use RensPhilipsen\NSApi\TransformsCollections;
use RensPhilipsen\NSApi\Resources\Arrival;
use RensPhilipsen\NSApi\Resources\Departure;
use RensPhilipsen\NSApi\Resources\Station;

trait ManagesStations
{
    use MakesHttpRequests, TransformsCollections;

    /**
     * Get all stations
     *
     * @return mixed
     */
    public function getStations(int $limit = 0)
    {
        return $this->transform(
            $this->get("stations?limit=$limit"),
            Station::class
        );
    }

    /**
     * Get all arrivals by station code
     *
     * @param string $code
     * @param int $maxJourneys
     * @return mixed
     */
    public function getArrivalsByStationCode(string $code, int $maxJourneys = 32)
    {
        return $this->transform(
            $this->get("arrivals?station=$code&maxJourneys=$maxJourneys")['arrivals'],
            Arrival::class
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
        return $this->transform(
            $this->get("departures?station=$code&maxJourneys=$maxJourneys")['departures'],
            Departure::class
        );
    }
}
