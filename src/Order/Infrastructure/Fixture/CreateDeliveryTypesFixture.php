<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Fixture;

use App\Common\Infrastructure\Repository\Flusher;
use App\Order\Domain\Entity\DeliveryType;
use App\Order\Domain\Repository\DeliveryTypeRepositoryInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ObjectManager;

final class CreateDeliveryTypesFixture extends Fixture
{
    public function __construct(
        private readonly DeliveryTypeRepositoryInterface $deliveryTypeRepository,
        private readonly Flusher $flusher,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $existingDeliveryTypes = $this->deliveryTypeRepository->findAll();

        /* @var DeliveryType $deliveryType */
        foreach ($this->getDeliveryTypesToCreate() as $deliveryType) {
            if (false === $this->doesDeliveryTypeExist($existingDeliveryTypes, $deliveryType)) {
                $this->createDeliveryType($deliveryType);
            }
        }

        $this->flusher->flush();
    }

    private function getDeliveryTypesToCreate(): Collection
    {
        return new ArrayCollection([
            DeliveryType::create(
                slug: 'payment_required',
                name: 'Ожидается оплата',
            ),
            DeliveryType::create(
                slug: 'payment_successful',
                name: 'Оплачен',
            ),
            DeliveryType::create(
                slug: 'assembly_awaited',
                name: 'Ждёт сборки',
            ),
            DeliveryType::create(
                slug: 'assembling',
                name: 'В сборке',
            ),
            DeliveryType::create(
                slug: 'delivering',
                name: 'Доставляется',
            ),
            DeliveryType::create(
                slug: 'ready',
                name: 'Готов к выдаче',
            ),
            DeliveryType::create(
                slug: 'received',
                name: 'Получен',
            ),
            DeliveryType::create(
                slug: 'canceled',
                name: 'Отменён',
            ),
        ]);
    }

    private function doesDeliveryTypeExist(array $existingDeliveryTypes, DeliveryType $deliveryType): bool
    {
        /* @var DeliveryType $deliveryType */
        foreach ($existingDeliveryTypes as $existingDeliveryType) {
            if ($deliveryType->getSlug() === $existingDeliveryType->getSlug()) {
                return true;
            }
        }

        return false;
    }

    private function createDeliveryType(DeliveryType $deliveryType): void
    {
        $this->deliveryTypeRepository->create($deliveryType);
    }
}
