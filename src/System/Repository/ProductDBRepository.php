<?php

namespace App\System\Repository;

use App\DTO\ProductDTO;
use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Throwable;
use Webmozart\Assert\Assert;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductDBRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    private const BULK_INSERT_BATCH_SIZE = 50;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function bulkInsert(array $products): void
    {
        $entityManager = $this->getEntityManager();
        $entityManager->getConnection()->beginTransaction();

        try {
            $productChunks = array_chunk($products, self::BULK_INSERT_BATCH_SIZE);

            foreach ($productChunks as $chunk) {
                foreach ($chunk as $product) {
                    $entityManager->persist(Product::fromDTO($product));
                }

                $entityManager->flush();
                $entityManager->clear();
            }

            $entityManager->getConnection()->commit();
        } catch (Throwable $e) {
            $entityManager->getConnection()->rollBack();
            throw $e;
        }
    }

    public function getAll(): array
    {
        $products = $this->findAll();
        return array_map(function (Product $product) {
            return new ProductDTO([
                'name' => $product->getName(),
                'price' => $product->getPrice(),
                'imageLink' => $product->getImageUrl(),
                'productLink' => $product->getProductUrl(),
            ]);
        }, $products);
    }
}
