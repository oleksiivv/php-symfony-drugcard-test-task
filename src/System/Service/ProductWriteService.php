<?php

namespace App\System\Service;

use App\DTO\ProductDTO;
use App\System\Service\SaveStrategy\ProductsDBSaveStrategy;
use App\System\Service\SaveStrategy\ProductsCSVSaveStrategy;
use App\Enum\StorageTypeEnum;
use Webmozart\Assert\Assert;

class ProductWriteService
{
    public function __construct(
        private ProductsCSVSaveStrategy $productsFSSaveStrategy,
        private ProductsDBSaveStrategy  $productsDBSaveStrategy,
    ) {
    }

    public function save(array $products, ?string $storageType): void
    {
        Assert::allIsInstanceOf($products, ProductDTO::class);
        Assert::notEmpty($products);

        if ($storageType === StorageTypeEnum::CSV->value) {
            $this->productsFSSaveStrategy->execute($products);
            return;
        }

        if ($storageType === StorageTypeEnum::DB->value) {
            $this->productsDBSaveStrategy->execute($products);
            return;
        }

        $this->productsFSSaveStrategy->execute($products);
        $this->productsDBSaveStrategy->execute($products);
    }
}
