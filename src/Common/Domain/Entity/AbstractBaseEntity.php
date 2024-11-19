<?php

declare(strict_types=1);

namespace App\Common\Domain\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\UuidV4;

abstract class AbstractBaseEntity
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    protected UuidV4 $id;

    #[ORM\Column(type: 'datetime_immutable')]
    protected DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetime_immutable')]
    protected DateTimeImmutable $updatedAt;

    public function getId(): AbstractUid
    {
        return $this->id;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
