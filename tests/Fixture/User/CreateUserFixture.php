<?php

declare(strict_types=1);

namespace App\Tests\Fixture\User;

use App\Common\Domain\ValueObject\Email;
use App\Common\Domain\ValueObject\RuPhoneNumber;
use App\Common\Infrastructure\Repository\Flusher;
use App\Role\Domain\Enum\RoleEnum;
use App\Role\Domain\Repository\RoleRepositoryInterface;
use App\User\Domain\Entity\User;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\ValueObject\Name;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\ByteString;
use Symfony\Component\Uid\UuidV4;

final class CreateUserFixture extends Fixture
{
    public function __construct(
        private readonly RoleRepositoryInterface     $roleRepository,
        private readonly UserRepositoryInterface     $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly Flusher                     $flusher,
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $userRole = $this->roleRepository->findBySlug(RoleEnum::ROLE_USER->name);
        $user = User::create(
            Name::fromString('Less Grossman'),
            Email::fromString('less-grossman@test.com'),
            RuPhoneNumber::fromInt(1234567890),
            new UuidV4(),
            new ArrayCollection([$userRole]),
        );

        $password = $this->passwordHasher->hashPassword($user, ByteString::fromRandom(12)->toString());
        $user->setPassword($password);

        $this->userRepository->add($user);
        $this->flusher->flush();
    }
}
