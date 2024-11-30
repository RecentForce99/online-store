<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Repository;

use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use App\User\Application\Exception\UserNotFound;
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
            throw UserNotFound::byId(
                $id,
            );
        }

        return $product;
    }

    public function add(Product $product): void
    {
        $this->getEntityManager()->persist($product);
    }
}
