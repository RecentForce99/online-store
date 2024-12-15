<?php

declare(strict_types=1);

namespace App\Order\Application\UseCase\CheckoutOrder;

use App\Common\Domain\Exception\Validation\GreaterThanMaxLengthException;
use App\Common\Domain\Exception\Validation\GreaterThanMaxValueException;
use App\Common\Domain\Exception\Validation\LessThanMinValueException;
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
use App\User\Domain\ValueObject\Delivery;
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
     * @throws GreaterThanMaxValueException
     * @throws InvalidDeliveryTypeException
     * @throws LessThanMinValueException
     * @throws UserNotFoundException
     * @throws GreaterThanMaxLengthException
     */
    public function __invoke(CheckoutOrderCommand $checkoutOrderCommand): void
    {
        $user = $this->userRepository->getById($checkoutOrderCommand->userId);

        $userDelivery = $user->getDelivery();
        $currentDelivery = Delivery::create(
            $checkoutOrderCommand->address ?? $userDelivery->getAddress(),
            $checkoutOrderCommand->kladrId ?? $userDelivery->getKladrId(),
        );
        $currentDeliveryType = $this->deliveryTypeRepository->getBySlug($checkoutOrderCommand->deliveryType);

        $this->assertUserCantCheckoutOrder(
            $user,
            $checkoutOrderCommand,
            $currentDeliveryType,
        );

        $paymentRequiredOrderStatus = $this->orderStatusRepository->getPaymentRequiredOrderStatus();

        $order = Order::create(
            user: $user,
            phone: $checkoutOrderCommand->phone ?? $user->getPhone()->getPhone(),
            status: $paymentRequiredOrderStatus,
            delivery: $currentDelivery,
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
        if (20 < $countOfProducts) {
            throw CartIsOverflowingException::byCountOfProducts($countOfProducts);
        }

        if (null === $currentDeliveryType) {
            throw InvalidDeliveryTypeException::bySlug($checkoutOrderCommand->deliveryType);
        }
    }
}
