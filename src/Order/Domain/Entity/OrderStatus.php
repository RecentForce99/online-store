<?php

declare(strict_types=1);

namespace App\Order\Domain\Entity;

use App\Common\Domain\Entity\AbstractBaseEntity;
use App\Common\Domain\Trait\HasDatetime;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'order_statuses')]
#[ORM\HasLifecycleCallbacks]
class OrderStatus extends AbstractBaseEntity
{
    use HasDatetime;

    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 20)]
    private string $slug;

    #[ORM\Column(type: 'string', unique: true, length: 20)]
    private string $name;

    #[ORM\Column(type: 'boolean', options: ['default' => false])]
    private bool $notifiable;

    #[ORM\OneToMany(mappedBy: 'status', targetEntity: Order::class)]
    private Collection $orders;

    public static function create(
        string $slug,
        string $name,
        bool $notifiable = false,
        DateTimeImmutable $createdAt = new DateTimeImmutable(),
        DateTimeImmutable $updatedAt = new DateTimeImmutable(),
    ): self {
        return (new static())
            ->setSlug($slug)
            ->setName($name)
            ->setNotifiable($notifiable)
            ->setCreatedAt($createdAt)
            ->setUpdatedAt($updatedAt);
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    private function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
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
}
