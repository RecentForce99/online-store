<?php

declare(strict_types=1);

namespace App\Order\Domain\Entity;

use App\Cart\Domain\Entity\CartProduct;
use App\Common\Domain\Entity\AbstractBaseEntity;
use App\Common\Domain\Trait\HasDatetime;
use App\Common\Domain\Trait\HasId;
use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\Delivery;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity]
#[ORM\Table(name: 'orders')]
#[HasLifecycleCallbacks]
class Order extends AbstractBaseEntity
{
    use HasId;
    use HasDatetime;

    #[ORM\Column(
        type: 'bigint',
        nullable: true,
        options: [
            'comment' => 'This field will be used as custom phone number that can be defined',
        ]
    ),
    ]
    private ?int $phone;

    #[ORM\Embedded(class: Delivery::class)]
    private Delivery $delivery;

    #[ORM\ManyToOne(targetEntity: DeliveryType::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(name: 'delivery_type_slug', referencedColumnName: 'slug', nullable: false, onDelete: 'RESTRICT')]
    private DeliveryType $deliveryType;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(name: 'user_id', nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[ORM\ManyToOne(targetEntity: OrderStatus::class, inversedBy: 'orders')]
    #[ORM\JoinColumn(name: 'status_slug', referencedColumnName: 'slug', nullable: false, onDelete: 'RESTRICT')]
    private OrderStatus $status;

    #[ORM\OneToMany(
        mappedBy: 'order',
        targetEntity: OrderProduct::class,
        cascade: [
            'persist',
        ],
    )]
    private Collection $orderProducts;

    public static function create(
        User $user,
        ?int $phone,
        OrderStatus $status,
        Delivery $delivery,
        DeliveryType $deliveryType,
        Collection $orderProducts = new ArrayCollection(),
        DateTimeImmutable $createdAt = new DateTimeImmutable(),
        DateTimeImmutable $updatedAt = new DateTimeImmutable(),
    ): self {
        $order = new self();

        $order->id = new UuidV4();
        $order->user = $user;
        $order->phone = $phone;
        $order->status = $status;
        $order->delivery = $delivery;
        $order->deliveryType = $deliveryType;
        $order->orderProducts = $orderProducts;
        $order->createdAt = $createdAt;
        $order->updatedAt = $updatedAt;

        return $order;
    }

    public function addOrderProductsFromCartProducts(Collection $cartProducts): void
    {
        /* @var CartProduct $cartProduct */
        foreach ($cartProducts as $cartProduct) {
            $orderProduct = OrderProduct::create(
                order: $this,
                product: $cartProduct->getProduct(),
                quantity: $cartProduct->getQuantity(),
            );

            $this->orderProducts->add($orderProduct);
        }
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

    public function setOrderProducts(Collection $orderProducts): self
    {
        $this->orderProducts = $orderProducts;

        return $this;
    }

    public function getPhone(): ?int
    {
        return $this->phone;
    }

    public function getStatus(): OrderStatus
    {
        return $this->status;
    }

    public function setDelivery(Delivery $delivery): self
    {
        $this->delivery = $delivery;

        return $this;
    }

    public function getOrderProducts(): Collection
    {
        return $this->orderProducts;
    }

    public function getDelivery(): Delivery
    {
        return $this->delivery;
    }

    public function getDeliveryType(): DeliveryType
    {
        return $this->deliveryType;
    }
}
