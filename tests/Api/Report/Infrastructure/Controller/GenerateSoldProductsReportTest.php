<?php

declare(strict_types=1);

namespace App\Tests\Api\Report\Infrastructure\Controller;

use App\Common\Domain\Exception\Validation\GreaterThanMaxLengthException;
use App\Common\Domain\Exception\Validation\GreaterThanMaxValueException;
use App\Common\Domain\Exception\Validation\InvalidEmailException;
use App\Common\Domain\Exception\Validation\LessThanMinLengthException;
use App\Common\Domain\Exception\Validation\LessThanMinValueException;
use App\Common\Domain\Exception\Validation\WrongLengthOfPhoneNumberException;
use App\Common\Domain\ValueObject\Email;
use App\Common\Domain\ValueObject\RuPhoneNumber;
use App\Order\Domain\Entity\DeliveryType;
use App\Order\Domain\Entity\Order;
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

final class GenerateSoldProductsReportTest extends AbstractApiBaseTestCase
{
    private const string CONTROLLER_NAME = 'report.sold_products.generate';
    private User $user;

    /**
     * @throws GreaterThanMaxLengthException
     * @throws InvalidEmailException
     * @throws LessThanMinLengthException
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


        $this->user = User::create(
            name: Name::fromString('Less Grossman'),
            email: Email::fromString('less-grossman@example.com'),
            phone: RuPhoneNumber::fromInt(1234567890),
            promoId: UuidV4::v4(),
            delivery: Delivery::create('New York', '999999999999999'),
            roles: new ArrayCollection([$role]),
        );
        $userPassword = $this->passwordHasher->hashPassword($this->user, 'password');
        $this->user->setPassword($userPassword);
        $this->entityManager->persist($this->user);


        $this->flusher->flush();

        $this->client->loginUser($this->user);
    }

    /**
     * @throws ProductAlreadyAddedToCartException
     * @throws GreaterThanMaxLengthException
     * @throws LessThanMinValueException
     * @throws GreaterThanMaxValueException
     */
    public function testSuccessGenerateSoldProductsReport(): void
    {
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


        $this->user->addProductToCart($product);


        $orderStatus = OrderStatus::create(
            'payment_required',
            'Ожидается оплата',
        );
        $this->entityManager->persist($orderStatus);


        $deliveryType = DeliveryType::create(
            'pickup',
            'Самовывоз',
        );
        $this->entityManager->persist($deliveryType);


        $order = Order::create(
            user: $this->user,
            phone: $this->user->getPhone()->getPhone(),
            status: $orderStatus,
            delivery: Delivery::create('Address', '9999999999999'),
            deliveryType: $deliveryType,
        );
        $this->user->checkoutOrder($order);

        $this->flusher->flush();


        $this->sendRequestByControllerName(self::CONTROLLER_NAME);

        $this->assertEquals(Response::HTTP_CREATED, $this->client->getResponse()->getStatusCode());
        $this->assertJson($this->client->getResponse()->getContent());
    }

    public function testFailedGenerateSoldProductsReportDueToNotFoundAnyProductsSoldInLast24Hours(): void
    {
        $this->sendRequestByControllerName(self::CONTROLLER_NAME);

        $responseJson = $this->client->getResponse()->getContent();
        $responseData = $this->decoder->decode($responseJson, 'json');

        $this->assertEquals(
            Response::HTTP_NOT_FOUND,
            $this->client->getResponse()->getStatusCode(),
        );
        $this->assertJson($responseJson);
        $this->assertEquals('fail', $responseData['result'] ?? null);
    }
}
