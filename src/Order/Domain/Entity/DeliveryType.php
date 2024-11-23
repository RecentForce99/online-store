<?php

declare(strict_types=1);

namespace App\Order\Domain\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'delivery_types')]
class DeliveryType
{
    #[Id]
    #[Column(type: 'string', length: 20)]
    private string $name;

    #[Column(type: 'string', unique: true, length: 20)]
    private string $slug;

    #[OneToMany(mappedBy: 'deliveryType', targetEntity: Order::class)]
    private Collection $orders;

    #[Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[Column(type: 'datetime_immutable')]
    private DateTimeImmutable $updatedAt;

    private function __construct(string $slug)
    {
        $this->slug = $slug;
    }

    public static function create(
        string            $slug,
        string            $name,
        DateTimeImmutable $createdAt = new DateTimeImmutable(),
        DateTimeImmutable $updatedAt = new DateTimeImmutable(),
    ): DeliveryType
    {
        return (new static($slug))
            ->setName($name)
            ->setCreatedAt($createdAt)
            ->setUpdatedAt($updatedAt);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): DeliveryType
    {
        $this->name = $name;
        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function setOrders(Collection $orders): DeliveryType
    {
        $this->orders = $orders;
        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): DeliveryType
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): DeliveryType
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
