<?php

declare(strict_types=1);

namespace App\User\Application\UseCase\Create;

use App\Common\Domain\ValueObject\Email;
use App\Common\Domain\ValueObject\RuPhoneNumber;
use App\Role\Domain\Repository\RoleRepositoryInterface;
use App\User\Domain\Entity\User;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\ValueObject\Name;
use Symfony\Component\Uid\AbstractUid;

final class CreateUserCommandHandler
{
    private const string AUTHORIZED_USER_ROLE_SLUG = 'authorized_user';

    public function __construct(
        private readonly RoleRepositoryInterface $roleRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly AbstractUid             $uuid,
    )
    {
    }

    public function __invoke(CreateUserCommand $createUserCommand): void
    {
        $authorizedUserRole = $this->roleRepository->findBySlug(self::AUTHORIZED_USER_ROLE_SLUG);

        $user = User::create(
            name: Name::fromString($createUserCommand->name),
            email: Email::fromString($createUserCommand->email),
            phone: RuPhoneNumber::fromInt($createUserCommand->phone),
            promoId: is_null($createUserCommand->promoId) ? null : $this->uuid::fromString($createUserCommand->promoId),
            role: $authorizedUserRole,
        );

        $this->userRepository->create($user);
        $this->userRepository->flush();
    }
}