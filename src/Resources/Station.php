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
     * @var integer
     */
    public $uicCode;

    /**
     * EVACode of the station
     *
     * @var integer
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
     * @var double
     */
    public $latitude;

    /**
     * Longitude of the station
     *
     * @var double
     */
    public $longitude;


    public function __construct(array $attributes)
    {
        $this->fill($attributes);
        unset($this->attributes);
    }

    public function fill(array $attributes)
    {
        $this->code = $attributes['code'];
        $this->uicCode = (int)$attributes['UICCode'];
        $this->evaCode = (int)$attributes['EVACode'];
        $this->longName = $attributes['namen']['lang'];
        $this->shortName = $attributes['namen']['kort'];
        $this->name = $attributes['namen']['middel'];
        $this->hasFacilities = $attributes['heeftFaciliteiten'] ?: false;
        $this->hasDepartures = $attributes['heeftVertrektijden'] ?: false;
        $this->hasTravelAssistance = $attributes['heeftReisassistentie'] ?: false;
        $this->country = $attributes['land'];
        $this->latitude = $attributes['lat'];
        $this->longitude = $attributes['lng'];
    }
}