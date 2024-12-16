<?php

declare(strict_types=1);

namespace App\Common\Domain\ValueObject;

use App\Common\Domain\Exception\Validation\GreaterThanMaxLengthException;
use App\Common\Domain\Exception\Validation\InvalidEmailException;
use App\Common\Domain\Exception\Validation\LessThanMinLengthException;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class Email
{
    private const int VALIDATION_MIN_LENGTH = 3;
    private const int VALIDATION_MAX_LENGTH = 255;

    #[ORM\Column(type: 'string', length: self::VALIDATION_MAX_LENGTH, unique: true)]
    private string $email;

    /**
     * @throws GreaterThanMaxLengthException
     * @throws InvalidEmailException
     * @throws LessThanMinLengthException
     */
    private function __construct(string $email)
    {
        $emailLength = strlen($email);
        if (self::VALIDATION_MIN_LENGTH > $emailLength) {
            throw LessThanMinLengthException::byField(
                'почта',
                $email,
                $emailLength,
            );
        }

        if (self::VALIDATION_MAX_LENGTH < $emailLength) {
            throw GreaterThanMaxLengthException::byField(
                'почта',
                $email,
                $emailLength,
            );
        }

        if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw InvalidEmailException::byEmail($email);
        }

        $this->email = $email;
    }

    /**
     * @throws GreaterThanMaxLengthException
     * @throws InvalidEmailException
     * @throws LessThanMinLengthException
     */
    public static function fromString(string $email): Email
    {
        return new Email($email);
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
