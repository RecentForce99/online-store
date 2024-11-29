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
use App\Role\Application\Exception\RoleNotFound;
use App\Role\Domain\Entity\Role;
use App\Role\Domain\Repository\RoleRepositoryInterface;
use App\User\Application\Exception\EmailHasBeenTakenException;
use App\User\Application\Exception\PhoneHasBeenTakenException;
use App\User\Domain\Entity\User;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\ValueObject\Name;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Uid\AbstractUid;

final class SignUpCommandHandler
{
    private const string AUTHORIZED_USER_ROLE_SLUG = 'authorized_user';
    private const string AUTHORIZED_USER_ROLE_NAME = 'Авторизованный пользователь';

    public function __construct(
        private readonly RoleRepositoryInterface     $roleRepository,
        private readonly UserRepositoryInterface     $userRepository,
        private readonly AbstractUid                 $uuid,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
        private readonly FlusherInterface            $flusher,
        private readonly EventDispatcherInterface    $eventDispatcher,
    )
    {
    }

    /**
     * @throws GreaterThanMaxLengthException
     * @throws WrongLengthOfPhoneNumberException
     * @throws InvalidEmailException
     * @throws LessThanMinLengthException
     * @throws RoleNotFound
     * @throws EmailHasBeenTakenException
     * @throws PhoneHasBeenTakenException
     */
    public function __invoke(SignUpCommand $signUpCommand): void
    {
        $authorizedUserRole = $this->roleRepository->findBySlug(self::AUTHORIZED_USER_ROLE_SLUG);

        $this->validate($authorizedUserRole, $signUpCommand);

        $user = User::create(
            name: Name::fromString($signUpCommand->name),
            email: Email::fromString($signUpCommand->email),
            phone: RuPhoneNumber::fromInt($signUpCommand->phone),
            promoId: $this->getPromoId($signUpCommand),
            roles: new ArrayCollection([$authorizedUserRole])
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
     * @throws RoleNotFound
     */
    private function validate(
        ?Role         $authorizedUserRole,
        SignUpCommand $signUpCommand,
    ): void
    {
        if (true === is_null($authorizedUserRole)) {
            throw RoleNotFound::bySlug(self::AUTHORIZED_USER_ROLE_SLUG, self::AUTHORIZED_USER_ROLE_NAME);
        }

        if (false === $this->userRepository->isEmailAvailable($signUpCommand->email)) {
            throw EmailHasBeenTakenException::byEmail($signUpCommand->email);
        }

        if (false === $this->userRepository->isPhoneAvailable($signUpCommand->phone)) {
            throw PhoneHasBeenTakenException::byPhone($signUpCommand->phone);
        }
    }

    private function getPromoId(SignUpCommand $signUpCommand): ?AbstractUid
    {
        return is_null($signUpCommand->promoId)
            ? null
            : $this->uuid::fromString($signUpCommand->promoId);
    }
}
