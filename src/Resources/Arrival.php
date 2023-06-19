<?php

namespace RensPhilipsen\NSApi\Resources;

use Illuminate\Support\Carbon;

class Arrival
{

    /**
     * Origin of the arrival
     *
     * @var string
     */
    public $origin;

    /**
     * Name of the arrival
     *
     * @var string
     */
    public $name;

    /**
     * Planned date time of the arrival
     *
     * @var string
     */
    public $plannedDateTime;

    /**
     * Planned timezone offset of the arrival
     *
     * @var int
     */
    public $plannedTimeZoneOffset;

    /**
     * Actual date time of the arrival
     *
     * @var string
     */
    public $actualDateTime;

    /**
     * Actual timezone offset of the arrival
     *
     * @var int
     */
    public $actualTimeZoneOffset;

    /**
     * Planned track of the arrival
     *
     * @var string
     */
    public $plannedTrack;

    /**
     * Actual track of the arrival
     *
     * @var string
     */
    public $actualTrack;


    /**
     * Product of the arrival
     *
     * @var Product
     */
    public $product;

    /**
     * Train category of the arrival
     *
     * @var string
     */
    public $trainCategory;

    /**
     *  Is the arrival cancelled
     *
     * @var bool
     */
    public $cancelled;

    /**
     * Status of the arrival
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
        $this->origin = $attributes['origin'];
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
        $this->status = $attributes['arrivalStatus'] ?? null;
    }

    public function getDelay()
    {
        $plannedDateTime = Carbon::parse($this->plannedDateTime);
        $actualDateTime = Carbon::parse($this->actualDateTime);
        $difference = $plannedDateTime->diffInMinutes($actualDateTime);
        return $difference > 0 ? $difference : null;
    }
}
