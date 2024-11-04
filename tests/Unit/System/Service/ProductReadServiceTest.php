<?php

namespace App\Tests\Unit\System\Service;

use PHPUnit\Framework\TestCase;
use App\System\Service\ProductReadService;
use App\System\Repository\ProductDBRepository;
use App\System\Repository\ProductCSVRepository;
use App\Enum\StorageTypeEnum;

class ProductReadServiceTest extends TestCase
{
    private ProductDBRepository $productDBRepositoryMock;
    private ProductCSVRepository $productCSVRepositoryMock;
    private ProductReadService $productReadService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->productDBRepositoryMock = $this->createMock(ProductDBRepository::class);
        $this->productCSVRepositoryMock = $this->createMock(ProductCSVRepository::class);

        $this->productReadService = new ProductReadService($this->productDBRepositoryMock, $this->productCSVRepositoryMock);
    }

    public function testGetAllFromDB()
    {
        $expected = ['product1', 'product2'];

        $this->productDBRepositoryMock->method('getAll')->willReturn($expected);

        $result = $this->productReadService->getAll(StorageTypeEnum::DB->value);

        $this->assertEquals($expected, $result);
    }

    public function testGetAllFromCSV()
    {
        $expected = ['product3', 'product4'];

        $this->productCSVRepositoryMock->method('getAll')->willReturn($expected);

        $result = $this->productReadService->getAll(StorageTypeEnum::CSV->value);

        $this->assertEquals($expected, $result);
    }

    public function testGetAllFromBoth()
    {
        $expectedDB = ['product1', 'product2'];
        $expectedCSV = ['product3', 'product4'];

        $expected = array_merge($expectedDB, $expectedCSV);

        $this->productDBRepositoryMock->method('getAll')->willReturn($expectedDB);
        $this->productCSVRepositoryMock->method('getAll')->willReturn($expectedCSV);

        $result = $this->productReadService->getAll(null);

        $this->assertEquals($expected, $result);
    }
}