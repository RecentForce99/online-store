<?php

declare(strict_types=1);

namespace App\Order\Application\UseCase\CheckoutOrder;

use App\Cart\Domain\Repository\CartProductRepositoryInterface;
use App\Common\Infrastructure\Repository\Flusher;
use App\Order\Application\Exception\CartIsEmptyException;
use App\Order\Application\Exception\CartIsOverflowingException;
use App\Order\Application\Exception\InvalidDeliveryTypeException;
use App\Order\Domain\Entity\DeliveryType;
use App\Order\Domain\Entity\Order;
use App\Order\Domain\Repository\DeliveryTypeRepositoryInterface;
use App\Order\Domain\Repository\OrderRepositoryInterface;
use App\Order\Domain\Repository\OrderStatusRepositoryInterface;
use App\User\Domain\Entity\User;

final class CheckoutOrderCommandHandler
{
    private const string PAYMENT_REQUIRED_ORDER_STATUS = 'payment_required';

    public function __construct(
        private readonly DeliveryTypeRepositoryInterface $deliveryTypeRepository,
        private readonly OrderStatusRepositoryInterface $orderStatusRepository,
        private readonly OrderRepositoryInterface $orderRepository,
        private readonly CartProductRepositoryInterface $cartProductRepository,
        private readonly Flusher $flusher,
    ) {
    }

    /**
     * @throws CartIsEmptyException
     * @throws CartIsOverflowingException
     * @throws InvalidDeliveryTypeException
     */
    public function __invoke(
        User $user,
        CheckoutOrderCommand $checkoutOrderCommand,
    ): void {
        $user->getCartProducts()->initialize();

        $deliveryTypes = $this->deliveryTypeRepository->findAll();
        $currentDeliveryType = $this->getCurrentDeliveryType(
            $deliveryTypes,
            $checkoutOrderCommand,
        );

        $this->validate(
            $user,
            $checkoutOrderCommand,
            $currentDeliveryType,
        );

        $paymentRequiredOrderStatus = $this->orderStatusRepository->findBySlug(self::PAYMENT_REQUIRED_ORDER_STATUS);

        $order = Order::create(
            user: $user,
            phone: $checkoutOrderCommand->phone,
            status: $paymentRequiredOrderStatus,
            deliveryType: $currentDeliveryType,
        );

        // TODO Add notification dispatching

        $this->orderRepository->add($order);
        $this->cartProductRepository->clear($user);
        $this->flusher->flush();
    }

    /**
     * @throws CartIsEmptyException
     * @throws CartIsOverflowingException
     * @throws InvalidDeliveryTypeException
     */
    private function validate(
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

    private function getCurrentDeliveryType(
        array $deliveryTypes,
        CheckoutOrderCommand $checkoutOrderCommand,
    ): ?DeliveryType {
        $deliveryTypes = array_filter($deliveryTypes, function (DeliveryType $deliveryType) use ($checkoutOrderCommand) {
            return $deliveryType->getSlug() === $checkoutOrderCommand->deliveryType;
        });

        $deliveryType = current($deliveryTypes);

        return $deliveryType instanceof DeliveryType ? $deliveryType : null;
    }
}
