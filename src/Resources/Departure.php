<?php

namespace RensPhilipsen\NSApi\Resources;

use Illuminate\Support\Carbon;

class Departure
{

    /**
     * Direction of the departure
     *
     * @var string
     */
    public $direction;

    /**
     * Name of the departure
     *
     * @var string
     */
    public $name;

    /**
     * Planned date time of the departure
     *
     * @var string
     */
    public $plannedDateTime;

    /**
     * Planned timezone offset of the departure
     *
     * @var int
     */
    public $plannedTimeZoneOffset;

    /**
     * Actual date time of the departure
     *
     * @var string
     */
    public $actualDateTime;

    /**
     * Actual timezone offset of the departure
     *
     * @var int
     */
    public $actualTimeZoneOffset;

    /**
     * Planned track of the departure
     *
     * @var string
     */
    public $plannedTrack;

    /**
     * Actual track of the departure
     *
     * @var string
     */
    public $actualTrack;


    /**
     * Product of the departure
     *
     * @var Product
     */
    public $product;

    /**
     * Train category of the departure
     *
     * @var string
     */
    public $trainCategory;

    /**
     *  Is the departure cancelled
     *
     * @var bool
     */
    public $cancelled;

    /**
     * Route stations of the departure
     *
     * @var array
     */
    public $routeStations;


    /**
     * Status of the departure
     *
     * @var string
     */
    public $status;

    public function __construct(array $attributes)
    {
        $this->fill($attributes);
        unset($this->attributes);
    }

    public function fill(array $attributes)
    {
        $this->direction = $attributes['direction'];
        $this->name = $attributes['name'];
        $this->plannedDateTime = $attributes['plannedDateTime'] ?? null;
        $this->plannedTimeZoneOffset = $attributes['plannedTimeZoneOffset'] ?? null;
        $this->actualDateTime = $attributes['actualDateTime'] ?? null;
        $this->actualTimeZoneOffset = $attributes['actualTimeZoneOffset'] ?? null;
        $this->plannedTrack = $attributes['plannedTrack'] ?? null;
        $this->actualTrack = $attributes['actualTrack'] ?? null;
        $this->product = new Product($attributes['product']) ?? null;
        $this->trainCategory = $attributes['trainCategory'];
        $this->cancelled = $attributes['cancelled'] ?? null;
        $this->routeStations = $attributes['routeStations'] ?? null;
        $this->cancelled = $attributes['cancelled'] ?? null;
        $this->status = $attributes['departureStatus'] ?? null;
    }

    public function getDelay()
    {
        $plannedDateTime = Carbon::parse($this->plannedDateTime);
        $actualDateTime = Carbon::parse($this->actualDateTime);
        $difference = $plannedDateTime->diffInMinutes($actualDateTime);
        return $difference > 0 ? $difference : null;
    }
}