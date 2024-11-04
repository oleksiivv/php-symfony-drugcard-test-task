<?php

namespace App\System\Repository;

use App\DTO\ProductDTO;
use Webmozart\Assert\Assert;

class ProductCSVRepository implements ProductRepositoryInterface
{
    private const HEADERS = ['name', 'price', 'image_url', 'product_url'];

    public function __construct(private readonly string $productsCsvPath)
    {
    }

    public function bulkInsert(array $products): void
    {
        $fileHandle = fopen($this->productsCsvPath, 'w');

        fputcsv($fileHandle, self::HEADERS);

        foreach ($products as $product) {
            $data = [
                $product->name,
                $product->price,
                $product->imageUrl,
                $product->productUrl,
            ];

            fputcsv($fileHandle, $data);
        }

        fclose($fileHandle);
    }

    public function getAll(): array
    {
        $products = [];
        $fileHandle = fopen($this->productsCsvPath, 'r');

        fgetcsv($fileHandle);

        while (($data = fgetcsv($fileHandle)) !== false) {
            $products[] = new ProductDTO([
                'name' => $data[0],
                'price' => $data[1],
                'imageUrl' => $data[2],
                'productUrl' => $data[3]
            ]);
        }

        fclose($fileHandle);

        return $products;
    }
}
