<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Repository;

use App\Order\Domain\Entity\DeliveryType;
use App\Order\Domain\Repository\DeliveryTypeRepositoryInterface;
use App\Order\Infrastructure\Exception\DeliveryTypeNotFoundException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class DeliveryTypeRepository extends ServiceEntityRepository implements DeliveryTypeRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeliveryType::class);
    }

    public function add(DeliveryType $deliveryType): void
    {
        $this->getEntityManager()->persist($deliveryType);
    }

    public function findAll(): array
    {
        return parent::findAll();
    }

    /**
     * @throws DeliveryTypeNotFoundException
     */
    public function getBySlug(string $slug): DeliveryType
    {
        /* @var DeliveryType|null $deliveryType */
        $deliveryType = $this->findOneBy(['slug' => $slug]);
        if (null === $deliveryType) {
            throw DeliveryTypeNotFoundException::byName($slug);
        }

        return $deliveryType;
    }
}
