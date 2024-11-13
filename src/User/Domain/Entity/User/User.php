<?php

declare(strict_types=1);

namespace App\User\Domain\Entity\User;

use App\Common\Domain\ValueObject\Email;
use App\Common\Domain\ValueObject\RuPhoneNumber;
use App\User\Domain\Entity\AbstractBaseEntity;
use App\User\Domain\ValueObject\Name;
use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'users')]
class User extends AbstractBaseEntity
{
    #[Column(type: 'string', length: 2)]
    private Name $name;
    #[Column(type: 'string', unique: true)]
    private Email $email;
    #[Column(type: 'bigint', unique: true, options: ['unsigned' => true])]
    private RuPhoneNumber $phone;

    protected function __construct(
        Name          $name,
        Email         $email,
        RuPhoneNumber $phone,
    )
    {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
    }

    public static function create(
        Name          $name,
        Email         $email,
        RuPhoneNumber $phone,
    ): User
    {
        return (new self(
            name: $name,
            email: $email,
            phone: $phone,
        ))
            ->setCreatedAt(new DateTimeImmutable())
            ->setUpdatedAt(new DateTimeImmutable());
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
}