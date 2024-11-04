<?php

namespace App\System\Service\SaveStrategy;

use App\Message\WriteProductsToFileMessage;
use Symfony\Component\Messenger\MessageBusInterface;

class ProductsCSVSaveStrategy implements ProductsSaveStrategyInterface
{
    public function __construct(private readonly MessageBusInterface $bus)
    {
    }

    public function execute(array $products): void
    {
        $this->bus->dispatch(new WriteProductsToFileMessage($products));
    }
}
