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
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class User extends AbstractBaseEntity implements UserInterface
{
    #[ORM\Embedded(class: Name::class, columnPrefix: false)]
    private Name $name;

    #[ORM\Embedded(class: Email::class, columnPrefix: false)]
    private Email $email;

    #[ORM\Embedded(class: RuPhoneNumber::class, columnPrefix: false)]
    private RuPhoneNumber $phone;

    #[ORM\Column(type: 'uuid', nullable: true)]
    private ?UuidV4 $promoId;

    #[ORM\Column(type: 'string')]
    private ?string $password = null;

    #[ORM\JoinTable(name: 'roles_users')]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[ORM\InverseJoinColumn(name: 'role_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    #[ORM\ManyToMany(targetEntity: Role::class)]
    private Collection $roles;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Order::class)]
    private Collection $orders;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: CartProduct::class)]
    private Collection $cartProducts;

    public static function create(
        Name              $name,
        Email             $email,
        RuPhoneNumber     $phone,
        ?UuidV4           $promoId,
        Collection        $roles = new ArrayCollection(),
        DateTimeImmutable $createdAt = new DateTimeImmutable(),
        DateTimeImmutable $updatedAt = new DateTimeImmutable(),
    ): User
    {
        return (new self())
            ->setName($name)
            ->setEmail($email)
            ->setPhone($phone)
            ->setPromoId($promoId)
            ->setRoles($roles)
            ->setCreatedAt($createdAt)
            ->setUpdatedAt($updatedAt);
    }

    public function eraseCredentials(): void
    {
        $this->password = null;
    }

    public function getUserIdentifier(): string
    {
        return $this->email->getEmail();
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

    public function setEmail(Email $email): User
    {
        $this->email = $email;
        return $this;
    }

    public function setPhone(RuPhoneNumber $phone): User
    {
        $this->phone = $phone;
        return $this;
    }

    public function setPromoId(?UuidV4 $promoId): User
    {
        $this->promoId = $promoId;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return $this->roles->map(fn(Role $role) => $role->getSlug())->toArray();
    }

    public function setRoles(Collection $roles): User
    {
        $this->roles = $roles;
        return $this;
    }
}