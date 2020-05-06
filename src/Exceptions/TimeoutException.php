<?php

namespace RensPhilipsen\NSApi\Exceptions;

use Exception;

class TimeoutException extends Exception
{
    /**
     * The output returned from the operation.
     *
     * @var array
     */
    public array $output;

    /**
     * Create a new exception instance.
     *
     * @param $output
     */
    public function __construct($output)
    {
        parent::__construct('Script timed out while waiting for the process to complete.');

        $this->output = $output;
    }

    /**
     * The output returned from the operation.
     *
     * @return array
     */
    public function output()
    {
        return $this->output;
    }
}