<?php
namespace Hautelook;

use Hautelook\Product;

/**
  * A cart item consists of a product and quantity.
  */
class CartItem
{

    private /*Product*/ $product;
    private /*int*/ $quantity;

    /**
      * Constructs a new shopping cart item.
      *
      * @param Product $product the product of this item
      * @param int $initialQuantity the initial quantity of the product; must be greater than 0
      */
    public function __construct($product, $initialQuantity)
    {
        $this->product = $product;
        $this->quantity = $initialQuantity;
    }

    /**
      * Returns the product of this cart item.
      */
    public function product()
    {
        return $this->product;
    }

    /**
      * Returns the quantity being ordered of the product.
      */
    public function quantity()
    {
        return $this->quantity;
    }

    /**
      * Adds more quantity to the item.
      *
      * @param int $additional the additional quantity to add; must be greater than 0
      */
    public function addQuantity($additional)
    {
        $this->quantity += $additional;
    }
}
