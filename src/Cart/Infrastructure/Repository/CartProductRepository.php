<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\Repository;

use App\Cart\Domain\Entity\CartProduct;
use App\Cart\Domain\Repository\CartProductRepositoryInterface;
use App\User\Domain\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class CartProductRepository extends ServiceEntityRepository implements CartProductRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CartProduct::class);
    }

    public function getListWithPaginateForUser(User $user, int $limit, int $offset): array
    {
        return $this->createQueryBuilder('cart')
            ->select(
                'product.id',
                'product.name',
                'product.description',
                'product.cost',
                'product.tax',
            )
            ->innerJoin('cart.product', 'product')
            ->where('cart.user = :user')
            ->setParameter('user', $user)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }
}
