<?php

declare(strict_types=1);

namespace App\User\Domain\ValueObject;

use App\Common\Domain\Exception\Validation\GreaterThanMaxLengthException;
use App\Common\Domain\Exception\Validation\GreaterThanMaxValueException;
use App\Common\Domain\Exception\Validation\LessThanMinValueException;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class Delivery
{
    private const int ADDRESS_VALIDATION_MIN_LENGTH = 1;
    private const int ADDRESS_VALIDATION_MAX_LENGTH = 255;
    private const int KLADR_ID_VALIDATION_MAX_LENGTH = 25;

    #[ORM\Column(type: 'string', length: self::ADDRESS_VALIDATION_MAX_LENGTH)]
    private string $address;

    #[ORM\Column(type: 'string', length: self::KLADR_ID_VALIDATION_MAX_LENGTH)]
    private string $kladrId;

    /**
     * @throws GreaterThanMaxValueException
     * @throws LessThanMinValueException
     * @throws GreaterThanMaxLengthException
     */
    private function __construct(string $address, string $kladrId)
    {
        $addressLength = strlen($address);
        if (self::ADDRESS_VALIDATION_MIN_LENGTH > $addressLength) {
            throw LessThanMinValueException::byField(
                'адрес',
                $address,
                self::ADDRESS_VALIDATION_MIN_LENGTH,
            );
        }

        if (self::ADDRESS_VALIDATION_MAX_LENGTH < $addressLength) {
            throw GreaterThanMaxValueException::byField(
                'адрес',
                $address,
                self::ADDRESS_VALIDATION_MAX_LENGTH,
            );
        }

        $this->address = $address;

        if (self::KLADR_ID_VALIDATION_MAX_LENGTH < strlen($kladrId)) {
            throw GreaterThanMaxLengthException::byField(
                'кладр id',
                $kladrId,
                self::KLADR_ID_VALIDATION_MAX_LENGTH,
            );
        }

        $this->kladrId = $kladrId;
    }

    /**
     * @throws GreaterThanMaxValueException
     * @throws LessThanMinValueException
     * @throws GreaterThanMaxLengthException
     */
    public static function create(string $address, string $kladrId): self
    {
        return new Delivery($address, $kladrId);
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getKladrId(): string
    {
        return $this->kladrId;
    }
}
