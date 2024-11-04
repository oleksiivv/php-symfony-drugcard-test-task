<?php

namespace App\Tests\Unit\System\Repository;

use App\System\Repository\ProductCSVRepository;
use PHPUnit\Framework\TestCase;
use App\DTO\ProductDTO;

class ProductCSVRepositoryTest extends TestCase
{
    public function testBulkInsert()
    {
        $csvPath = sys_get_temp_dir() . '/test-products.csv';

        $products = [
            new ProductDTO([
                'name' => 'Laptop',
                'price' => 999.99,
                'imageUrl' => 'laptop.jpg',
                'productUrl' => 'laptop_url'
            ]),
            new ProductDTO([
                'name' => 'Camera',
                'price' => 499.49,
                'imageUrl' => 'camera.jpg',
                'productUrl' => 'camera_url'
            ]),
        ];

        (new ProductCSVRepository($csvPath))->bulkInsert($products);

        $contents = file_get_contents($csvPath);
        $this->assertStringContainsString('Laptop,999.99,laptop.jpg,laptop_url', $contents);
        $this->assertStringContainsString('Camera,499.49,camera.jpg,camera_url', $contents);
    }
}