<?php

namespace App\System\Parser;

use App\DTO\ProductDTO;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class FoxtrotProductsParser implements ProductsParserInterface
{
    private const URL = 'https://www.foxtrot.com.ua/uk/shop/noutbuki.html';

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

        $crawler->filterXPath('//div[@class="card__head "]')->each(function (Crawler $node) use (&$products) {
            try {
                $products[] = new ProductDTO([
                    'name' => $node->attr('data-title'),
                    'price' => (float) $node->attr('data-price'),
                    'imageUrl' => $node->filterXPath('.//source[@class="src-jpeg"]')->attr('srcset'),
                    'productUrl' => $node->attr('data-url'),
                    'store' => self::URL,
                ]);
            } catch (InvalidArgumentException) {
                $this->logger->error('Found empty node in Foxtrot products parser');
            }
        });

        return $products;
    }
}