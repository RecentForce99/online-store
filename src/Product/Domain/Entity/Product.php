<?php

declare(strict_types=1);

namespace App\Product\Domain\Entity;

use App\Cart\Domain\Entity\CartProduct;
use App\Common\Domain\Entity\AbstractBaseEntity;
use App\Common\Domain\Trait\HasDatetime;
use App\Common\Domain\Trait\HasId;
use App\Order\Domain\Entity\OrderProduct;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Uid\UuidV4;

#[Entity]
#[Table(name: 'products')]
#[HasLifecycleCallbacks]
class Product extends AbstractBaseEntity
{
    use HasId;
    use HasDatetime;

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
        string $name,
        int $weight,
        int $height,
        int $width,
        int $length,
        ?string $description,
        int $cost,
        int $tax,
        int $version,
        UuidV4 $id = new UuidV4(),
        DateTimeImmutable $createdAt = new DateTimeImmutable(),
        DateTimeImmutable $updatedAt = new DateTimeImmutable(),
    ): self {
        return (new self())
            ->setId($id)
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

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function setHeight(int $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getWidth(): int
    {
        return $this->width;
    }

    public function setWidth(int $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function setLength(int $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function setCost(int $cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function setTax(int $tax): self
    {
        $this->tax = $tax;

        return $this;
    }

    public function getVersion(): int
    {
        return $this->version;
    }

    public function setVersion(int $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getCost(): int
    {
        return $this->cost;
    }
}
