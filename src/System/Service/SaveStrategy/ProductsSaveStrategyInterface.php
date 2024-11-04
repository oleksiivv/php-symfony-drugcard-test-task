<?php

namespace App\System\Service\SaveStrategy;

interface ProductsSaveStrategyInterface
{
    public function execute(array $products): void;
}
