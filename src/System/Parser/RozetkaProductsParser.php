<?php

namespace App\System\Parser;

use App\DTO\ProductDTO;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RozetkaProductsParser implements ProductsParserInterface
{
    private const URL = 'https://rozetka.com.ua/ua/notebooks/c80004/';

    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function parse(): array
    {
        $products = [];

        $html = $this->client->request('GET', self::URL);
        $crawler = new Crawler($html->getContent());

        $crawler->filterXPath('//div[@class="goods-tile__inner"]')->each(function (Crawler $node) use (&$products) {
            try {
                $products[] = new ProductDTO([
                    'name' => $node->filterXPath('.//a[@class="product-link goods-tile__heading"]')->attr('title'),
                    'price' => (float) $node->filterXPath('.//span[@class="goods-tile__price-value"]')->text(),
                    'imageUrl' => $node->filterXPath('.//img[@class="lazy_img_hover display-none  ng-failed-lazyloaded ng-lazyloaded"]')->attr('src'),
                    'productUrl' => $node->filterXPath('.//a[@class="product-link goods-tile__picture"]')->attr('href'),
                ]);
            } catch (InvalidArgumentException) {
                $this->logger->error('Found empty node in Rozetka products parser');
            }
        });

        return $products;
    }
}