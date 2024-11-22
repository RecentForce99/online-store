<?php

declare(strict_types=1);

namespace App\Role\Domain\Entity;

use App\User\Domain\Entity\User;
use DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints\Unique;

#[Entity]
#[Table(name: 'roles')]
class Role
{
    /**
     * Better-off to use UUID id to inherit AbstractEntity
     * But this way is a new possibility to use slug as primary key which I've never done before
     */
    #[Id]
    #[Column(type: 'string', length: 255)]
    private string $slug;

    #[Column(type: 'string', unique: true, length: 255)]
    private string $name;

    #[OneToMany(mappedBy: 'role', targetEntity: User::class)]
    private Collection $users;

    #[Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[Column(type: 'datetime_immutable')]
    private DateTimeImmutable $updatedAt;

    private function __construct(string $slug)
    {
        $this->slug = $slug;
    }

    public static function create(
        string            $slug,
        string            $name,
        DateTimeImmutable $createdAt = new DateTimeImmutable(),
        DateTimeImmutable $updatedAt = new DateTimeImmutable(),
    ): Role
    {
        return (new static($slug))
            ->setName($name)
            ->setCreatedAt($createdAt)
            ->setUpdatedAt($updatedAt);
    }

    public function getSlug(): string
    {
        return $this->slug;
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

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function setUsers(Collection $users): Role
    {
        $this->users = $users;
        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): Role
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTimeImmutable $updatedAt): Role
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}