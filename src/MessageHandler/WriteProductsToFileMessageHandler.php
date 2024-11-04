<?php

namespace App\MessageHandler;

use App\Message\WriteProductsToFileMessage;
use App\System\Repository\ProductCSVRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class WriteProductsToFileMessageHandler
{
    public function __construct(private ProductCSVRepository $productRepository)
    {
    }

    public function __invoke(WriteProductsToFileMessage $message): void
    {
        $this->productRepository->bulkInsert($message->getProducts());
    }
}
