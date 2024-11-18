<?php

declare(strict_types=1);

namespace App\User\Application\UseCase\Add;

use App\Common\Domain\Exception\Validation\GreaterThanMaxLengthException;
use App\Common\Domain\Exception\Validation\InvalidEmailException;
use App\Common\Domain\Exception\Validation\LessThanMinLengthException;
use App\Common\Domain\Exception\Validation\WrongLengthOfPhoneNumberException;
use App\Common\Domain\ValueObject\Email;
use App\Common\Domain\ValueObject\RuPhoneNumber;
use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\Name;
use App\User\Infrastructure\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

final class CreateUserCommandHandler
{
    public function __construct(
        private readonly UserRepository $userRepository,
    )
    {
    }

    public function __invoke(CreateUserCommand $createUserCommand): JsonResponse
    {
        $user = User::create(
            name: Name::fromString($createUserCommand->name),
            email: Email::fromString($createUserCommand->email),
            phone: RuPhoneNumber::fromInt($createUserCommand->phone),
        );

        $this->userRepository->create($user);

        return new JsonResponse();
    }
}