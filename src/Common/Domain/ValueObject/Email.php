<?php

declare(strict_types=1);

namespace App\Common\Domain\ValueObject;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Response;

final class Email extends StringValue
{
    private const int MIN_LENGTH = 3;
    private const int MAX_LENGTH = 255;

    protected function __construct(protected string $value)
    {
        $email = $value;

        $emailLength = strlen($value);
        if (self::MIN_LENGTH > $emailLength) {
            throw new InvalidArgumentException(sprintf(
                'The email [%s] is too short, it must be minimum [%s] characters.',
                $email,
                self::MIN_LENGTH,
            ),
                Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if (self::MAX_LENGTH < $emailLength) {
            throw new InvalidArgumentException(
                sprintf(
                    'The email [%s] is too long, it must be maximum [%s] characters.',
                    $email,
                    self::MAX_LENGTH,
                ),
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }

        if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException(
                sprintf(
                    'The email [%s] is invalid.',
                    $email,
                ),
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }

        parent::__construct($email);
    }
}