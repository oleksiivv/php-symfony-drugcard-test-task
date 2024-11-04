<?php

namespace App\System\Parser;

use Webmozart\Assert\Assert;

class ProductsParserComposite implements ProductsParserInterface
{
    public function __construct(private readonly array $parsers)
    {
        Assert::allIsInstanceOf($parsers, ProductsParserInterface::class);
    }

    public function parse(): array
    {
        $products = [];

        foreach ($this->parsers as $parser) {
            $products = array_merge($products, $parser->parse());
        }

        return $products;
    }
}