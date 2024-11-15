<?php

declare(strict_types=1);

namespace App\User\Application\UseCase\SignUp;

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

final class SignUpUserCommandHandler
{
    public function __construct(
        private readonly UserRepository $userRepository,
    )
    {
    }

    public function __invoke(SignUpUserCommand $signUpUserCommand): JsonResponse
    {
        $user = User::add(
            name: Name::fromString($signUpUserCommand->name),
            email: Email::fromString($signUpUserCommand->email),
            phone: RuPhoneNumber::fromInt($signUpUserCommand->phone),
        );

        $this->userRepository->add($user);

        return new JsonResponse();
    }
}