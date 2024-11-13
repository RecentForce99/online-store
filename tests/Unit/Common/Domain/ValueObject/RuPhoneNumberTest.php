<?php

namespace App\Tests\Unit\Common\Domain\ValueObject;

use App\Common\Domain\ValueObject\RuPhoneNumber;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class RuPhoneNumberTest extends TestCase
{
    public function testValidPhoneNumber(): void
    {
        $phoneNumber = RuPhoneNumber::fromInt(1234567890);

        $this->assertInstanceOf(RuPhoneNumber::class, $phoneNumber);
        $this->assertEquals(1234567890, $phoneNumber->value());
    }

    public function testPhoneNumberTooShort(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The length of the phone number [12345] is [5], whereas it must be [10].');

        RuPhoneNumber::fromInt(12345);
    }

    public function testPhoneNumberTooLong(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The length of the phone number [' . str_repeat('1', 11) . '] is [11], whereas it must be [10].');

        RuPhoneNumber::fromInt(11111111111);
    }

    public function testPhoneNumberEqualComparison(): void
    {
        $phone1 = RuPhoneNumber::fromInt(1234567890);
        $phone2 = RuPhoneNumber::fromInt(1234567890);

        $this->assertTrue($phone1->isEqualTo($phone2));
    }

    public function testPhoneNumberNotEqualComparison(): void
    {
        $phone1 = RuPhoneNumber::fromInt(1234567890);
        $phone2 = RuPhoneNumber::fromInt(9876543210);

        $this->assertFalse($phone1->isEqualTo($phone2));
    }

    public function testPhoneNumberIsLessThan(): void
    {
        $phone1 = RuPhoneNumber::fromInt(1234567890);
        $phone2 = RuPhoneNumber::fromInt(9876543210);

        $this->assertTrue($phone1->isLessThan($phone2));
    }

    public function testPhoneNumberIsGreaterThan(): void
    {
        $phone1 = RuPhoneNumber::fromInt(9876543210);
        $phone2 = RuPhoneNumber::fromInt(1234567890);

        $this->assertTrue($phone1->isGreaterThan($phone2));
    }
}