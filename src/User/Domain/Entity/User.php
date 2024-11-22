<?php

declare(strict_types=1);

namespace App\User\Domain\Entity;

use App\Common\Domain\Entity\AbstractBaseEntity;
use App\Common\Domain\ValueObject\Email;
use App\Common\Domain\ValueObject\RuPhoneNumber;
use App\Role\Domain\Entity\Role;
use App\User\Domain\ValueObject\Name;
use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

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

    #[ManyToOne(targetEntity: Role::class)]
    #[JoinColumn(name: 'role_slug', referencedColumnName: 'slug', nullable: false, onDelete: 'RESTRICT')]
    private Role $role;

    public static function create(
        Name              $name,
        Email             $email,
        RuPhoneNumber     $phone,
        Role              $role,
        DateTimeImmutable $createdAt = new DateTimeImmutable(),
        DateTimeImmutable $updatedAt = new DateTimeImmutable(),
    ): User
    {
        return (new self())
            ->setName($name)
            ->setEmail($email)
            ->setPhone($phone)
            ->setRole($role)
            ->setCreatedAt($createdAt)
            ->setUpdatedAt($updatedAt);
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function setName(Name $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function setEmail(Email $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPhone(): RuPhoneNumber
    {
        return $this->phone;
    }

    public function setPhone(RuPhoneNumber $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function setRole(Role $role): self
    {
        $this->role = $role;
        return $this;
    }
}