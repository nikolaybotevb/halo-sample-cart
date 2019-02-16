<?php
namespace Hautelook;

use Hautelook\CartItem;
use Hautelook\Product;

/**
  * Shopping cart.
  */
class Cart
{

    const FLAT_RATE_CUTOFF_SUBTOTAL = 100;
    const HEAVY_ITEM_THREASHOLD_LB = 10;
    const SHIPPING_FLAT_RATE_PRICE = 5;
    const SHIPPING_HEAVY_ITEM_RATE = 20;

    private /*array<productName: string => product: Product>*/ $items;
    private /*int*/ $percentDiscount;

    /**
      * Constructs a new shopping cart.
      */
    public function __construct()
    {
        $this->items = array();
        $this->PercentDiscount = 0;
    }

    /**
      * Adds the given quantity of product to the cart.
      *
      * @param Product $product the product to add to the cart
      * @param int $quantity the quantity of product; must be greater than 0
      */
    public function addItem(Product $product, $quantity)
    {
        $itemKey = $product->name();
        if (array_key_exists($itemKey, $this->items))
        {
            $this->items[$itemKey]->addQuantity($quantity);
        }
        else
        {
            $this->items[$itemKey] = new CartItem($product, $quantity);
        }
    }

    /**
      * Sets the discount (in %) for this order. This usually comes in the form of a coupon
      * which is validated externally before its discount is applied to the cart.
      *
      * @param int $perentDiscount the percentage discount; a number between 0 and 100
      */
    public function applyDiscount($percentDiscount)
    {
        $this->percentDiscount = $percentDiscount;
    }

    /**
      * Looks for a cart item by product name.
      *
      * @param string $productName the name of the product to look for in the cart
      * @return CartItem the cart item for the product, or null if there is no item for the named product in the cart.
      */
    public function findCartItem($productName)
    {
        if (array_key_exists($productName, $this->items))
        {
            return $this->items[$productName];
        }
        else
        {
            return NULL;
        }
    }

    /**
      * Returns the subtotal for the cart. This is the total price of all items with discounts applied.
      */
    public function subtotal()
    {
        $subtotal = array_reduce($this->items,
            function($acc, $item) { return $acc + ($item->product()->price() * $item->quantity()); },
            0);
        // Apply discount
        $subtotal = $subtotal * (1.0 - $this->percentDiscount / 100.0);
        return $subtotal;
    }

    /**
      * Returns the total due for the cart. This is the subtotal plus shipping and any applicable taxes.
      */
    public function total()
    {
        $subtotal = $this->subtotal();

        $shipping = 0;

        // Flat rate
        if ($subtotal < Cart::FLAT_RATE_CUTOFF_SUBTOTAL)
        {
            $someLightItems = array_reduce($this->items,
                function($acc, $item) { return $acc || ($item->product()->weight() < Cart::HEAVY_ITEM_THREASHOLD_LB); },
                false);
            if ($someLightItems)
            {
                 $shipping += Cart::SHIPPING_FLAT_RATE_PRICE;
            }
        }

        /// Shipping for heavy items
        $heavyItems =  array_filter($this->items,
            function($item) { return $item->product()->weight() >= Cart::HEAVY_ITEM_THREASHOLD_LB; });
        $shipping += count($heavyItems) * Cart::SHIPPING_HEAVY_ITEM_RATE;

        return $subtotal + $shipping;
    }
}
