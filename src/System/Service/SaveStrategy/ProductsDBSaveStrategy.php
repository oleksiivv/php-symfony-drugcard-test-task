<?php

namespace App\System\Service\SaveStrategy;

use App\System\Repository\ProductDBRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use RuntimeException;

class ProductsDBSaveStrategy implements ProductsSaveStrategyInterface
{
    public function __construct(
        private ProductDBRepository $productRepository
    ) {
    }

    public function execute(array $products): void
    {
        try {
            $this->productRepository->bulkInsert($products);
        } catch (UniqueConstraintViolationException $exception) {
            throw new RuntimeException('Product already exists in the database', $exception);
        }
    }
}