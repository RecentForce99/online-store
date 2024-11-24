<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Presentation\Api;

use App\Common\Infrastructure\Enum\Kafka\KafkaTopicEnum;
use App\Common\Infrastructure\Trait\FormattedErrorsTrait;
use App\User\Application\UseCase\Create\CreateUserCommand;
use App\User\Application\UseCase\Create\CreateUserCommandHandler;
use App\User\Application\UseCase\Create\SendNotificationCommandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route(path: '/user')]
final class UserController extends AbstractController
{
    use FormattedErrorsTrait;

    #[Route(methods: 'POST')]
    public function create(
        Request                        $request,
        ValidatorInterface             $validator,
        CreateUserCommandHandler       $createUserCommandHandler,
        SendNotificationCommandHandler $sendNotificationToKafkaCommandHandler,
    ): JsonResponse
    {
        $createUserCommand = new CreateUserCommand(
            name: $request->get('name', ''),
            email: $request->get('email', ''),
            phone: (int)$request->get('phone', 0),
            promoId: $request->get('promoId'),
        );

        $errors = $validator->validate($createUserCommand);
        if (count($errors) > 0) {
            return new JsonResponse(
                data: [
                    'errors' => $this->getFormattedErrors($errors),
                ],
                status: JsonResponse::HTTP_BAD_REQUEST,
            );
        }

        $createUserCommandHandler($createUserCommand);
        $sendNotificationToKafkaCommandHandler($createUserCommand, KafkaTopicEnum::NEW_USER->value);

        return new JsonResponse(null, JsonResponse::HTTP_CREATED);
    }
}
