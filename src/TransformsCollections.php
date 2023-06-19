<?php

namespace RensPhilipsen\NSApi;

trait TransformsCollections
{
      /**
     * Transform the items of the collection to the given class.
     *
     * @param  array $collection
     * @param  string $class
     * @param  array $extraData
     * @return array
     */
    public function transform($collection, $class, $extraData = [])
    {
        return array_map(function ($data) use ($class, $extraData) {
            return new $class($data + $extraData);
        }, $collection);
    }
}
