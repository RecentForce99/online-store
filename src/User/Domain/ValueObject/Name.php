<?php

declare(strict_types=1);

namespace App\User\Domain\ValueObject;

use App\Common\Domain\Exception\Validation\GreaterThanMaxLengthException;
use App\Common\Domain\Exception\Validation\LessThanMinLengthException;
use App\Common\Domain\ValueObject\StringValue;
use Symfony\Component\HttpFoundation\Response;

final class Name extends StringValue
{
    private const int MIN_LENGTH = 2;
    private const int MAX_LENGTH = 100;

    /**
     * @throws GreaterThanMaxLengthException
     * @throws LessThanMinLengthException
     */
    protected function __construct(protected string $value)
    {
        $name = $value;

        $nameLength = strlen($value);
        if (self::MIN_LENGTH > $nameLength) {
            throw new LessThanMinLengthException(
                sprintf(
                    'The name [%s] is too short, it must be minimum [%s] characters.',
                    $name,
                    self::MIN_LENGTH,
                ),
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }

        if (self::MAX_LENGTH < $nameLength) {
            throw new GreaterThanMaxLengthException(
                sprintf(
                    'The name [%s] is too long, it must be maximum [%s] characters.',
                    $name,
                    self::MAX_LENGTH,
                ),
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }

        parent::__construct($name);
    }
}