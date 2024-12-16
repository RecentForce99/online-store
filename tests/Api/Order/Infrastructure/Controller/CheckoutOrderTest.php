<?php

declare(strict_types=1);

namespace App\Tests\Api\Order\Infrastructure\Controller;

use App\Common\Domain\Exception\Validation\GreaterThanMaxLengthException;
use App\Common\Domain\Exception\Validation\GreaterThanMaxValueException;
use App\Common\Domain\Exception\Validation\InvalidEmailException;
use App\Common\Domain\Exception\Validation\LessThanMinLengthException;
use App\Common\Domain\Exception\Validation\LessThanMinValueException;
use App\Common\Domain\Exception\Validation\WrongLengthOfPhoneNumberException;
use App\Common\Domain\ValueObject\Email;
use App\Common\Domain\ValueObject\RuPhoneNumber;
use App\Order\Domain\Entity\DeliveryType;
use App\Order\Domain\Entity\OrderStatus;
use App\Product\Domain\Entity\Product;
use App\Role\Domain\Entity\Role;
use App\Tests\Api\AbstractApiBaseTestCase;
use App\User\Application\Exception\ProductAlreadyAddedToCartException;
use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\Delivery;
use App\User\Domain\ValueObject\Name;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Uid\UuidV4;

final class CheckoutOrderTest extends AbstractApiBaseTestCase
{
    private const string CONTROLLER_NAME = 'order.checkout';

    /**
     * @throws GreaterThanMaxLengthException
     * @throws InvalidEmailException
     * @throws LessThanMinLengthException
     * @throws ProductAlreadyAddedToCartException
     * @throws WrongLengthOfPhoneNumberException
     * @throws GreaterThanMaxValueException
     * @throws LessThanMinValueException
     */
    protected function setUp(): void
    {
        parent::setUp();


        $role = Role::create(
            'ROLE_USER',
            'Пользователь',
        );
        $this->entityManager->persist($role);



        $orderStatus = OrderStatus::create(
            'payment_required',
            'Ожидается оплата',
        );
        $this->entityManager->persist($orderStatus);



        $user = User::create(
            name: Name::fromString('Less Grossman'),
            email: Email::fromString('less-grossman@example.com'),
            phone: RuPhoneNumber::fromInt(1234567890),
            promoId: UuidV4::v4(),
            delivery: Delivery::create('New York', '999999999999999'),
            roles: new ArrayCollection([$role]),
        );
        $userPassword = $this->passwordHasher->hashPassword($user, 'password');
        $user->setPassword($userPassword);
        $this->entityManager->persist($user);



        $product = Product::create(
            name: 'Product 1',
            weight: 10,
            height: 10,
            width: 10,
            length: 10,
            description: 'Description',
            cost: 100,
            tax: 0,
            version: 1,
        );
        $this->entityManager->persist($product);



        $user->addProductToCart($product);


        $this->flusher->flush();

        $this->client->loginUser($user);
    }

    public function testSuccessCheckoutOrderForPickupDeliveryType(): void
    {
        $deliveryTypeSlug = 'pickup';
        $deliveryType = DeliveryType::create(
            $deliveryTypeSlug,
            'Самовывоз',
        );
        $this->entityManager->persist($deliveryType);
        $this->flusher->flush();

        $this->sendRequestByControllerName(self::CONTROLLER_NAME, [
            'deliveryType' => $deliveryTypeSlug,
        ]);

        $this->checkJsonableResponseByHttpCode(Response::HTTP_CREATED);
    }

    public function testSuccessCheckoutOrderForCourierDeliveryType(): void
    {
        $deliveryTypeSlug = 'courier';
        $deliveryType = DeliveryType::create(
            $deliveryTypeSlug,
            'Самовывоз',
        );
        $this->entityManager->persist($deliveryType);
        $this->flusher->flush();

        $this->sendRequestByControllerName(self::CONTROLLER_NAME, [
            'deliveryType' => $deliveryTypeSlug,
        ]);

        $this->checkJsonableResponseByHttpCode(Response::HTTP_CREATED);
    }

    public function testFailedCheckoutOrderDueToWrongDeliveryType(): void
    {
        $this->sendRequestByControllerName(self::CONTROLLER_NAME, [
            'deliveryType' => 'wrong-type',
        ]);

        $this->checkJsonableResponseByHttpCode(Response::HTTP_NOT_FOUND);
    }
}
