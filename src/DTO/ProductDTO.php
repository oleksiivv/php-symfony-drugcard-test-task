<?php

namespace App\DTO;

use Spatie\DataTransferObject\DataTransferObject;

class ProductDTO extends DataTransferObject
{
    public ?string $name;

    public ?float $price;

    public ?string $imageUrl;

    public ?string $productUrl;
}
