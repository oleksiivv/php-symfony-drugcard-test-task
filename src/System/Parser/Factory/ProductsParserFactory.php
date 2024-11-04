<?php

namespace App\System\Parser\Factory;

use App\Enum\ProductsStoreEnum;
use App\System\Parser\FoxtrotProductsParser;
use App\System\Parser\MoyoProductsParser;
use App\System\Parser\ProductsParserComposite;
use App\System\Parser\ProductsParserInterface;
use App\System\Parser\RozetkaProductsParser;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ProductsParserFactory
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function create(?string $store): ProductsParserInterface
    {
        return match ($store) {
            ProductsStoreEnum::ROZETKA->value => new RozetkaProductsParser($this->client, $this->logger),
            ProductsStoreEnum::MOYO->value => new MoyoProductsParser($this->client, $this->logger),
            ProductsStoreEnum::FOXTROT->value => new FoxtrotProductsParser($this->client, $this->logger),

            ProductsStoreEnum::ALL->value => new ProductsParserComposite([
                new RozetkaProductsParser($this->client, $this->logger),
                new MoyoProductsParser($this->client, $this->logger),
                new FoxtrotProductsParser($this->client, $this->logger),
            ]),

            default => throw new InvalidArgumentException('Invalid store name'),
        };
    }
}