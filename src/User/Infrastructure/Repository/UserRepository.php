<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Repository;

use App\Common\Application\Exception\EntityNotFound;
use App\User\Domain\Entity\User;
use App\User\Domain\Repository\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

final class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findById(string $id): ?User
    {
        return $this->findOneBy(['id' => $id]);
    }

    public function getById(string $id): User
    {
        $user = $this->findById($id);
        if (true === is_null($user)) {
            throw new EntityNotFound(
                "User with id [$id] has not been found",
                Response::HTTP_BAD_REQUEST,
            );
        }

        return $user;
    }

    public function create(User $user): void
    {
        $this->getEntityManager()->persist($user);
    }
}
