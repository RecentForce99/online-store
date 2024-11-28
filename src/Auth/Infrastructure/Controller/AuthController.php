<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Controller;

use App\Auth\Application\UseCase\Create\CreateUserCommand;
use App\Auth\Application\UseCase\Create\CreateUserCommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/auth')]
final class AuthController extends AbstractController
{
    #[Route(methods: 'POST', path: '/signUp')]
    public function create(
        #[MapRequestPayload] CreateUserCommand $createUserCommand,
        CreateUserCommandHandler $createUserCommandHandler,
    ): JsonResponse {
        $createUserCommandHandler($createUserCommand);

        return new JsonResponse(null, JsonResponse::HTTP_CREATED);
    }
}
