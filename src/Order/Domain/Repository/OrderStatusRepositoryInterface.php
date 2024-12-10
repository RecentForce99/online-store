<?php

declare(strict_types=1);

namespace App\Order\Domain\Repository;

use App\Order\Domain\Entity\OrderStatus;

interface OrderStatusRepositoryInterface
{
    public function add(OrderStatus $orderStatus): void;

    public function findBySlug(string $slug): ?OrderStatus;

    public function findAll(): array;

    public function getPaymentRequiredOrderStatus(): OrderStatus;
}
