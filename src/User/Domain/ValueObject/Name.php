<?php

declare(strict_types=1);

namespace App\User\Domain\ValueObject;

use App\Common\Domain\Exception\Validation\GreaterThanMaxLengthException;
use App\Common\Domain\Exception\Validation\LessThanMinLengthException;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class Name
{
    private const int VALIDATION_MIN_LENGTH = 2;
    private const int VALIDATION_MAX_LENGTH = 100;

    #[ORM\Column(type: 'string', length: self::VALIDATION_MAX_LENGTH)]
    private string $name;

    /**
     * @throws GreaterThanMaxLengthException
     * @throws LessThanMinLengthException
     */
    private function __construct(string $name)
    {
        $nameLength = strlen($name);
        if (self::VALIDATION_MIN_LENGTH > $nameLength) {
            throw LessThanMinLengthException::byField(
                'имя',
                $name,
                self::VALIDATION_MIN_LENGTH,
            );
        }

        if (self::VALIDATION_MAX_LENGTH < $nameLength) {
            throw GreaterThanMaxLengthException::byField(
                'имя',
                $name,
                self::VALIDATION_MAX_LENGTH,
            );
        }

        $this->name = $name;
    }

    /**
     * @throws GreaterThanMaxLengthException
     * @throws LessThanMinLengthException
     */
    public static function fromString(string $name): Name
    {
        return new Name($name);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Name
    {
        $this->name = $name;

        return $this;
    }
}
