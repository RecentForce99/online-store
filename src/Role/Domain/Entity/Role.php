<?php

declare(strict_types=1);

namespace App\Role\Domain\Entity;

use App\Common\Domain\Entity\AbstractBaseEntity;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;

#[ORM\Entity]
#[ORM\Table(name: 'roles')]
class Role  extends AbstractBaseEntity
{
    #[ORM\Column(type: 'string', length: 255)]
    private string $slug;

    #[ORM\Column(type: 'string', unique: true, length: 255)]
    private string $name;

    public static function create(
        string            $slug,
        string            $name,
        DateTimeImmutable $createdAt = new DateTimeImmutable(),
        DateTimeImmutable $updatedAt = new DateTimeImmutable(),
    ): Role
    {
        return (new static())
            ->setSlug($slug)
            ->setName($name)
            ->setCreatedAt($createdAt)
            ->setUpdatedAt($updatedAt);
    }

    public function getId(): UuidV4
    {
        return $this->id;
    }

    public function setId(UuidV4 $id): Role
    {
        $this->id = $id;
        return $this;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): Role
    {
        $this->slug = $slug;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Role
    {
        $this->name = $name;
        return $this;
    }
}