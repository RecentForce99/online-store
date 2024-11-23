<?php

declare(strict_types=1);

namespace App\User\Domain\Entity;

use App\Cart\Domain\Entity\CartProduct;
use App\Common\Domain\Entity\AbstractBaseEntity;
use App\Common\Domain\ValueObject\Email;
use App\Common\Domain\ValueObject\RuPhoneNumber;
use App\Order\Domain\Entity\Order;
use App\Role\Domain\Entity\Role;
use App\User\Domain\ValueObject\Name;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Uid\UuidV4;

#[Entity]
#[Table(name: 'users')]
class User extends AbstractBaseEntity
{
    #[Column(type: 'string', length: 255)]
    private Name $name;

    #[Column(type: 'string', unique: true, length: 255)]
    private Email $email;

    #[Column(type: 'bigint', unique: true, options: ['unsigned' => true])]
    private RuPhoneNumber $phone;

    #[Column(type: 'uuid', nullable: true)]
    private ?UuidV4 $promoId;

    #[ManyToOne(targetEntity: Role::class)]
    #[JoinColumn(name: 'role_slug', referencedColumnName: 'slug', nullable: false, onDelete: 'RESTRICT')]
    private Role $role;

    #[OneToMany(mappedBy: 'user', targetEntity: Order::class)]
    private Collection $orders;

    #[OneToMany(mappedBy: 'user', targetEntity: CartProduct::class)]
    private Collection $cartProducts;

    public static function create(
        Name              $name,
        Email             $email,
        RuPhoneNumber     $phone,
        ?UuidV4           $promoId, // UuidV4 can be replaced with a service interface to avoid overlapping layers
        Role              $role,
        DateTimeImmutable $createdAt = new DateTimeImmutable(),
        DateTimeImmutable $updatedAt = new DateTimeImmutable(),
    ): User
    {
        return (new self())
            ->setName($name)
            ->setEmail($email)
            ->setPhone($phone)
            ->setPromoId($promoId)
            ->setRole($role)
            ->setCreatedAt($createdAt)
            ->setUpdatedAt($updatedAt);
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function setName(Name $name): User
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function setEmail(Email $email): User
    {
        $this->email = $email;
        return $this;
    }

    public function getPhone(): RuPhoneNumber
    {
        return $this->phone;
    }

    public function setPhone(RuPhoneNumber $phone): User
    {
        $this->phone = $phone;
        return $this;
    }

    public function getPromoId(): ?UuidV4
    {
        return $this->promoId;
    }

    public function setPromoId(?UuidV4 $promoId): User
    {
        $this->promoId = $promoId;
        return $this;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function setRole(Role $role): User
    {
        $this->role = $role;
        return $this;
    }

    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function setOrders(Collection $orders): User
    {
        $this->orders = $orders;
        return $this;
    }

    public function getCartProducts(): Collection
    {
        return $this->cartProducts;
    }

    public function setCartProducts(Collection $cartProducts): User
    {
        $this->cartProducts = $cartProducts;
        return $this;
    }
}