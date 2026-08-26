<?php

namespace RensPhilipsen\NSApi\Exceptions;

use Exception;

class NotFoundException extends Exception
{
    /**
     * Create a new exception instance.
     *
     * @param string|null $message
     */
    public function __construct(?string $message = null)
    {
        parent::__construct($message ?: 'The resource you are looking for could not be found.');
    }
}