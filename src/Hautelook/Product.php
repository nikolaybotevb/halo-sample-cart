<?php
namespace Hautelook;

/**
  * Product definition.
  */
class Product
{

    private /*string*/ $name;
    private /*int*/ $price;
    private /*int*/ $weight;

    /**
      * Constructs a new product definition.
      *
      * @param string $name the name of the product
      * @param int $price the unit price of the product
      * @param int $weight the unit shipping weight of the product
      */
    public function __construct($name, $price, $weight)
    {
        $this->name = $name;
        $this->price = $price;
        $this->weight = $weight;
    }

    /**
      * Returns the product name.
      */
    public function name()
    {
        return $this->name;
    }

    /**
      * Returns the unit price of the product.
      */
    public function price()
    {
        return $this->price;
    }

    /**
      * Returns the unit shipping weight of the product.
      */
    public function weight()
    {
        return $this->weight;
    }
}

