<?php

declare(strict_types=1);

namespace App\Order\Domain\Repository;

use App\Order\Domain\Entity\DeliveryType;

interface DeliveryTypeRepositoryInterface
{
    public function add(DeliveryType $deliveryType): void;

    public function findAll(): array;

    public function getBySlug(string $slug): DeliveryType;
}
