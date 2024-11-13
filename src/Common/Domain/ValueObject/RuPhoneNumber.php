<?php

declare(strict_types=1);

namespace App\Common\Domain\ValueObject;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

final class RuPhoneNumber extends IntegerValue
{
    private const int LENGTH = 10;

    protected function __construct(protected int $value)
    {
        $phoneNumber = abs($value);

        $phoneNumberString = strval($phoneNumber);
        $phoneNumberLength = strlen($phoneNumberString);
        if (self::LENGTH !== $phoneNumberLength) {
            throw new InvalidArgumentException(
                sprintf(
                    'The length of the phone number [%d] is [%s], whereas it must be [%d].',
                    $phoneNumber,
                    $phoneNumberLength,
                    self::LENGTH,
                ),
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }

        parent::__construct($phoneNumber);
    }
}
