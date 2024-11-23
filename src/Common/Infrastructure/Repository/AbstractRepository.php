<?php

declare(strict_types=1);

namespace App\Common\Infrastructure\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class AbstractRepository extends ServiceEntityRepository
{
    protected EntityManagerInterface $entityManager;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AbstractRepository::class);
        $this->entityManager = $registry->getManager();
    }

    public function flush(): void
    {
        $this->entityManager->flush();
    }
}