<?php

declare(strict_types=1);

namespace App\Role\Infrastructure\Fixture;

use App\Common\Infrastructure\Repository\Flusher;
use App\Role\Domain\Entity\Role;
use App\Role\Domain\Repository\RoleRepositoryInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ObjectManager;

final class CreateRolesFixture extends Fixture
{
    public function __construct(
        private readonly Flusher                 $flusher,
        private readonly RoleRepositoryInterface $roleRepository,
    )
    {
    }

    public function load(ObjectManager $manager)
    {
        /* @var Role $role */
        foreach ($this->getRolesToCreate() as $role) {
            if (false === $this->doesRoleExist($role)) {
                $this->createRole($role);
            }
        }

        $this->flusher->flush();
    }

    private function getRolesToCreate(): Collection
    {
        return new ArrayCollection([
            Role::create(
                slug: 'admin',
                name: 'Администратор',
            ),
            Role::create(
                slug: 'authorized_user',
                name: 'Авторизованный пользователь',
            ),
        ]);
    }

    private function doesRoleExist(Role $role): bool
    {
        return false === is_null($this->roleRepository->findBySlug($role->getSlug()));
    }

    private function createRole(Role $role): void
    {
        $this->roleRepository->create($role);
    }
}