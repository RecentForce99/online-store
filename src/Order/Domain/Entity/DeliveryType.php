<?php

declare(strict_types=1);

namespace App\Order\Domain\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'delivery_types')]
class DeliveryType
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 20)]
    private string $name;

    #[ORM\Column(type: 'string', unique: true, length: 20)]
    private string $slug;

    #[ORM\OneToMany(mappedBy: 'deliveryType', targetEntity: Order::class)]
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
        DateTimeImmutable $createdAt = new DateTimeImmutable(),
        DateTimeImmutable $updatedAt = new DateTimeImmutable(),
    ): self {
        return (new static($slug))
            ->setName($name)
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

    public function getSlug(): string
    {
        return $this->slug;
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
