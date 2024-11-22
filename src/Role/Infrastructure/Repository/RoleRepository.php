<?php

declare(strict_types=1);

namespace App\Role\Infrastructure\Repository;

use App\Common\Domain\Repository\AbstractRepository;
use App\Role\Domain\Entity\Role;
use App\Role\Domain\Repository\RoleRepositoryInterface;

final class RoleRepository extends AbstractRepository implements RoleRepositoryInterface
{
    public function create(Role $role): void
    {
        $this->entityManager->persist($role);
    }

    public function findBySlug(string $slug): ?Role
    {
        return $this->entityManager->getRepository(Role::class)
            ->findOneBy(['slug' => $slug]);
    }
}