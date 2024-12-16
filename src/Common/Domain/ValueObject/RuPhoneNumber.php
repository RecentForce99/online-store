<?php

declare(strict_types=1);

namespace App\Common\Domain\ValueObject;

use App\Common\Domain\Exception\Validation\WrongLengthOfPhoneNumberException;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class RuPhoneNumber
{
    private const int VALIDATION_LENGTH = 10;

    #[ORM\Column(type: 'bigint', unique: true, options: ['unsigned' => true])]
    private int $phone;

    /**
     * @throws WrongLengthOfPhoneNumberException
     */
    private function __construct(int $ruPhoneNumber)
    {
        $ruPhoneNumber = abs($ruPhoneNumber);

        $ruPhoneNumberString = strval($ruPhoneNumber);
        $ruPhoneNumberLength = strlen($ruPhoneNumberString);
        if (self::VALIDATION_LENGTH !== $ruPhoneNumberLength) {
            throw WrongLengthOfPhoneNumberException::byPhone($ruPhoneNumber, self::VALIDATION_LENGTH);
        }

        $this->phone = $ruPhoneNumber;
    }

    /**
     * @throws WrongLengthOfPhoneNumberException
     */
    public static function fromInt(int $ruPhoneNumber): RuPhoneNumber
    {
        return new RuPhoneNumber($ruPhoneNumber);
    }

    public function getPhone(): int
    {
        return $this->phone;
    }
}
