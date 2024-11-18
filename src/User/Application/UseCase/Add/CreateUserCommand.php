<?php

declare(strict_types=1);

namespace App\User\Application\UseCase\Add;

use App\Common\Domain\ValueObject\Email;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateUserCommand
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Type(
            type: 'string',
            message: 'Некорректное имя',
        )]
        public string $name,

        #[Assert\NotBlank]
        #[Assert\Type(
            type: 'string',
            message: 'Некорректный E-mail',
        )]
        public string $email,

        #[Assert\NotBlank]
        #[Assert\Type(
            type: 'integer',
            message: 'Номер телефона должен соответствовать формату +7 (XXX) XXX XX-XX',
        )]
        public int $phone,
    )
    {
    }
}