<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Repository;

use App\Order\Domain\Entity\OrderStatus;
use App\Order\Domain\Repository\OrderStatusRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class OrderStatusRepository extends ServiceEntityRepository implements OrderStatusRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderStatus::class);
    }

    public function findBySlug(string $slug): ?OrderStatus
    {
        return $this->findOneBy(['slug' => $slug]);
    }

    public function add(OrderStatus $orderStatus): void
    {
        $this->getEntityManager()->persist($orderStatus);
    }
}