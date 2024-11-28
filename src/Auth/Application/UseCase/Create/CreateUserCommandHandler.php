<?php

declare(strict_types=1);

namespace App\Auth\Application\UseCase\Create;

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

final class CreateUserCommandHandler
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
    public function __invoke(CreateUserCommand $createUserCommand): void
    {
        $authorizedUserRole = $this->roleRepository->findBySlug(self::AUTHORIZED_USER_ROLE_SLUG);

        $this->validate($authorizedUserRole, $createUserCommand);

        $user = User::create(
            name: Name::fromString($createUserCommand->name),
            email: Email::fromString($createUserCommand->email),
            phone: RuPhoneNumber::fromInt($createUserCommand->phone),
            promoId: $this->getPromoId($createUserCommand),
            roles: new ArrayCollection([$authorizedUserRole])
        );

        $hashedPassword = $this->userPasswordHasher->hashPassword($user, $createUserCommand->password);
        $user->setPassword($hashedPassword);

        $this->userRepository->add($user);
        $this->flusher->flush();

        $afterUserSignUpEvent = new AfterUserSignUpEvent($createUserCommand);
        $this->eventDispatcher->dispatch($afterUserSignUpEvent);
    }

    /**
     * @throws EmailHasBeenTakenException
     * @throws PhoneHasBeenTakenException
     * @throws RoleNotFound
     */
    private function validate(
        ?Role             $authorizedUserRole,
        CreateUserCommand $createUserCommand,
    ): void
    {
        if (true === is_null($authorizedUserRole)) {
            throw RoleNotFound::bySlug(self::AUTHORIZED_USER_ROLE_SLUG, self::AUTHORIZED_USER_ROLE_NAME);
        }

        if (false === $this->userRepository->isEmailAvailable($createUserCommand->email)) {
            throw EmailHasBeenTakenException::byEmail($createUserCommand->email);
        }

        if (false === $this->userRepository->isPhoneAvailable($createUserCommand->phone)) {
            throw PhoneHasBeenTakenException::byPhone($createUserCommand->phone);
        }
    }

    private function getPromoId(CreateUserCommand $createUserCommand): ?AbstractUid
    {
        return is_null($createUserCommand->promoId)
            ? null
            : $this->uuid::fromString($createUserCommand->promoId);
    }
}
