<?php

declare(strict_types=1);

namespace App\Order\Domain\Entity;

use App\Common\Domain\Entity\AbstractBaseEntity;
use App\User\Domain\Entity\User;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'orders')]
class Order extends AbstractBaseEntity
{
    #[Column(
        type: 'bigint',
        nullable: true,
        options: [
            'comment' => 'This field will be used as custom phone number that can be defined',
        ]),
    ]
    private ?int $phone;

    #[ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ManyToOne(targetEntity: OrderStatus::class, inversedBy: 'orders')]
    #[JoinColumn(name: 'status_slug', referencedColumnName: 'slug', nullable: false, onDelete: 'RESTRICT')]
    private OrderStatus $status;

    #[ManyToOne(targetEntity: DeliveryType::class, inversedBy: 'orders')]
    #[JoinColumn(name: 'delivery_type_slug', referencedColumnName: 'slug', nullable: false, onDelete: 'RESTRICT')]
    private DeliveryType $deliveryType;

    #[OneToMany(mappedBy: 'order', targetEntity: OrderProduct::class)]
    private Collection $orderProducts;

    public static function create(
        User              $user,
        ?int              $phone,
        OrderStatus       $status,
        DeliveryType      $deliveryType,
        DateTimeImmutable $createdAt = new DateTimeImmutable(),
        DateTimeImmutable $updatedAt = new DateTimeImmutable(),
    ): Order
    {
        return (new self())
            ->setUser($user)
            ->setPhone($phone)
            ->setStatus($status)
            ->setDeliveryType($deliveryType)
            ->setCreatedAt($createdAt)
            ->setUpdatedAt($updatedAt);
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function setPhone(?int $phone): Order
    {
        $this->phone = $phone;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): Order
    {
        $this->user = $user;
        return $this;
    }

    public function getStatus(): OrderStatus
    {
        return $this->status;
    }

    public function setStatus(OrderStatus $status): Order
    {
        $this->status = $status;
        return $this;
    }

    public function getDeliveryType(): DeliveryType
    {
        return $this->deliveryType;
    }

    public function setDeliveryType(DeliveryType $deliveryType): Order
    {
        $this->deliveryType = $deliveryType;
        return $this;
    }

    public function getOrderProducts(): Collection
    {
        return $this->orderProducts;
    }

    public function setOrderProducts(Collection $orderProducts): Order
    {
        $this->orderProducts = $orderProducts;
        return $this;
    }
}
