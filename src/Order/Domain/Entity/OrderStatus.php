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
#[Table(name: 'order_statuses')]
class OrderStatus
{
    #[Id]
    #[Column(type: 'string', length: 20)]
    private string $slug;

    #[Column(type: 'string', unique: true, length: 20)]
    private string $name;

    #[Column(type: 'boolean', options: ['default' => false])]
    private bool $notifiable;

    #[OneToMany(mappedBy: 'status', targetEntity: Order::class)]
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
        bool              $notifiable,
        DateTimeImmutable $createdAt = new DateTimeImmutable(),
        DateTimeImmutable $updatedAt = new DateTimeImmutable(),
    ): OrderStatus
    {
        return (new static($slug))
            ->setName($name)
            ->setNotifiable($notifiable)
            ->setCreatedAt($createdAt)
            ->setUpdatedAt($updatedAt);
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): OrderStatus
    {
        $this->name = $name;
        return $this;
    }

    public function isNotifiable(): bool
    {
        return $this->notifiable;
    }

    public function setNotifiable(bool $notifiable): OrderStatus
    {
        $this->notifiable = $notifiable;
        return $this;
    }

    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function setOrders(Collection $orders): OrderStatus
    {
        $this->orders = $orders;
        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): OrderStatus
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): OrderStatus
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}

