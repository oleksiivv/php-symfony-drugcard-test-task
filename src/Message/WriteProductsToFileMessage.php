<?php

namespace App\Message;

class WriteProductsToFileMessage
{
    public function __construct(private readonly array $products)
    {
    }

    public function getProducts(): array
    {
        return $this->products;
    }
}