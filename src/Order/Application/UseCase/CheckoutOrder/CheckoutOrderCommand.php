<?php

declare(strict_types=1);

namespace App\Order\Application\UseCase\CheckoutOrder;

use Symfony\Component\Validator\Constraints as Assert;

final class CheckoutOrderCommand
{
    #[Assert\Type(
        type: 'int',
        message: 'Номер телефона должен быть числом.',
    )]
    public ?int $phone = null;

    #[Assert\NotBlank(
        message: 'Доставка должна быть обязательна указана.',
    )]
    public string $deliveryType;

    #[Assert\Type(
        type: 'integer',
        message: 'Кладр id должен быть числом',
    )]
    #[Assert\Range(
        min: 1,
        max: 100,
    )]
    public int $kladrId;

    #[Assert\Type(
        type: 'string',
        message: 'Адрес должен быть строкой',
    )]
    public string $address;

    public string $userId; // This param is set from code
}
