<?php

declare(strict_types=1);

namespace App\Common\Domain\ValueObject;

use App\Common\Domain\Exception\Validation\GreaterThanMaxLengthException;
use App\Common\Domain\Exception\Validation\InvalidEmailException;
use App\Common\Domain\Exception\Validation\LessThanMinLengthException;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Response;

#[ORM\Embeddable]
final class Email
{
    private const int VALIDATION_MIN_LENGTH = 3;
    private const int VALIDATION_MAX_LENGTH = 255;

    #[ORM\Column(type: 'string')]
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
            throw new LessThanMinLengthException(
                sprintf(
                    'The email [%s] is too short, it must be minimum [%s] characters.',
                    $email,
                    self::VALIDATION_MIN_LENGTH,
                ),
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }

        if (self::VALIDATION_MAX_LENGTH < $emailLength) {
            throw new GreaterThanMaxLengthException(
                sprintf(
                    'The email [%s] is too long, it must be maximum [%s] characters.',
                    $email,
                    self::VALIDATION_MAX_LENGTH,
                ),
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
        }

        if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException(
                sprintf(
                    'The email [%s] is invalid.',
                    $email,
                ),
                Response::HTTP_INTERNAL_SERVER_ERROR,
            );
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

    public function setEmail(string $email): Email
    {
        $this->email = $email;

        return $this;
    }
}
