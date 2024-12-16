<?php

declare(strict_types=1);

namespace App\Auth\Application\Domain\Event\SignUp;

use App\Common\Domain\Event\DomainEventInterface;

final readonly class AfterUserSignUpEvent implements DomainEventInterface
{
    public function __construct(
        public string $email,
        public int $phone,
        public ?string $promoId,
        public string $type = 'sms',
    ) {
    }
}
