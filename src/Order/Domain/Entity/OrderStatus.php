<?php

declare(strict_types=1);

namespace App\Order\Domain\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_statuses')]
class OrderStatus
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 20)]
    private string $slug;

    #[ORM\Column(type: 'string', unique: true, length: 20)]
    private string $name;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $notifiable;

    #[ORM\OneToMany(mappedBy: 'status', targetEntity: Order::class)]
    private Collection $orders;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $updatedAt;

    private function __construct(string $slug)
    {
        $this->slug = $slug;
    }

    public static function create(
        string $slug,
        string $name,
        bool $notifiable,
        DateTimeImmutable $createdAt = new DateTimeImmutable(),
        DateTimeImmutable $updatedAt = new DateTimeImmutable(),
    ): self {
        return (new static($slug))
            ->setName($name)
            ->setNotifiable($notifiable)
            ->setCreatedAt($createdAt)
            ->setUpdatedAt($updatedAt);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setNotifiable(bool $notifiable): self
    {
        $this->notifiable = $notifiable;

        return $this;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
