<?php

declare(strict_types=1);

namespace App\User\Application\UseCase\Create;

use App\Common\Domain\ValueObject\Email;
use App\Common\Domain\ValueObject\RuPhoneNumber;
use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\Name;
use App\User\Infrastructure\Repository\UserRepository;

final class CreateUserCommandHandler
{
    public function __construct(
        private readonly UserRepository $userRepository,
    )
    {
    }

    public function __invoke(CreateUserCommand $createUserCommand): void
    {
        $user = User::create(
            name: Name::fromString($createUserCommand->name),
            email: Email::fromString($createUserCommand->email),
            phone: RuPhoneNumber::fromInt($createUserCommand->phone),
        );

        $this->userRepository->create($user);
        $this->userRepository->flush();
    }
}