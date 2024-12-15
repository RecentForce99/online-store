<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Repository;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\User\Application\Exception\UserNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class ProductRepository extends ServiceEntityRepository implements ProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function findById(string $id): ?Product
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function getById(string $id): Product
    {
        $product = $this->findById($id);
        if (true === is_null($product)) {
            throw UserNotFoundException::byId(
                $id,
            );
        }

        return $product;
    }

    public function add(Product $product): void
    {
        $this->getEntityManager()->persist($product);
    }

    public function getProductsSoldInLast24Hours(): array
    {
        return $this->createQueryBuilder('product')
            ->select(
                'product.name as product_name',
                'product.cost as price',
                'SUM(order_products.quantity) as amount',
                'user.id as user_id',
            )
            ->innerJoin('product.orderProducts', 'order_products')
            ->innerJoin('order_products.order', 'order')
            ->innerJoin('order.user', 'user')
            ->groupBy('product.id, user.id')
            ->getQuery()
            ->getResult();
    }
}
