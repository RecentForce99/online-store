<?php

declare(strict_types=1);

namespace App\Order\Domain\Repository;

use App\Order\Domain\Entity\DeliveryType;

interface DeliveryTypeRepositoryInterface
{
    public function create(DeliveryType $deliveryType): void;
    public function findAll(): array;
}