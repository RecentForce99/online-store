<?php

declare(strict_types=1);

namespace App\Order\Domain\Entity;

use App\Common\Domain\Entity\AbstractBaseEntity;
use App\User\Domain\Entity\User;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'orders')]
class Order extends AbstractBaseEntity
{
    #[ORM\Column(
        type: 'bigint',
        nullable: true,
        options: [
            'comment' => 'This field will be used as custom phone number that can be defined',
        ]),
    ]
    private ?int $phone;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\ManyToOne(targetEntity: OrderStatus::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(name: 'status_slug', referencedColumnName: 'slug', nullable: false, onDelete: 'RESTRICT')]
    private OrderStatus $status;

    #[ORM\ManyToOne(targetEntity: DeliveryType::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(name: 'delivery_type_slug', referencedColumnName: 'slug', nullable: false, onDelete: 'RESTRICT')]
    private DeliveryType $deliveryType;

    #[ORM\OneToMany(mappedBy: 'order', targetEntity: OrderProduct::class)]
    private Collection $orderProducts;

    public static function create(
        User              $user,
        ?int              $phone,
        OrderStatus       $status,
        DeliveryType      $deliveryType,
        DateTimeImmutable $createdAt = new DateTimeImmutable(),
        DateTimeImmutable $updatedAt = new DateTimeImmutable(),
    ): self
    {
        return (new self())
            ->setUser($user)
            ->setPhone($phone)
            ->setStatus($status)
            ->setDeliveryType($deliveryType)
            ->setCreatedAt($createdAt)
            ->setUpdatedAt($updatedAt);
    }

    public function setPhone(?int $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function setStatus(OrderStatus $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function setDeliveryType(DeliveryType $deliveryType): self
    {
        $this->deliveryType = $deliveryType;
        return $this;
    }
}
