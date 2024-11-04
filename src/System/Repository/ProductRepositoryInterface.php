<?php

namespace App\System\Repository;

interface ProductRepositoryInterface
{
    public function bulkInsert(array $products): void;
}
