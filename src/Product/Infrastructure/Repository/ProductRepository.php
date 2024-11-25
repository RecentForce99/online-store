<?php

declare(strict_types=1);

namespace App\Product\Infrastructure\Repository;

use App\Common\Application\Exception\EntityNotFound;
use App\Product\Domain\Entity\Product;
use App\Product\Domain\Repository\ProductRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

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
            throw new EntityNotFound(
                "Product with id [$id] has not been found",
                Response::HTTP_BAD_REQUEST,
            );
        }

        return $product;
    }

    public function create(Product $product): void
    {
        $this->getEntityManager()->persist($product);
    }
}
