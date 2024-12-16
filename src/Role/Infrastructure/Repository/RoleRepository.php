<?php

declare(strict_types=1);

namespace App\Role\Infrastructure\Repository;

use App\Role\Application\Exception\RoleNotFoundException;
use App\Role\Domain\Entity\Role;
use App\Role\Domain\Repository\RoleRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class RoleRepository extends ServiceEntityRepository implements RoleRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Role::class);
    }

    public function add(Role $role): void
    {
        $this->getEntityManager()->persist($role);
    }

    public function findBySlug(string $slug): ?Role
    {
        return $this->findOneBy(['slug' => $slug]);
    }

    /**
     * @throws RoleNotFoundException
     */
    public function getUserRole(): Role
    {
        $userRole = $this->findOneBy(['slug' => 'ROLE_USER']);
        if (true === is_null($userRole)) {
            throw RoleNotFoundException::byName('Пользователь');
        }

        return $userRole;
    }
}
