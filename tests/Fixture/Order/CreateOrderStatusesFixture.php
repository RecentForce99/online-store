<?php

declare(strict_types=1);

namespace App\Tests\Fixture\Order;

use App\Common\Infrastructure\Repository\Flusher;
use App\Order\Domain\Entity\OrderStatus;
use App\Order\Domain\Repository\OrderStatusRepositoryInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ObjectManager;

final class CreateOrderStatusesFixture extends Fixture
{
    public function __construct(
        private readonly OrderStatusRepositoryInterface $orderStatusRepository,
        private readonly Flusher $flusher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $existingOrderStatuses = $this->orderStatusRepository->findAll();

        /* @var OrderStatus $orderStatus */
        foreach ($this->getOrderStatusesCreate() as $orderStatus) {
            if (false === $this->doesOrderStatusExist($existingOrderStatuses, $orderStatus)) {
                $this->createOrderStatus($orderStatus);
            }
        }

        $this->flusher->flush();
    }

    private function getOrderStatusesCreate(): Collection
    {
        return new ArrayCollection([
            OrderStatus::create(
                slug: 'payment_required',
                name: 'Ожидается оплата',
            ),
            OrderStatus::create(
                slug: 'payment_successful',
                name: 'Оплачен',
                notifiable: true,
            ),
            OrderStatus::create(
                slug: 'assembly_awaited',
                name: 'Ждёт сборки',
            ),
            OrderStatus::create(
                slug: 'assembling',
                name: 'В сборке',
            ),
            OrderStatus::create(
                slug: 'delivering',
                name: 'Доставляется',
            ),
            OrderStatus::create(
                slug: 'ready',
                name: 'Готов к выдаче',
                notifiable: true,
            ),
            OrderStatus::create(
                slug: 'received',
                name: 'Получен',
                notifiable: true,
            ),
            OrderStatus::create(
                slug: 'canceled',
                name: 'Отменён',
                notifiable: true,
            ),
        ]);
    }

    private function doesOrderStatusExist(array $existingOrderStatuses, OrderStatus $orderStatus): bool
    {
        /* @var OrderStatus $orderStatus */
        foreach ($existingOrderStatuses as $existingOrderStatus) {
            if ($orderStatus->getSlug() === $existingOrderStatus->getSlug()) {
                return true;
            }
        }

        return false;
    }

    private function createOrderStatus(OrderStatus $orderStatus): void
    {
        $this->orderStatusRepository->add($orderStatus);
    }
}
