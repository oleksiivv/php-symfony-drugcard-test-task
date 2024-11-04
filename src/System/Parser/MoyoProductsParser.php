<?php

namespace App\System\Parser;

use App\DTO\ProductDTO;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MoyoProductsParser implements ProductsParserInterface
{
    private const URL = 'https://www.moyo.ua/ua/foto_video/tv_audio/lcd_tv/?markdown=100&utm_source=google&utm_medium=cpc&utm_campaign=Promodo_Pmax_TV_Ukraine&gad_source=1';

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

        $crawler->filterXPath('//div[@data-category_id="3015"]')->each(function (Crawler $node) use (&$products) {
            try {
                $products[] = new ProductDTO([
                    'name' => $node->filterXPath('.//a[@class="product-card_title gtm-link-product"]')->text(),
                    'price' => (float) $node->attr('data-price'),
                    'imageUrl' => $node->attr('data-img'),
                    'productUrl' => $node->filterXPath('.//a[@class="product-card_image"]')->attr('href'),
                ]);
            } catch (InvalidArgumentException $e) {
                $this->logger->error($e);
                $this->logger->error('Found empty node in Rozetka products parser');
            }
        });

        return $products;
    }
}