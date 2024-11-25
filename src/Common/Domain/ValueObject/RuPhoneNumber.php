<?php

declare(strict_types=1);

namespace App\Common\Domain\ValueObject;

use App\Common\Domain\Exception\Validation\WrongLengthOfPhoneNumberException;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Response;

#[ORM\Embeddable]
final class RuPhoneNumber
{
    private const int VALIDATION_LENGTH = 10;

    #[ORM\Column(type: 'integer')]
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
            throw new WrongLengthOfPhoneNumberException(
                sprintf(
                    'The length of the phone number [%d] is [%s], whereas it must be [%d].',
                    $ruPhoneNumber,
                    $ruPhoneNumberLength,
                    self::VALIDATION_LENGTH,
                ),
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
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

    public function getRuPhoneNumber(): int
    {
        return $this->phone;
    }

    public function setRuPhoneNumber(int $ruPhoneNumber): RuPhoneNumber
    {
        $this->phone = $ruPhoneNumber;

        return $this;
    }
}
