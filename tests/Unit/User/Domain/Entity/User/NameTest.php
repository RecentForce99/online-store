<?php

namespace App\Tests\Unit\User\Domain\Entity\User;

use App\Common\Domain\Exception\Validation\GreaterThanMaxLengthException;
use App\Common\Domain\Exception\Validation\LessThanMinLengthException;
use App\User\Domain\ValueObject\Name;
use PHPUnit\Framework\TestCase;

final class NameTest extends TestCase
{
    public function testValidName(): void
    {
        $name = Name::fromString('John Doe');

        $this->assertInstanceOf(Name::class, $name);
        $this->assertEquals('John Doe', $name->value());
    }

    public function testNameTooShort(): void
    {
        $this->expectException(LessThanMinLengthException::class);
        $this->expectExceptionMessage('The name [J] is too short, it must be minimum [2] characters.');

        Name::fromString('J');
    }

    public function testNameTooLong(): void
    {
        $this->expectException(GreaterThanMaxLengthException::class);
        $this->expectExceptionMessage('The name [' . str_repeat('A', 101) . '] is too long, it must be maximum [100] characters.');

        Name::fromString(str_repeat('A', 101));
    }

    public function testNameEqualComparison(): void
    {
        $name1 = Name::fromString('John');
        $name2 = Name::fromString('John');

        $this->assertTrue($name1->isEqualTo($name2));
    }

    public function testNameNotEqualComparison(): void
    {
        $name1 = Name::fromString('John');
        $name2 = Name::fromString('Jane');

        $this->assertFalse($name1->isEqualTo($name2));
    }

    public function testToString(): void
    {
        $name = Name::fromString('John Doe');

        $this->assertEquals('John Doe', (string) $name);
    }
}