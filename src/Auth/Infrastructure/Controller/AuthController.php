<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Controller;

use App\Auth\Application\UseCase\SignUp\SignUpCommand;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/auth')]
final class AuthController extends AbstractController
{
    /**
     * @throws ExceptionInterface
     */
    #[Route(methods: 'POST', path: '/signUp', name: 'auth.signUp')]
    public function signUp(
        #[MapRequestPayload] SignUpCommand $signUpCommand,
        MessageBusInterface $messageBus,
    ): JsonResponse {
        $messageBus->dispatch($signUpCommand);

        return new JsonResponse(status: JsonResponse::HTTP_CREATED);
    }
}
