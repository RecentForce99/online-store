<?php

declare(strict_types=1);

namespace App\Product\Domain\Entity;

use App\Cart\Domain\Entity\CartProduct;
use App\Common\Domain\Entity\AbstractBaseEntity;
use App\Order\Domain\Entity\OrderProduct;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'products')]
class Product extends AbstractBaseEntity
{
    #[Column(type: 'string', length: 255)]
    private string $name;

    #[Column(type: 'integer')]
    private int $weight;

    #[Column(type: 'integer')]
    private int $height;

    #[Column(type: 'integer')]
    private int $width;

    #[Column(type: 'integer')]
    private int $length;

    #[Column(type: 'text', nullable: true)]
    private ?string $description;

    #[Column(type: 'integer')]
    private int $cost;

    #[Column(type: 'integer')]
    private int $tax;

    #[Column(type: 'smallint')]
    private int $version;

    #[OneToMany(mappedBy: 'product', targetEntity: OrderProduct::class)]
    private Collection $orderProducts;

    #[OneToMany(mappedBy: 'product', targetEntity: CartProduct::class)]
    private Collection $cartProducts;

    public static function create(
        string            $name,
        int               $weight,
        int               $height,
        int               $width,
        int               $length,
        ?string           $description,
        int               $cost,
        int               $tax,
        int               $version,
        DateTimeImmutable $createdAt = new DateTimeImmutable(),
        DateTimeImmutable $updatedAt = new DateTimeImmutable(),
    ): Product
    {
        return (new self())
            ->setName($name)
            ->setWeight($weight)
            ->setHeight($height)
            ->setWidth($width)
            ->setLength($length)
            ->setDescription($description)
            ->setCost($cost)
            ->setTax($tax)
            ->setVersion($version)
            ->setCreatedAt($createdAt)
            ->setUpdatedAt($updatedAt);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Product
    {
        $this->name = $name;
        return $this;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): Product
    {
        $this->weight = $weight;
        return $this;
    }

    public function getHeight(): int
    {
        return $this->height;
    }

    public function setHeight(int $height): Product
    {
        $this->height = $height;
        return $this;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function setWidth(int $width): Product
    {
        $this->width = $width;
        return $this;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function setLength(int $length): Product
    {
        $this->length = $length;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): Product
    {
        $this->description = $description;
        return $this;
    }

    public function getCost(): int
    {
        return $this->cost;
    }

    public function setCost(int $cost): Product
    {
        $this->cost = $cost;
        return $this;
    }

    public function getTax(): int
    {
        return $this->tax;
    }

    public function setTax(int $tax): Product
    {
        $this->tax = $tax;
        return $this;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function setVersion(int $version): Product
    {
        $this->version = $version;
        return $this;
    }

    public function getOrderProducts(): Collection
    {
        return $this->orderProducts;
    }

    public function setOrderProducts(Collection $orderProducts): Product
    {
        $this->orderProducts = $orderProducts;
        return $this;
    }

    public function getCartProducts(): Collection
    {
        return $this->cartProducts;
    }

    public function setCartProducts(Collection $cartProducts): Product
    {
        $this->cartProducts = $cartProducts;
        return $this;
    }
}
