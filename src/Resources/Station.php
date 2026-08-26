<?php

namespace RensPhilipsen\NSApi\Resources;

class Station
{
    /**
     * Code of the station
     *
     * @var string
     */
    public $code;

    /**
     * UICCode of the station
     *
     * @var int
     */
    public $uicCode;

    /**
     * EVACode of the station
     *
     * @var int
     */
    public $evaCode;

    /**
     * Long name of the station
     *
     * @var string
     */
    public $longName;

    /**
     * Short name of the station
     *
     * @var string
     */
    public $shortName;

    /**
     * Name of the station
     *
     * @var string
     */
    public $name;

    /**
     * Does the station have facilities
     *
     * @var bool
     */
    public $hasFacilities;

    /**
     * Does the station have departures
     *
     * @var bool
     */
    public $hasDepartures;

    /**
     * Does the station have travel assistance
     *
     * @var bool
     */
    public $hasTravelAssistance;

    /**
     * Country of the station
     *
     * @var string
     */
    public $country;

    /**
     * Latitude of the station
     *
     * @var float
     */
    public $latitude;

    /**
     * Longitude of the station
     *
     * @var float
     */
    public $longitude;


    public function __construct(array $attributes)
    {
        $this->fill($attributes);
        unset($this->attributes);
    }

    public function fill(array $attributes)
    {
        $names = $attributes['namen'] ?? [];

        $this->code = $attributes['code'] ?? null;
        $this->uicCode = isset($attributes['UICCode']) ? (int) $attributes['UICCode'] : null;
        $this->evaCode = isset($attributes['EVACode']) ? (int) $attributes['EVACode'] : null;
        $this->longName = $names['lang'] ?? null;
        $this->shortName = $names['kort'] ?? null;
        $this->name = $names['middel'] ?? null;
        $this->hasFacilities = (bool) ($attributes['heeftFaciliteiten'] ?? false);
        $this->hasDepartures = (bool) ($attributes['heeftVertrektijden'] ?? false);
        $this->hasTravelAssistance = (bool) ($attributes['heeftReisassistentie'] ?? false);
        $this->country = $attributes['land'] ?? null;
        $this->latitude = $attributes['lat'] ?? null;
        $this->longitude = $attributes['lng'] ?? null;
    }
}
