<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Controller;

use App\Order\Application\Exception\CartIsEmptyException;
use App\Order\Application\Exception\CartIsOverflowingException;
use App\Order\Application\Exception\InvalidDeliveryTypeException;
use App\Order\Application\UseCase\CheckoutOrder\CheckoutOrderCommand;
use App\Order\Application\UseCase\CheckoutOrder\CheckoutOrderCommandHandler;
use App\User\Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/order')]
final class OrderController extends AbstractController
{
    /**
     * @throws CartIsEmptyException
     * @throws CartIsOverflowingException
     * @throws InvalidDeliveryTypeException
     */
    #[Route(methods: ['POST'])]
    public function checkoutOrder(
        #[MapRequestPayload] CheckoutOrderCommand $checkoutOrderCommand,
        CheckoutOrderCommandHandler $checkoutOrderCommandHandler,
    ): JsonResponse {
        /* @var User $user */
        $user = $this->getUser();
        $checkoutOrderCommandHandler(
            $user,
            $checkoutOrderCommand,
        );

        return new JsonResponse();
    }
}
