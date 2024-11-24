<?php

declare(strict_types=1);

namespace App\Common\Domain\ValueObject;

abstract class IntegerValue
{
    protected function __construct(protected int $value)
    {
    }

    public static function fromInt(int $value): static
    {
        return new static($value);
    }

    public function value(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string)$this->value();
    }

    public function isEqualTo(self $other): bool
    {
        return $this->value() === $other->value();
    }

    public function isLessThan(self $other): bool
    {
        return $this->value() < $other->value();
    }

    public function isGreaterThan(self $other): bool
    {
        return $this->value() > $other->value();
    }
}
