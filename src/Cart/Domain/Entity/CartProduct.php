<?php

declare(strict_types=1);

namespace App\Cart\Domain\Entity;

use App\Product\Domain\Entity\Product;
use App\User\Domain\Entity\User;
use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;

#[Entity]
#[Table(name: 'cart_products')]
class CartProduct
{
    #[Id]
    #[ManyToOne(targetEntity: User::class, inversedBy: 'cartProducts')]
    #[JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private User $user;

    #[Id]
    #[ManyToOne(targetEntity: Product::class, inversedBy: 'cartProducts')]
    #[JoinColumn(name: 'product_id', referencedColumnName: 'id', nullable: false, onDelete: 'CASCADE')]
    private Product $product;

    #[Column(type: 'integer', options: ['default' => 0])]
    private int $quantity;

    #[Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[Column(type: 'datetime_immutable')]
    private DateTimeImmutable $updatedAt;

    public static function create(
        User              $user,
        Product           $product,
        int               $quantity = 0,
        DateTimeImmutable $createdAt = new DateTimeImmutable(),
        DateTimeImmutable $updatedAt = new DateTimeImmutable(),
    ): CartProduct
    {
        return (new static())
            ->setUser($user)
            ->setProduct($product)
            ->setQuantity($quantity)
            ->setCreatedAt($createdAt)
            ->setUpdatedAt($updatedAt);
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): CartProduct
    {
        $this->user = $user;
        return $this;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): CartProduct
    {
        $this->product = $product;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): CartProduct
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): CartProduct
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): CartProduct
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
