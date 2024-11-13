<?php

namespace App\Tests\Unit\Common\Domain\ValueObject;

use App\Common\Domain\ValueObject\Email;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

final class EmailTest extends TestCase
{
    public function testValidEmail(): void
    {
        $email = Email::fromString('test@example.com');

        $this->assertInstanceOf(Email::class, $email);
        $this->assertEquals('test@example.com', $email->value());
    }

    public function testEmailTooShort(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The email [te] is too short, it must be minimum [3] characters.');

        Email::fromString('te'); // Email too short
    }

    public function testEmailTooLong(): void
    {
        $longEmail = str_repeat('a', 256) . '@example.com';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The email [' . $longEmail . '] is too long, it must be maximum [255] characters.');

        Email::fromString($longEmail); // Email too long
    }

    public function testInvalidEmailFormat(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('The email [invalid-email] is invalid.');

        Email::fromString('invalid-email'); // Invalid email format
    }

    public function testEmailEqualComparison(): void
    {
        $email1 = Email::fromString('test@example.com');
        $email2 = Email::fromString('test@example.com');

        $this->assertTrue($email1->isEqualTo($email2));
    }

    public function testEmailNotEqualComparison(): void
    {
        $email1 = Email::fromString('test@example.com');
        $email2 = Email::fromString('another@example.com');

        $this->assertFalse($email1->isEqualTo($email2));
    }

    public function testEmailToString(): void
    {
        $email = Email::fromString('test@example.com');

        $this->assertEquals('test@example.com', (string) $email);
    }
}