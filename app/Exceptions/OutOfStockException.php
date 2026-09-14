<?php

namespace App\Exceptions;

use App\Models\Product;
use RuntimeException;

class OutOfStockException extends RuntimeException
{
    public function __construct(public readonly Product $product, public readonly int $available)
    {
        parent::__construct("Not enough stock for \"{$product->name}\": only {$available} available.");
    }
}
