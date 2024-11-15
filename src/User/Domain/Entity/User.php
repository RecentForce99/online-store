<?php

declare(strict_types=1);

namespace App\User\Domain\Entity;

use App\Common\Domain\Entity\AbstractBaseEntity;
use App\Common\Domain\ValueObject\Email;
use App\Common\Domain\ValueObject\RuPhoneNumber;
use App\User\Domain\ValueObject\Name;
use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Uid\UuidV4;

#[Entity]
#[Table(name: 'users')]
class User extends AbstractBaseEntity
{
    #[Column(type: 'string', length: 2)]
    private Name $name;
    #[Column(type: 'string', unique: true, length: 255)]
    private Email $email;
    #[Column(type: 'bigint', length: 10, unique: true, options: ['unsigned' => true])]
    private RuPhoneNumber $phone;

    protected function __construct(
        Name          $name,
        Email         $email,
        RuPhoneNumber $phone,
    )
    {
        $this->id = new UuidV4();
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
    }

    public static function add(
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