<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Controller;

use App\Order\Application\UseCase\CheckoutOrder\CheckoutOrderCommand;
use App\User\Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/order')]
final class OrderController extends AbstractController
{
    /**
     * @throws ExceptionInterface
     */
    #[Route(methods: ['POST'], name: 'order.checkout')]
    public function checkoutOrder(
        #[MapRequestPayload] CheckoutOrderCommand $checkoutOrderCommand,
        MessageBusInterface $messageBus,
    ): JsonResponse {
        /* @var User $user */
        $user = $this->getUser();
        $checkoutOrderCommand->userId = $user->getId()->toString();

        $messageBus->dispatch($checkoutOrderCommand);

        return new JsonResponse(status: Response::HTTP_CREATED);
    }
}
