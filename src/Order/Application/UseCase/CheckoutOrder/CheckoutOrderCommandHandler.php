<?php

declare(strict_types=1);

namespace App\Order\Application\UseCase\CheckoutOrder;

use App\Common\Infrastructure\Repository\Flusher;
use App\Order\Application\Exception\CartIsEmptyException;
use App\Order\Application\Exception\CartIsOverflowingException;
use App\Order\Application\Exception\InvalidDeliveryTypeException;
use App\Order\Domain\Entity\DeliveryType;
use App\Order\Domain\Entity\Order;
use App\Order\Domain\Repository\DeliveryTypeRepositoryInterface;
use App\Order\Domain\Repository\OrderStatusRepositoryInterface;
use App\User\Application\Exception\UserNotFoundException;
use App\User\Domain\Entity\User;
use App\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class CheckoutOrderCommandHandler
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly DeliveryTypeRepositoryInterface $deliveryTypeRepository,
        private readonly OrderStatusRepositoryInterface $orderStatusRepository,
        private readonly Flusher $flusher,
    ) {
    }

    /**
     * @throws CartIsEmptyException
     * @throws CartIsOverflowingException
     * @throws InvalidDeliveryTypeException
     * @throws UserNotFoundException
     */
    public function __invoke(CheckoutOrderCommand $checkoutOrderCommand): void
    {
        $user = $this->userRepository->getById($checkoutOrderCommand->userId);
        $currentDeliveryType = $this->deliveryTypeRepository->getBySlug($checkoutOrderCommand->deliveryType);

        $this->assertUserCantCheckoutOrder(
            $user,
            $checkoutOrderCommand,
            $currentDeliveryType,
        );

        $paymentRequiredOrderStatus = $this->orderStatusRepository->getPaymentRequiredOrderStatus();

        // TODO Add notification dispatching

        $order = Order::create(
            user: $user,
            phone: $checkoutOrderCommand->phone,
            status: $paymentRequiredOrderStatus,
            deliveryType: $currentDeliveryType,
        );

        $user->checkoutOrder($order);
        $this->flusher->flush();
    }

    /**
     * @throws CartIsEmptyException
     * @throws CartIsOverflowingException
     * @throws InvalidDeliveryTypeException
     */
    private function assertUserCantCheckoutOrder(
        User $user,
        CheckoutOrderCommand $checkoutOrderCommand,
        ?DeliveryType $currentDeliveryType,
    ): void {
        if ($user->getCartProducts()->isEmpty()) {
            throw CartIsEmptyException::emptyCart();
        }

        $countOfProducts = $user->getCartProducts()->count();
        if ($countOfProducts > 20) {
            throw CartIsOverflowingException::byCountOfProducts($countOfProducts);
        }

        if (null === $currentDeliveryType) {
            throw InvalidDeliveryTypeException::bySlug($checkoutOrderCommand->deliveryType);
        }
    }
}
