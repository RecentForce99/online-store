<?php

declare(strict_types=1);

namespace App\Auth\Application\UseCase\SignUp;

use App\Auth\Application\Event\AfterUserSignUpEvent;
use App\Common\Domain\Exception\Validation\GreaterThanMaxLengthException;
use App\Common\Domain\Exception\Validation\InvalidEmailException;
use App\Common\Domain\Exception\Validation\LessThanMinLengthException;
use App\Common\Domain\Exception\Validation\WrongLengthOfPhoneNumberException;
use App\Common\Domain\Repository\FlusherInterface;
use App\Common\Domain\ValueObject\Email;
use App\Common\Domain\ValueObject\RuPhoneNumber;
use App\Role\Domain\Repository\RoleRepositoryInterface;
use App\User\Application\Exception\EmailHasBeenTakenException;
use App\User\Application\Exception\PhoneHasBeenTakenException;
use App\User\Domain\Entity\User;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\ValueObject\Name;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\AbstractUid;

#[AsMessageHandler]
final class SignUpCommandHandler
{
    public function __construct(
        private readonly RoleRepositoryInterface $roleRepository,
        private readonly UserRepositoryInterface $userRepository,
        private readonly AbstractUid $uuid,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly FlusherInterface $flusher,
        private readonly EventDispatcherInterface $eventDispatcher,
    ) {
    }

    /**
     * @throws GreaterThanMaxLengthException
     * @throws WrongLengthOfPhoneNumberException
     * @throws InvalidEmailException
     * @throws LessThanMinLengthException
     * @throws EmailHasBeenTakenException
     * @throws PhoneHasBeenTakenException
     */
    public function __invoke(SignUpCommand $signUpCommand): void
    {
        $userRole = $this->roleRepository->getUserRole();

        $this->assertUserCanSignUp($signUpCommand);

        $user = User::create(
            name: Name::fromString($signUpCommand->name),
            email: Email::fromString($signUpCommand->email),
            phone: RuPhoneNumber::fromInt($signUpCommand->phone),
            promoId: $this->getPromoId($signUpCommand),
            roles: new ArrayCollection([$userRole])
        );

        $hashedPassword = $this->userPasswordHasher->hashPassword($user, $signUpCommand->password);
        $user->setPassword($hashedPassword);

        $this->userRepository->add($user);
        $this->flusher->flush();

        $afterUserSignUpEvent = new AfterUserSignUpEvent($signUpCommand);
        $this->eventDispatcher->dispatch($afterUserSignUpEvent);
    }

    /**
     * @throws EmailHasBeenTakenException
     * @throws PhoneHasBeenTakenException
     */
    private function assertUserCanSignUp(
        SignUpCommand $signUpCommand,
    ): void {
        if (false === $this->userRepository->isEmailAvailable($signUpCommand->email)) {
            throw EmailHasBeenTakenException::byEmail($signUpCommand->email);
        }

        if (false === $this->userRepository->isPhoneAvailable($signUpCommand->phone)) {
            throw PhoneHasBeenTakenException::byPhone($signUpCommand->phone);
        }
    }

    private function getPromoId(SignUpCommand $signUpCommand): ?AbstractUid
    {
        return true === is_null($signUpCommand->promoId)
            ? null
            : $this->uuid::fromString($signUpCommand->promoId);
    }
}
