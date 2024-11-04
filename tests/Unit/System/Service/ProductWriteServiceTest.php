<?php

namespace App\Tests\Unit\System\Service;

use App\DTO\ProductDTO;
use PHPUnit\Framework\TestCase;
use App\System\Service\ProductWriteService;
use App\System\Service\SaveStrategy\ProductsCSVSaveStrategy;
use App\System\Service\SaveStrategy\ProductsDBSaveStrategy;
use App\Enum\StorageTypeEnum;

class ProductWriteServiceTest extends TestCase
{
    private ProductsCSVSaveStrategy $productsCSVSaveStrategyMock;
    private ProductsDBSaveStrategy $productsDBSaveStrategyMock;
    private ProductWriteService $productWriteService;
    private array $products;

    protected function setUp(): void
    {
        $this->productsCSVSaveStrategyMock = $this->createMock(ProductsCSVSaveStrategy::class);
        $this->productsDBSaveStrategyMock = $this->createMock(ProductsDBSaveStrategy::class);

        $this->productWriteService = new ProductWriteService($this->productsCSVSaveStrategyMock, $this->productsDBSaveStrategyMock);

        $this->products = [
            new ProductDTO(['name' => 'Phone', 'price' => 999]),
            new ProductDTO(['name' => 'Laptop', 'price' => 1999]),
        ];
    }

    public function testSaveToCSVStorage()
    {
        $this->productsCSVSaveStrategyMock->expects($this->once())
            ->method('execute')
            ->with($this->products);

        $this->productWriteService->save($this->products, StorageTypeEnum::CSV->value);
    }

    public function testSaveToDBStorage()
    {
        $this->productsDBSaveStrategyMock->expects($this->once())
            ->method('execute')
            ->with($this->products);

        $this->productWriteService->save($this->products, StorageTypeEnum::DB->value);
    }

    public function testSaveToBothStorages()
    {
        $this->productsCSVSaveStrategyMock->expects($this->once())
            ->method('execute')
            ->with($this->products);

        $this->productsDBSaveStrategyMock->expects($this->once())
            ->method('execute')
            ->with($this->products);

        $this->productWriteService->save($this->products, null);
    }
}
