<?php

declare(strict_types=1);

namespace App\Common\Domain\Trait;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV4;

trait HasId
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    protected UuidV4 $id;

    public function getId(): UuidV4
    {
        return $this->id;
    }

    protected function setId(UuidV4 $id): self
    {
        $this->id = $id;

        return $this;
    }
}
