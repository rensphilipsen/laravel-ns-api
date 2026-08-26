<?php

namespace RensPhilipsen\NSApi\Resources;

class Product
{

    /**
     * Number of the product
     *
     * @var string
     */
    public $number;

    /**
     * Category code of the product
     *
     * @var string
     */
    public $categoryCode;

    /**
     * Short category name of the product
     *
     * @var string
     */
    public $shortCategoryName;

    /**
     * Long category name of the product
     *
     * @var string
     */
    public $longCategoryName;

    /**
     * Operator code of the product
     *
     * @var string
     */
    public $operatorCode;

    /**
     * Operator name of the product
     *
     * @var string
     */
    public $operatorName;

    /**
     * Type of the product
     *
     * @var string
     */
    public $type;

    public function __construct(array $attributes)
    {
        $this->fill($attributes);
        unset($this->attributes);
    }

    public function fill(array $attributes)
    {
        $this->number = $attributes['number'] ?? null;
        $this->categoryCode = $attributes['categoryCode'] ?? null;
        $this->shortCategoryName = $attributes['shortCategoryName'] ?? null;
        $this->longCategoryName = $attributes['longCategoryName'] ?? null;
        $this->operatorCode = $attributes['operatorCode'] ?? null;
        $this->operatorName = $attributes['operatorName'] ?? null;
        $this->type = $attributes['type'] ?? null;
    }
}
