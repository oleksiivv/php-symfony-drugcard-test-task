<?php

namespace App\Tests\Unit\System\Repository;

use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\Mapping\RuntimeReflectionService;
use Exception;
use PHPUnit\Framework\TestCase;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Connection;
use App\System\Repository\ProductDBRepository;
use App\DTO\ProductDTO;
use App\Entity\Product;

class ProductDBRepositoryTest extends TestCase
{
    public function testBulkInsertWorksCorrectly(): void
    {
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $managerRegistryMock = $this->createMock(ManagerRegistry::class);
        $connectionMock = $this->createMock(Connection::class);

        $metadata = new ClassMetadata(Product::class);
        $metadata->initializeReflection(new RuntimeReflectionService());

        $entityManagerMock->method('getClassMetadata')
            ->with(Product::class)
            ->willReturn($metadata);

        $entityManagerMock->method('getConnection')->willReturn($connectionMock);
        $managerRegistryMock->method('getManagerForClass')
            ->with(Product::class)
            ->willReturn($entityManagerMock);

        $productRepository = new ProductDBRepository($managerRegistryMock);

        $products = [
            new ProductDTO(['name' => 'Laptop', 'price' => 1000, 'imageUrl' => 'link-to-image.jpg', 'productUrl' => 'link-to-product']),
            new ProductDTO(['name' => 'Phone', 'price' => 500, 'imageUrl' => 'link-to-image.jpg', 'productUrl' => 'link-to-product'])
        ];

        $connectionMock->expects($this->once())->method('beginTransaction');
        $entityManagerMock->expects($this->exactly(2))->method('persist');
        $entityManagerMock->expects($this->once())->method('flush');
        $entityManagerMock->expects($this->once())->method('clear');
        $connectionMock->expects($this->once())->method('commit');

        $productRepository->bulkInsert($products);
    }

    public function testBulkInsertRollbacksTransactionIfQueryFails(): void
    {
        $entityManagerMock = $this->createMock(EntityManagerInterface::class);
        $managerRegistryMock = $this->createMock(ManagerRegistry::class);
        $connectionMock = $this->createMock(Connection::class);

        $metadata = new ClassMetadata(Product::class);
        $metadata->initializeReflection(new RuntimeReflectionService());

        $entityManagerMock->method('getClassMetadata')
            ->with(Product::class)
            ->willReturn($metadata);

        $entityManagerMock->method('getConnection')->willReturn($connectionMock);
        $managerRegistryMock->method('getManagerForClass')
            ->with(Product::class)
            ->willReturn($entityManagerMock);

        $productRepository = new ProductDBRepository($managerRegistryMock);

        $products = [
            new ProductDTO(['name' => 'Laptop', 'price' => 1000, 'imageUrl' => 'link-to-image.jpg', 'productUrl' => 'link-to-product']),
            new ProductDTO(['name' => 'Phone', 'price' => 500, 'imageUrl' => 'link-to-image.jpg', 'productUrl' => 'link-to-product'])
        ];

        $connectionMock->expects($this->once())
            ->method('beginTransaction');

        $entityManagerMock->expects($this->exactly(2))
            ->method('persist');

        $entityManagerMock->expects($this->once())
            ->method('flush')
            ->willThrowException(new Exception('Query failed'));

        $connectionMock->expects($this->once())
            ->method('rollBack');

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Query failed');

        $productRepository->bulkInsert($products);
    }
}
