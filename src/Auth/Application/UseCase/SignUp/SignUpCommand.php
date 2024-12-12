<?php

declare(strict_types=1);

namespace App\Auth\Application\UseCase\SignUp;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class SignUpCommand
{
    #[Assert\NotBlank]
    #[Assert\Type(
        type: 'string',
        message: 'Некорректное имя',
    )]
    public string $name;

    #[Assert\NotBlank]
    #[Assert\Type(
        type: 'string',
        message: 'Некорректный E-mail',
    )]
    public string $email;

    #[Assert\NotBlank]
    #[Assert\Type(
        type: 'integer',
        message: 'Номер телефона должен быть числом',
    )]
    #[Assert\GreaterThan(0)]
    public int $phone;

    #[Assert\Uuid(
        message: 'Промо id должен соответствовать uuid v4',
    )]
    public ?string $promoId;

    #[Assert\Type(
        type: 'string',
        message: 'Кладр id должен быть строкой',
    )]
    #[Assert\NotBlank(
        message: 'Кладр id обязателен к заполнению',
    )]
    public string $kladrId;

    #[Assert\Type(
        type: 'string',
        message: 'Адрес должен быть строкой',
    )]
    #[Assert\NotBlank(
        message: 'Адрес обязателен к заполнению',
    )]
    public string $address;

    #[Assert\Type(
        type: 'string',
        message: 'Пароль не является строкой',
    )]
    #[Assert\PasswordStrength(
        message: 'Слишком простой пароль',
    )]
    public string $password;

    public function __construct(
        string $name,
        string $email,
        int $phone,
        ?string $promoId,
        string $password,
        string $kladrId,
        string $address,
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->promoId = $promoId;
        $this->password = $password;
        $this->kladrId = $kladrId;
        $this->address = $address;
    }
}
