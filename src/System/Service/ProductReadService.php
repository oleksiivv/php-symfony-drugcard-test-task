<?php

namespace App\System\Service;

use App\System\Repository\ProductCSVRepository;
use App\System\Repository\ProductDBRepository;
use App\Enum\StorageTypeEnum;

class ProductReadService
{
    public function __construct(
        private ProductDBRepository $productDBRepository,
        private ProductCSVRepository $productCSVRepository,
    ) {
    }

    public function getAll(?string $storageType): array
    {
        if ($storageType === StorageTypeEnum::DB->value) {
            return $this->productDBRepository->getAll();
        }

        if ($storageType === StorageTypeEnum::CSV->value) {
            return $this->productCSVRepository->getAll();
        }

        return array_merge(
            $this->productDBRepository->getAll(),
            $this->productCSVRepository->getAll(),
        );
    }
}
